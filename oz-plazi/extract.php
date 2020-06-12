<?php

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
				
				//echo $xml;
				
				$dom= new DOMDocument;
				$dom->loadXML($xml);
				$xpath = new DOMXPath($dom);
				
				$xpath->registerNamespace('trt', 'http://plazi.org/vocab/treatment#');
				$xpath->registerNamespace('dwc', 'http://rs.tdwg.org/dwc/terms/');
				$xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
				
				$title = '';
				
				$count = 0;


				if (1)
				{
					$xpath_query = '//trt:publishedIn/@rdf:resource';
					$nodeCollection = $xpath->query ($xpath_query);
					foreach($nodeCollection as $node)
					{
						//echo $node->firstChild->nodeValue . "\n";
					}
					
					$xpath_query = '//dc:title';
					$nodeCollection = $xpath->query ($xpath_query);
					foreach($nodeCollection as $node)
					{
						//echo $node->firstChild->nodeValue . "\n";
						$title = $node->firstChild->nodeValue;
					}
					
					
				
					$xpath_query = '//dwc:collectionCode';
					$nodeCollection = $xpath->query ($xpath_query);
					foreach($nodeCollection as $node)
					{
						//echo $node->firstChild->nodeValue . "\n";
						/*
						$nc = $xpath->query ('following-sibling::dwc:catalogNumber', $node);
						foreach ($nc as $n)
						{
							$n->firstChild->nodeValue . "\n";
						}
						*/
					}
					
			
					$xpath_query = '//dwc:specimenCode';
					$nodeCollection = $xpath->query ($xpath_query);
					foreach($nodeCollection as $node)
					{
						if ($count == 0)
						{
							echo $title . "\n";
							$count++;
						}
					
						echo $node->firstChild->nodeValue . "\n";
						/*
						$nc = $xpath->query ('following-sibling::dwc:catalogNumber', $node);
						foreach ($nc as $n)
						{
							$n->firstChild->nodeValue . "\n";
						}
						*/
					}													
				
					//<trt:publishedIn rdf:resource="http://doi.org/10.5281/zenodo.3680436"/>
				}
				
					/*
					// specimens
					$xpath_query = '//rdf:Description/rdf:type[@rdf:resource="http://plazi.org/vocab/treatment#Material"]';
					$nodeCollection = $xpath->query ($xpath_query);
					foreach($nodeCollection as $node)
					{
						echo 'x';
						$nc = $xpath->query ('../dwc:collectionCode', $node);
						foreach ($nc as $n)
						{
							$n->firstChild->nodeValue . "\n";
						}
					}
					*/
					


				
			}
		}
	}
}






