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
function ipni_name_fetch($id)
{
	global $couch;
	global $force;
	
	$id = 'urn:lsid:ipni.org:names:' . $id;
	
	$exists = $couch->exists($id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$url = 'https://beta.ipni.org/api/1/n/' . $id;
		
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

$ids = array(
	'77153667-1',
	'77153668-1',
	'77174084-1'
);


foreach ($ids as $id)
{
	ipni_name_fetch($id);
}

?>
