<?php

require_once(dirname(dirname(__FILE__)) . '/www/config.inc.php');

// Break a big triples file into arbitrary sized chunks for easier uplaoding.

// Glue
$triples_filename = 'csl.nt';
$triples_filename = 'references.nt';
//$triples_filename = 'works.nt';

$basename = str_replace('.nt', '', $triples_filename);
$graph_uri = 'https://crossref.org';

$count = 0;
$total = 0;
$triples = '';

$chunks= 50000;

$delay = 5;

$handle = null;
$output_filename = '';

$chunk_files = array();

$file_handle = fopen($triples_filename, "r");
while (!feof($file_handle)) 
{
	if ($count == 0)
	{
		$output_filename = $basename . '-' . $total . '.nt';
		$chunk_files[] = $output_filename;
		$handle = fopen($output_filename, 'a');
	}

	$line = fgets($file_handle);
	
	fwrite($handle, $line);
	
	if (!(++$count < $chunks))
	{
		fclose($handle);
		
		$total += $count;
		
		echo $total . "\n";
		$count = 0;
		
	}
}

fclose($handle);


echo "--- curl upload.sh ---\n";
$curl = "#!/bin/sh\n\n";
foreach ($chunk_files as $filename)
{
	$curl .= "echo '$filename'\n";
	
	$url = $config['sparql_endpoint'];
	
	if ($graph_uri != '')
	{
		$url .= '?context-uri=' . $graph_uri;
	}
	
	$curl .= "curl $url -H 'Content-Type: text/rdf+n3' --data-binary '@$filename'  --progress-bar | tee /dev/null\n";
	$curl .= "echo ''\n";
	$curl .= "sleep $delay\n";
}

file_put_contents(dirname(__FILE__) . '/upload.sh', $curl);

	
?>	
