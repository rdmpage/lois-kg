<?php

// Parse IA scan data and output as IIIF manifest

$xml = file_get_contents('BoletimdoMuseuP14MuseA_scandata.xml');

$dom= new DOMDocument;
$dom->loadXML($xml);
$xpath = new DOMXPath($dom);

$triples = array();

$ia_id        = '';
$base_id	 = '';



// Item
$xpath_query = '//book/bookData/bookId';
$nodes = $xpath->query ($xpath_query);
foreach($nodes as $node)
{
	$ia_id  = $node->firstChild->nodeValue;
	$base_id = 'https://archive.org/details/' . $node->firstChild->nodeValue;
}


$subject_id 	= $base_id . '/manifest.json';
$sequences_id 	= $base_id . '/canvas/default';


$triples[] = '<' . $subject_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#manifest> .';
$triples[] = '<' . $subject_id . '> <http://www.w3.org/2000/01/rdf-schema#label> "' . $ia_id  . '" .';

$triples[] = '<' . $subject_id . '> <http://iiif.io/api/presentation/2#hasSequences> <' . $sequences_id . '> .';
$triples[] = '<' . $sequences_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Sequence> .';





// Pages 
$xpath_query = '//book/pageData/page';
$pages = $xpath->query ($xpath_query);
foreach($pages as $page)
{
	$obj = new stdclass;

	if ($page->hasAttributes()) 
	{ 
		$attributes = array();
		$attrs = $page->attributes; 
		
		foreach ($attrs as $i => $attr)
		{
			$attributes[$attr->name] = $attr->value; 
		}
	}
	
	if (isset($attributes['leafNum']))
	{
		$obj->leafNum = $attributes['leafNum'];
	}
    
    foreach ($xpath->query('origWidth', $page) as $node)
    {
 		$obj->width =  $node->firstChild->nodeValue;
    }

    foreach ($xpath->query('origHeight', $page) as $node)
    {
 		$obj->height =  $node->firstChild->nodeValue;
    }
    
    // output 
    print_r($obj);
    
    $leafNum = str_pad($obj->leafNum, 4, '0', STR_PAD_LEFT);
    
    $canvas_id = $base_id . '/canvas/c' . $leafnum;
    
 	$annotation_id = $canvas_id . '/annotation';
 	
	$resource_id = 'http://www.archive.org/download/' . $ia_id . '/' . $ia_id  . '/page/n' . $obj->leafnum . '_medium.jpg';
	
	echo "$canvas_id\n";
	echo "$annotation_id\n";
	echo "$resource_id\n";
		   	
   
    
}

print_r($triples);



?>
