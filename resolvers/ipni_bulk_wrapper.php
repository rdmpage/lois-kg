<?php

// Process whole archive of name

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

//print_r($chunks);

$n = count($chunks);

for ($i = 0; $i < $n; $i++)
{
	$command = 'php ipni_names_bulk.php ' . $i . ' > n' . $i . '.nt';
	
	echo $command . "\n";
	
	system($command);
}


?>
