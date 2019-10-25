<?php

// Process whole archive of authors

require_once(dirname(__FILE__) . '/rdf-jsonl.php');

// Samsung external drive
$caches = array(
	'indexfungorum' => '/Volumes/Samsung_T5/rdf-archive/indexfungorum/rdf',
	'ipni_names' => '/Volumes/Samsung_T5/rdf-archive/ipni/rdf',
	'ipni_authors' => '/Volumes/Samsung_T5/rdf-archive/ipni/authors',
	'ion' => '/Volumes/Samsung_T5/rdf-archive/ion/rdf',
	'wsc' => '/Volumes/Samsung_T5/rdf-archive/nmbe/rdf',
);	

$basedir = $caches['ipni_authors'];

$files1 = scandir($basedir);

$chunksize = 10;

$chunks = array_chunk($files1, $chunksize);
//print_r($chunks);


$i = 4;

foreach ($chunks[$i] as $directory)
{

	if (preg_match('/^\d+$/', $directory))
	{	
		$files2 = scandir($basedir . '/' . $directory);
	
		//print_r($files2);


		foreach ($files2 as $filename)
		{
			if (preg_match('/^(?<id>.*)\.xml$/', $filename, $m))
			{	
				// create "url" from filename
				$url = $filename;
				$url = str_replace('.xml', '', $url);
				$url = 'urn:lsid:ipni.org:authors:' . $url;
	
				$doc = resolve_url($url, $caches, 'triples');
	
				echo $doc;

	
			}
		}
	}	
}

?>
