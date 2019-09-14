<?php

// Get IndexFungorum

require_once(dirname(__FILE__) . '/rdf-jsonl.php');

// Samsung external drive
$caches = array(
	'indexfungorum' => '/Volumes/Samsung_T5/rdf-archive/indexfungorum/rdf',
	'ipni_names' => '/Volumes/Samsung_T5/rdf-archive/ipni/rdf',
	'ipni_authors' => '/Volumes/Samsung_T5/rdf-archive/ipni/authors',
	'ion' => '/Volumes/Samsung_T5/rdf-archive/ion/rdf',
	'wsc' => '/Volumes/Samsung_T5/rdf-archive/nmbe/rdf',
);	
	

$urls = array(
	'urn:lsid:indexfungorum.org:names:489999',
	'urn:lsid:indexfungorum.org:names:814035',
);

$urls = array(
	'urn:lsid:indexfungorum.org:names:811978',
);



foreach ($urls as $url)
{
	$doc = resolve_url($url, $caches);
	
	echo json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo "\n";

}  




