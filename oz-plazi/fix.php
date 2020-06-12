<?php

// fix missing 

$basedir = dirname(__FILE__) . '/rdf';

$count = 0;

$files1 = scandir($basedir);

foreach ($files1 as $directory1)
{
	if (preg_match('/^[a-z0-9]{2}$/i', $directory1))
	{
		$files = scandir($basedir . '/' . $directory1);
	
		foreach ($files as $filename)
		{
			if (preg_match('/\.rdf/', $filename))
			{
				//echo $filename . "\n";
				
				$xml = file_get_contents($basedir . '/' . $directory1 .'/' . $filename);
				
				if (!preg_match('/<rdf:RDF/', $xml))
				{
					// fetch again
					echo $filename . "\n";
					
				}
				
			}
		}
	}
}






