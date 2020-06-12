<?php

require_once('utils.php');

//----------------------------------------------------------------------------------------


$filename = "xml.rss.xml.rss";
$filename = "missed.txt";

$count = 1;

$force = true;

$basedir = dirname(__FILE__) . '/rdf';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));

	if (preg_match('/<link>(?<url>.*)<\/link>/', $line, $m))
	{
		echo $m['url'] . "\n";
		
		//exit();
		
		$url = $m['url'];
		
		$uuid = str_replace('http://tb.plazi.org/GgServer/xml/', '', $url);
		
		$uuid_path = create_path_from_sha1($uuid, $basedir);
	
		//echo $uuid_path  . "\n";
	
		$rdf_filename = $uuid_path . '/' . $uuid . '.rdf';
	
		$go = true;
	
		if (file_exists($rdf_filename) && !$force)
		{
			echo "File exists\n";
			$go = false;
		}
	
		if ($go)
		{
			$rdf_url = str_replace('/xml/', '/rdf/', $url);
		
			$xml = get($rdf_url);
		
			file_put_contents($rdf_filename, $xml);
		
		
			// Give server a break every 10 items
			if (($count++ % 10) == 0)
			{
				$rand = rand(1000000, 3000000);
				echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
				usleep($rand);
			}
		
		}		
	}
	
}	


