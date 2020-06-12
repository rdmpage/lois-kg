<?php

error_reporting(E_ALL);

require_once('couchsimple.php');


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




$force = false;

$filename = 'modified.txt';


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$id = trim(fgets($file_handle));
	
	$go = true;
		
	if ($id == '')
	{
		$go = false;
	}
	
	if (preg_match('/^#/', $id))
	{
		$go = false;
	}
	
	if ($go)
	{

		$exists = $couch->exists($id);

		$go = true;
		if ($exists && !$force)
		{
			echo "Have already\n";
			$go = false;
		}

		if ($go)
		{
			$url = 'http://localhost/~rpage/lois-kg/www/construct.php?uri=' . urlencode($id);
	
			$json = get($url);
	
			echo $json;
		
			$doc = json_decode($json);
			if ($doc)
			{
				$doc->_id = $id;		
		
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

?>

