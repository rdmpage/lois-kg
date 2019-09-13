<?php

error_reporting(E_ALL);

require_once('couchsimple.php');

$force = true;

//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------
// My microcitation API
function get_work($guid)
{
	global $couch;

	$data = null;
	
	$url = 'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=' . $guid;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			
			$data->_id = $url;
			
			// https://github.com/CrossRef/rest-api-doc#result-types
			$data->{'message-format'} = 'application/vnd.crossref-api-message+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
						
			$data->message = $obj;
					
			/*
			// cited literature (ensure we use same logic when naming these as in CouchDB view)
			// see http://data.crossref.org/schemas/common4.3.7.xsd
			if (isset($data->message->reference))
			{
				// extract and add cited literature to database
				
				foreach ($data->message->reference as $cited) {
				
					// If reference has a DOI we simply add that to our list of links,
					// and these may be added to the queue 
					if (isset($cited->DOI))
					{
						$data->links[] = 'https://doi.org/' . strtolower(trim($cited->DOI));
					} 
					else 
					{
						// Handle citations that lack a DOI
						
						$doc = new stdclass;
						$doc->message = $reference;

						$doc->{'message-format'} = 'application/vnd.crossref-citation+json'; // made up by rdmp	
						$doc->{'message-timestamp'} = date("c", time());
						$doc->{'message-modified'} 	= $doc->{'message-timestamp'};
					
						// need consistent way of identifying these references,
						// note that the "key" used by CrossRef or in HTML version of article
						// is not always compatible with a URI :(
						
						$id = $cited->key;
						
						// clean 						
						$id = preg_replace('/[^a-zA-Z0-9_]/', '', $id);
												
						// make hash id 
						$doc->_id = 'https://doi.org/' . $doi . '#' . $id;
						$doc->cluster_id = $doc->_id;
						
						
						// attempt to parse unstructured string
						if (isset($cited->unstructured) && !isset($cited->{'article-title'}))
						{
							$cited = parse_reference($cited);
						}
						
						$doc->message = $cited;
						
						//print_r($cited);
						
						// Add directly to database
						if (1)
						{
							$exists = $couch->exists($doc->_id);
							if (!$exists)
							{
								$couch->add_update_or_delete_document($doc, $doc->_id, 'add');	
							}
							else
							{
								if ($force)
								{
									$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
								}
							}
						}

					}
				}
			}
			*/


			
		}
	}
	
	return $data;
}


//----------------------------------------------------------------------------------------
function microcitation_fetch($guid)
{
	global $couch;
	global $force;
	
	
	$exists = $couch->exists($guid);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = get_work($guid);
		
		//print_r($doc);
		
		
		if ($doc)
		{
		
			if (!$exists)
			{
				$couch->add_update_or_delete_document($doc, $doc->_id, 'add');	
			}
			else
			{
				if ($force)
				{
					$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
				}
			}
		}
		
	}	
}


// test cases
if (1)
{

	$guids=array(
	"10.3969/j.issn.2095-0845.2005.05.006"
	);
	
	$guids=array(
	"10.7525/j.issn.1673-5102.2016.01.001"
	);
	
	$guids=array(
	"http://ci.nii.ac.jp/naid/110004697473"
	);
	
	$guids=array(
	'10125/12560'
	);
	
	$guids=array(
	'10.6165/tai.1983.28.146'
	);
	
	$guids=array(
	'http://natuurtijdschriften.nl/record/592691'
	);
	
	$guids=array(
	'2324/2695'
	);
	

	$force = true;
	$force = false;
	
	foreach ($guids as $guid)
	{
		microcitation_fetch($guid);
	}
}

/*
// from file
if (0)
{
	$force = false;

	$filename = 'doi.txt';
	
	$file_handle = fopen($filename, "r");
	while (!feof($file_handle)) 
	{
		$doi = trim(fgets($file_handle));
		
		crossref_fetch($doi);
	}
}
*/

?>
