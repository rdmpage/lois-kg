<?php

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(__FILE__) . '/elastic.php');




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
		// Fetch from CouchDB
		
		//echo $id . "\n";
	
		$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/test/_view/elastic?key=" . urlencode('"' . $id . '"'));

		$row = json_decode($resp);
		
		//print_r($row);
		
		// Upload to Elastic
		$elastic_doc = new stdclass;
		$elastic_doc->doc = $row->rows[0]->value;
		$elastic_doc->doc_as_upsert = true;
		
		//print_r($elastic_doc);
		
		$elastic->send('POST',  '_doc/' . urlencode($elastic_doc->doc->id). '/_update', json_encode($elastic_doc));					
		
		
		
	
	}
}

/*
$obj = json_decode($json);

print_r($obj);

foreach ($obj->rows as $row)
{
	$elastic_doc = new stdclass;
	$elastic_doc->doc = $row->value;
	$elastic_doc->doc_as_upsert = true;
	$elastic->send('POST',  '_doc/' . urlencode($elastic_doc->doc->id). '/_update', json_encode($elastic_doc));					
}
*/
	
?>
