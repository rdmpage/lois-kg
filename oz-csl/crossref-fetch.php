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
	
	echo $data;
	
	return $data;
}

//----------------------------------------------------------------------------------------
// CrossRef API
function get_work($doi)
{
	global $couch;

	$data = null;
	
	$url = 'https://api.crossref.org/v1/works/http://dx.doi.org/' . $doi;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			
			$data->_id = 'https://doi.org/' . $doi;
			
			// https://github.com/CrossRef/rest-api-doc#result-types
			$data->{'message-format'} = 'application/vnd.crossref-api-message+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
						
			$data->message = $obj->message;
			
					
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
function crossref_fetch($doi)
{
	global $couch;
	global $force;
	
	$id = 'https://doi.org/' . $doi;
	
	$exists = $couch->exists($id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = get_work($doi);
		
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

	$dois=array(
	"10.1007/s12225-009-9096-4"
	);
	
	// literature cited, funding, and an ORCID
	$dois = array(
	'10.1186/s12862-017-0974-3',
	);
	
	$dois=array(
	'10.11646/phytotaxa.263.1.11'
	);
	
	$dois=array(
	'10.1600/036364412x648733'
	);
	
	$dois=array(
	'10.1080/00837792.1905.10669551'
	);
	
	$dois=array(
	'10.1016/S1567-1356(03)00226-5'
	);
	
	$dois=array(
	'10.1017/s0024282915000328'
	);
	
	$dois=array(
	'10.1002/tax.12017' // ORCID 0000-0002-3604-6532 Per Magnus Jørgensen
	);
	
	$dois=array(
	'10.1007/s13225-017-0386-0', //
	);
	
	// rebuild
	$dois=array(
	'10.1002/tax.12017', // ORCID 0000-0002-3604-6532 Per Magnus Jørgensen
	'10.1017/s0024282915000328',
	);
	
	$dois=array(
	'10.1007/s13225-017-0386-0' // huge number of references
	);
	
	$dois=array(
	'10.1016/S1567-1356(03)00226-5', 
	);
	
	$dois=array(
	'10.2307/25065588' // Taxon
	);
	
	// Rapini IPNI
	$dois=array(
"10.11606/issn.2316-9052.v19i0p55-169",
"10.11606/issn.2316-9052.v21i2p277-279",
"10.2307/4110986",
"10.2307/3393085",
"10.1600/0363644053661832",
"10.13102/neod.41.3",
"10.2307/4110907",
"10.2307/25065588",
"10.13102/neod.32.1",
"10.1007/s12225-008-9087-x",
"10.1017/S0960428609990230",
"10.1007/s12225-011-9262-3",
"10.11646/phytotaxa.26.1.2",
"10.1007/s12225-011-9295-7",
"10.1600/036364412x648733",	);

// Genus Lessingianthus
$dois=array(
"10.5479/si.0081024x.89",
"10.5902/2358198013894",
"10.1111/j.1095-8339.2006.00481.x",
"10.3767/000651906x622247",
"10.5902/2358198014020",
"10.1017/S0960428608005015",
"10.1007/s12228-008-9053-9",
);
$dois=array(
"10.3767/000651912x653813",
"10.5735/085.049.0404",
"10.4067/s0717-66432012000200006",
"10.3100/025.018.0214",
"10.11646/phytotaxa.186.4.4",
"10.11646/phytotaxa.238.1.4",
"10.1590/0102-33062017abb0368",
"10.1590/2175-7860201869224",
);


$dois=array(
'10.11646/phytotaxa.174.4.5',
'10.1017/s0960428608004915',
'10.3897/phytokeys.77.11345',
'10.2307/4118686'
);

$dois=array(
//'10.1111/njb.01126',
//'10.1600/036364408784571581',
'10.1640/0002-8444-103.1.21',
);

	
	$force = true;
	$force = false;
	
	foreach ($dois as $doi)
	{
		crossref_fetch($doi);
	}
}

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

?>
