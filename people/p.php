<?php

// Harvest ResearchGate profile image and metadata

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER =>  array(
	  	"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" )
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------



// process 


$filename = '320321240.html';

$html = file_get_contents($filename);


$dom = str_get_html($html);

$doi = '';


// DOI from meta tag
$metas = $dom->find('meta[property=citation_doi]');
foreach ($metas as $meta)
{
	$doi = $meta->content;

}


// JSON-LD
//$scripts = $dom->find('div[class=profile-detail] script[type=application/ld+json]');
$scripts = $dom->find('script[type=application/ld+json]');
foreach ($scripts as $script)
{
	$json = $script->innertext;

	$obj = json_decode($json);
	
	if ($obj->{'@type'} == 'ScholarlyArticle')
	{
		// Add DOI if we have one
		if ($doi != '')
		{
			$obj->sameAs = "https://doi.org/" . $doi;
		}
		
		

	}

}


?>

