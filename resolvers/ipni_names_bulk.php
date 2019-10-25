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

$basedir = $caches['ipni_names'];

$files1 = scandir($basedir);

$chunksize = 1;

$chunks = array_chunk($files1, $chunksize);


$i = 0;

//----------------------------------------------------------------------------------------
$filename = '';
if ($argc < 2)
{
	echo "Usage: ipni_names_bulk.php <n> \n";
	exit(1);
}
else
{
	$i = $argv[1];
}


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
				$url = 'urn:lsid:ipni.org:names:' . $url;
	
				$doc = resolve_url($url, $caches, 'triples');
	
				echo $doc;

	
			}
		}
	}	
}

?>
