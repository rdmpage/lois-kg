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

$ids=array(
'Daike_Tian2',
'Laura_Forrest3',
'Wisnu_Ardi',
);

// RBGE
$ids=array(
'Alan_Elliott4',
'Alan_Forrest',
'Alan_Forrest3',
'Aleix_Arnau_Soler',
'Alexandra_Wortley',
'Aline_Finger',
'Andrew_Kenny8',
'Anthony_Miller2',
'Antje_Ahrends',
'Axel_Poulsen',
'Barbara_Mackinder',
'Bhaskar_Adhikari',
'Brian_Coppins',
'Camila_Quinteros_Penafiel',
'Christine_Thompson4',
'Christopher_Walker5',
'Colin_Pendry',
'D_Knott',
'David_Long47',
'David_Mann6',
'David_Mitchell23',
'David_Mitchell51',
'David_Rae2',
'Deborah_Kohn',
'Deborah_Kohn3',
'Diego_Sanchez-Ganfornina',
'Edeline_Gagnon',
'Eden_House2',
'Elspeth_Haston',
'Flavia_Pezzini',
'Gail_Stott',
'Hannah_Wilson23',
'Hans_Sluiman',
'Ian_Hedge',
'Javier_Luna3',
'Jen_Farrar2',
'Joanne_Taylor18',
'John_Howieson4',
'John_Mcneill5',
'Julieth_Serrano2',
'Kanae_Nishii',
'Kate_Miller31',
'Katherine_Hayden',
'Laura_Forrest3',
'Laura_Keddie',
'Leonie_Alexander',
'Louis_Ronse_De_Craene',
'Louise_Olley2',
'M_Hughes2',
'Maca_Gomez-Gutierrez',
'Maggie_Mitchell',
'Marine_Pouget',
'Mark_Newman6',
'Markus_Ruhsam',
'Martin_Gardner',
'Martin_Pullan',
'Mary_Gibby',
'Mayra_Briceno2',
'Melanie_Bayo',
'Michael_Moeller6',
'Michelle_Hart2',
'Natalia_Contreras-Ortiz',
'Neil_Bell',
'Nicky_Sharp2',
'Paulina_Hechenleitner_V',
'Peter_Moonlight',
'Peter_Wilkie',
'Philip_Thomas2',
'R_Yahr',
'Rebecca_Hilgenhof',
'Robert_Cubey',
'Robyn_Drinkwater',
'Roger_Hyam',
'Ruth_Mcgregor',
'Sabina_Knees',
'Sadie_Barber',
'Sally_Eaton3',
'Sally_King4',
'Sarah_Roberts15',
'Sophie_Neale',
'Sophie_Williams4',
'Stephan_Helfer',
'Stephen_Mifsud',
'Suzanne_Cubey',
'Thibauld_Michel',
'Tiina_Sarkinen',
'Tony_Conlon',
'Wu_Huang5',
'Zin_Hter',
);

$ids=array(
'Robert_Stone21',
'Imercia_Mona',
'Syd_Ramdhani',
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

