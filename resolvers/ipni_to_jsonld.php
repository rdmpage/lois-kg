<?php

// Get IPNI as triples for input into a triple store

// suppress errors 
error_reporting(0); // there is an unexplained error in json-ld php

require_once(dirname(__FILE__) . '/rdf-jsonl.php');

// Samsung external drive
$caches = array(
	'indexfungorum' => '/Volumes/Samsung_T5/rdf-archive/indexfungorum/rdf',
	'ipni_names' => '/Volumes/Samsung_T5/rdf-archive/ipni/rdf',
	'ipni_authors' => '/Volumes/Samsung_T5/rdf-archive/ipni/authors',
	'ion' => '/Volumes/Samsung_T5/rdf-archive/ion/rdf',
	'wsc' => '/Volumes/Samsung_T5/rdf-archive/nmbe/rdf',
);	
	
$url = 'urn:lsid:ipni.org:names:77074582-1';

$urls = array(
	'urn:lsid:ipni.org:names:77074582-1',
	'urn:lsid:ipni.org:names:77074584-1',
	'urn:lsid:ipni.org:names:77130382-1',

	'urn:lsid:ipni.org:authors:40176-1',
);

$urls = array(
'urn:lsid:ipni.org:names:20007946-1',
'urn:lsid:ipni.org:names:324597-2',
'urn:lsid:ipni.org:authors:39541-1',
);

/*
$urls = array(
 'urn:lsid:marinespecies.org:taxname:1259280'
);
*/

foreach ($urls as $url)
{
	$doc = resolve_url($url, $caches);
	
	echo json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo "\n";

}  




