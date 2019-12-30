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


$id = 'Julia_Caceres-Chamizo';
$id = 'Roger_De_Keyzer';
$id = 'Ingi_Agnarsson';
$id = 'Jeff_Webb2'; // no image, seems to have no linked data
$id = 'Michael_Batley'; 

$id = 'Stefan_Wanke';
$id = 'Colin_Pendry';

$ids=array(
'Stefan_Wanke',
'Colin_Pendry',

'Michael_Batley',
'Jeff_Webb2',
'Ingi_Agnarsson',
'Roger_De_Keyzer',
'Julia_Caceres-Chamizo',
);

$ids=array(
'Huong_Thi_Thanh_Nguyen',
'Bui_Quang',
'DO_VAN_HAI',
'Cuong_Nguyen_The',
'Khang_Nguyen43',
'Jinshuang_Ma',
);

$count = 1;

foreach ($ids as $id)
{


	$url = 'https://www.researchgate.net/profile/' . $id;

	$html = get($url);

	//echo $html;

	$dom = str_get_html($html);

	// Image from meta tag
	$metas = $dom->find('meta[property=og:image]');
	foreach ($metas as $meta)
	{
		echo $meta->content . "\n";
	}

	// JSON-LD
	//$scripts = $dom->find('div[class=profile-detail] script[type=application/ld+json]');
	$scripts = $dom->find('script[type=application/ld+json]');
	foreach ($scripts as $script)
	{
		$json = $script->innertext;
	
		$obj = json_decode($json);
	
		if ($obj->{'@type'} == 'Person')
		{
			print_r($obj);
		
			echo json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			
			file_put_contents(dirname(__FILE__) . '/rg-jsonld/' . $id . '.json', json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
		}
	}
	
	if (($count++ % 5) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
		usleep($rand);
	}
	
}

?>

