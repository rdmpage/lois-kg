<?php

// Parse BHL METS from IA and extract key info

$xml = file_get_contents('BoletimdoMuseuP14MuseA_bhlmets.xml');

$dom= new DOMDocument;
$dom->loadXML($xml);
$xpath = new DOMXPath($dom);

$xpath->registerNamespace('mets', 'http://www.loc.gov/METS/'	); 

$xpath_query = '//mets:structMap/mets:div/mets:div';
$divs = $xpath->query ($xpath_query);
foreach($divs as $div)
{
	$obj = new stdclass;

	if ($div->hasAttributes()) 
	{ 
		$attributes = array();
		$attrs = $div->attributes; 
		
		foreach ($attrs as $i => $attr)
		{
			$attributes[$attr->name] = $attr->value; 
		}
	}
	
	if (isset($attributes['TYPE']))
	{
		$obj->type = $attributes['TYPE'];
	}
	if (isset($attributes['ORDER']))
	{
		$obj->order = $attributes['ORDER'];
	}
	if (isset($attributes['ORDERLABEL']))
	{
		$obj->orderlabel = $attributes['ORDERLABEL'];
	}
	if (isset($attributes['LABEL']))
	{
		$obj->label = $attributes['LABEL'];
	}
    
    foreach ($xpath->query('mets:fptr/@FILEID', $div) as $fptr)
    {
    	if (preg_match('/page(?<id>\d+)/', $fptr->firstChild->nodeValue, $m))
    	{
    		$obj->bhl = $m['id'];
    	}
    }
    
    // output 
    print_r($obj);
    
    
}

/*

<mets:div TYPE="page" ORDER="1" LABEL="Cover">
<mets:fptr FILEID="pageImg53333580" />
<mets:fptr FILEID="page53333580" />
</mets:div>

*/

?>
