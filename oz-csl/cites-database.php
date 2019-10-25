<?php

// Citations only that I am harvesting locally, assumes article metadata
// will be added separately, e.g. from CrossRef

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
// My microcitation API for citations
function get_work($url)
{
	global $couch;

	$data = null;
	
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
			
		}
	}
	
	return $data;
}


//----------------------------------------------------------------------------------------
function citation_fetch($guid)
{
	global $couch;
	global $force;
	
	// CouchDB document has URL as _id	
	$url = 'http://localhost/~rpage/microcitation/www/citations?guid=' . $guid;
	
	$exists = $couch->exists($url);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = get_work($url);
		
		print_r($doc);		

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
	"10.11646/phytotaxa.186.4.4"
	);
	
	$guids=array(
	'10.11646/phytotaxa.369.3.1',
	'10.11646/phytotaxa.349.3.1',
	'10.1186/s12862-017-0974-3'
	);	
	
	$guids=array(
//	'10.11646/phytotaxa.26.1.2',
	'10.11646/phytotaxa.159.3.2',
	);
	
	$guids=array(
	'10.11646/phytotaxa.405.3.3'
	);


	$force = true;
	//$force = false;
	
	foreach ($guids as $guid)
	{
		citation_fetch($guid);
	}
}



?>
