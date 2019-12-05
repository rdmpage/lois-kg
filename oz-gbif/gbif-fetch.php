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
// GBIF API
function get_occurrence($id)
{
	global $couch;

	$data = null;
	
	$url = 'https://api.gbif.org/v1/occurrence/' . $id;
	
	//echo $url . "\n";
	
	$json = get($url);
	
	//echo $json;
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			
			$data->_id = 'https://www.gbif.org/occurrence/' . $id;
			
			$data->{'message-format'} = 'application/json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
						
			$data->message = $obj;
			



			
		}
	}
	
	return $data;
}


//----------------------------------------------------------------------------------------
function gbif_fetch($id)
{
	global $couch;
	global $force;
	
	$_id = 'https://gbif.org/occurrence/' . $id;
	
	$exists = $couch->exists($_id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = get_occurrence($id);
				
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

	$force = true;
	$force = false;
	

	$ids=array(
	1320279664,
	1318436907,
	912179069
	);
	
	$ids=array(
	1424263254
	);
	
	$ids=array(
	1259102626,
	218946109,
	
	1087225434,
	);
	
	$ids=array(
	1087247105,
	1213645870,
	1087246970,
	);
	
	$ids=array(
	1096785870,
	1096785885
	);
	
	$ids=array(
	1426114122,
	1261856943,
	);
	
	// types Babingtonia angusta A.R.Bean to do
	$ids=array(
	438831061,
	912636794,
	1998796682,
	);
	
	
	
	foreach ($ids as $id)
	{
		gbif_fetch($id);
	}
}

// use API to search for specific records

if (0)
{
	$force = false;
	
	$species = array(
		3581189, // Ditassa bifurcata	
	);



	// 
	foreach ($species as $taxonKey)
	{
		$url = 'https://api.gbif.org/v1/occurrence/search?taxonKey=' . $taxonKey;
	
		// images
		//$url .= '&mediaType=StillImage';
		
		// types
		$url .= '&typeStatus=*';
		
		
		//echo $url;
	
		$json = get($url);
		
		//echo $json;
		
		if ($json != '')
		{
			$obj = json_decode($json);
		
			$ids = array();
		
			if (isset($obj->results))
			{		
				foreach ($obj->results as $result)
				{
					$ids[] = $result->key;
				}
			}
			
			foreach ($ids as $id)
			{
				gbif_fetch($id);
			}			
			
		}
	}
}

// from file
/*
if (1)
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
