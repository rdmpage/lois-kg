<?php

// Upload records from JSONL backup file

require_once(dirname(__FILE__) . '/couchsimple.php');

$filename = 'backup.jsonl';

// CouchDB
$docs = new stdclass;
$docs->docs = array();

$bulk_size = 10;
$bulk_count = 0;
$bulk_override = false;

$skip = 0;

$file_handle = fopen($filename, "r");
while (!feof($file_handle) && !$done) 
{
	$row = trim(fgets($file_handle));
	
	$obj = json_decode($row);
	
	unset($obj->_rev);
	
	$docs->docs [] = $obj;
	echo ".";
	
	if (count($docs->docs ) == $bulk_size)
	{
		if ($bulk_override)
		{
			$docs->new_edits = false;
		}
	
		echo "\nCouchDB...";
		
		print_r($docs);
		
		if ($bulk_count >= $skip)
		{
			$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . '/_bulk_docs', json_encode($docs));
			echo "\nUploaded... total=$bulk_count\n";
		}
		else
		{
			echo "$bulk_count skipping\n";
		}
		
		$bulk_count += $bulk_size;
	
		$docs->docs  = array();
	}
	
}

 // Make sure we load the last set of docs
if (count($docs->docs ) != 0)
{
	echo "CouchDB...\n";
	
	
	if ($bulk_override)
	{
		$docs->new_edits = false;
	}
	
	$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . '/_bulk_docs', json_encode($docs));		
	echo $resp;
	
	
	$bulk_count += count($docs->docs);
	echo "\nUploaded... total=$bulk_count\n";


	$docs->docs  = array();
}	




	
?>	

