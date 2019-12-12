<?php

error_reporting(E_ALL);

require_once('couchsimple.php');

$force = true;
$force = false;

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
function fetch_page($PageID)
{
	global $couch;
	global $force;
	

	$id = 'https://biodiversitylibrary.org/page/' . $PageID;
	
	$exists = $couch->exists($id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
	
		$parameters = array(
			'op' => 'GetPageMetadata',
			'pageid' => $PageID,
			'ocr' => 'true',
			'names' => 'true',
			'format' => 'json',
			'apikey' => '0d4f0303-712e-49e0-92c5-2113a5959159'
		);
	
		$url = 'https://www.biodiversitylibrary.org/api2/httpquery.ashx?' . http_build_query($parameters);

		echo $url . "\n";
		
		$json = get($url);
		
		echo $json;
		
		$doc = json_decode($json);
		$doc->_id = $id;

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

$pages=array(
43642438,
43642434,
43642430,

);

$count = 1;

foreach ($pages as $id)
{
	fetch_page($id);
	
if (($count++ % 10) == 0)
{
    $rand = rand(1000000, 3000000);
    echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
    usleep($rand);
}
	
}

?>
