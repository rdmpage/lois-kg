<?php

error_reporting(E_ALL);

require_once('couchsimple.php');



// Get BioStor record

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
function biostor_fetch($biostor)
{
	$data = null;
	
	// Just CSL
	$url = 'https://biostor.org/api.php?id=biostor-' . $biostor . '&format=citeproc';
	
	// Elastic JSON
	$url = 'https://biostor.org/api.php?id=biostor-' . $biostor;
		
	$json = get($url);
	
	//echo $json;
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			
			$data->_id = 'https://biostor.org/reference/' . $biostor;
			
			//$data->{'message-format'} = 'application/json';
			$data->{'message-format'} = 'application/vnd.citationstyles.csl+json';
			
			$data->{'message-format'} = 'application/vnd.crossref-api-message+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
			
			$csl = $obj; // CSL only
			
			$csl = $obj->_source->search_result_data->csl; // Elastic JSON
			$csl->thumbnail = $obj->_source->search_result_data->thumbnailUrl;
			$csl->bhl_pages = $obj->_source->search_result_data->bhl_pages;
			
			
			$data->message = $csl;
		}
	}
	
	return $data;
}

//----------------------------------------------------------------------------------------
function upload_biostor($biostor)
{
	global $couch;
	global $force;
	
	$id = 'https://biostor.org/reference/' . $biostor;
	
	$exists = $couch->exists($id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = biostor_fetch($biostor);
				
		
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

if (1)
{
	$force = false;
	$force = true;


	$biostor = 247841;
	$biostor = 247133;
	$biostor = 217668;
	$biostor = 11255;
	
	$data = biostor_fetch($biostor);
	
	//print_r($data);
	
	upload_biostor($biostor);
}	



?>
