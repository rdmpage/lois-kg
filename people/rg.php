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
'Jan_Frits_Veldkamp',

);

$ids=array(
/*'Wilbert_Hetterscheid',
'Gergin_Blagoev',
'Sujeevan_Ratnasingham',
'Jeremy_Dewaard',
'Jayme_Sones',
'Paul_Hebert2',
'Angela_Telfer',
'Monica_Young'*/
'Muhammad_Ashfaq12',
'Arif_Muhammad',
'Rick_West',

);

$ids=array(
'A_A_Thasun_Amarasinghe',
'A_Bauer2',
'Abhijit_Das19',
'Adi_Shabrani',
'Ahmad_Khaldun_Ismail',
'Akira_Mori2',
'Alain_Dubois',
'Alan_Savitzky',
'Alex_Sayok',
'Alex_Slavenko',
'Alexander_Haas7',
'Allen_Allison',
'Anat_Feldman3',
'Andrew_Peterson10',
'Andrew_Tuen',
'Aniruddha_Datta-Roy',
'Annemarie_Ohler',
'Aravind_N_A',
'Arvin_Diesmos',
'Benjamin_Karin',
'Boon_Hee_Kueh',
'Chandramouli_Sr4',
'Choo_Tan',
'Cristiano_Nogueira2',
'Daniel_Pincheira-Donoso',
'Danielle_Klomp',
'Devi_Stuart-Fox',
'Dinesh_Kp',
'Djoko_T_Iskandar',
'DMS_Suranjan_Karunarathna',
'Eliecer_Gutierrez',
'Elyse_Freitas',
'Eng_Wah_Teo',
'Engkamat_Lading',
'Eric_Smith',
'Franco_Andreone',
'George_Zug',
'Gernot_Vogel',
'Ha_Hoang37',
'Harikrishnan_Surendran',
'Hayden_Davis2',
'Heok_Tan',
'Hirohiko_Takeuchi',
'Hoi_Sen_Yong',
'Ht_Lalremsanga2',
'Indraneil_Das2',
'Indraneil_Das2/2#research-items',
'Indraneil_Das2#about',
'Indraneil_Das2#network',
'Indraneil_Das2#projects',
'Indraneil_Das2#research',
'Indraneil_Das2#research-items',
'Inon_Scharf',
'Jayaditya_Purkayastha2',
'Jayasilan_Mohd-Azlan',
'Jigme_Tshelthrim_Wangyal',
'Jongkar_Grinang',
'Jongkar_Grinang2',
'Karthikeyan_Vasudevan4',
'Laurent_Chirio',
'Li_Ding8',
'Luis_Ceriaco',
'Madhurima_Das6',
'Manuel_Schweizer',
'Maria_Novosolov',
'Mark_Auliya',
'Maximilian_Dehling',
'Michael_Cota',
'Misha_Vorobyev',
'Neela_Gayen',
'Norhayati_Ahmad3',
'Norsham_Yaakob',
'Octavio_Mateus',
'Omar_Torres-Carvajal',
'Patrick_David5',
'Peter_Paul_Dijk',
'Peter_Praschag2',
'Peter_Uetz2',
'Ramesh_Aggarwal2',
'Ramlah_Zainudin',
'Raoul_Van_Damme',
'Richard_Shine',
'Richard_Struijk',
'Richard_Wassersug',
'Ronald_Tron',
'Rupa_Nylla_Hooroo',
'Saibal_Sengupta2',
'Saipari_Sailo',
'Saipari_Sailo2',
'Samuel_Shonleben',
'Scott_Weinstein2',
'Sebastian_Klaus',
'Shailendra_Singh102',
'Shashwat_Sirsi',
'Stefan_Hertwig2',
'Taksa_Vasaruchapong',
'Tarun_Garg',
'Teppei_Jono',
'Terry_Ord',
'Tiffany_Doan',
'Todd_Jackman',
'Truong_Nguyen2',
'Uri_Roll',
'Yong_Min_Pui',
'Zoltan_Nagy4',
);

$ids=array(
'Aaron_Gove',
'Alastair_Culham',
'Andrew_Rozefelds',
'Ateeka_Othman',
'Barrie_Jamieson',
'Barry_Traill',
'Beom_Cheol_An',
'Bradley_Potts',
'Brendan_Mackey',
'Bruce_Baldwin3',
'Bruce_Maslin',
'Carla_Bruniera',
'Claudia_Paetzold2',
'Craig_Hempel',
'Cynthia_Morton6',
'D_Sokoloff',
'Daniel_Murphy3',
'Darren_Crayn',
'David_Cantrill',
'David_Posada2',
'Dieter_Boesewinkel',
'Dorothy_Steane',
'Douglas_Soltis',
'Elizabeth_James2',
'Elizabeth_Zimmer',
'Elvira_Hoerandl',
'Erik_Smets',
'Frank_Udovicic',
'Fredrik_Ronquist',
'Friedrich_Ehrendorfer',
'Gareth_Holmes4',
'Gareth_Nelson',
'Gildas_Gateble2',
'Gillian_Brown2',
'Gregory_Jordan2',
'Harun_Rashid',
'Ian_Cowie',
'James_Farris2',
'James_Worth',
'Jamuna_Chhetri',
'Jasmine_Janes',
'Jeremy_Just2',
'Joe_Miller6',
'Joel_Otero',
'John_Hosking2',
'John_Huelsenbeck',
'John_Woinarski',
'Jonathan_Majer',
'Jose_Rubens_Pirani',
'Juliana_Ottra',
'Jun_Wen3',
'Kare_Bremer',
'Kenneth_Wood2',
'Kerry_Gibbons3',
'Kingsley_Dixon',
'Krishnapillai_Sivasithamparam',
'Laura_Holzmeyer',
'Leigh_Johnson9',
'Ludovic_Gielly',
'Marc_Appelhans',
'Marco_Duretto',
'Marco_Duretto#about',
'Marco_Duretto#network',
'Marco_Duretto#research',
'Margaret_Heslewood',
'Maria_Salatino',
'Mark_Clements2',
'Mark_Simmons4',
'Matthew_Barrett2',
'Maurizio_Rossetto',
'Michael_Bayly',
'Michael_Fay4',
'Michael_Jones19',
'Miguel_De_Salas',
'Milton_Groppo',
'Neville_Walsh',
'Niklas_Reichelt',
'Ole_Seberg',
'Pam_Soltis',
'Pamela_Soltis',
'Paul_Forster2',
'Paula_Rudall',
'Peter_Endress',
'Peter_Weston2',
'Philippa_Alvarez',
'Pierre_Taberlet',
'Pieter_Baas',
'Pja_Kessler',
'Reece_Luxton',
'Richard_Jobson',
'Richard_Ree',
'Robert_Dunn3',
'Robert_Hill9',
'Rose_Barrett',
'Russell_Barrett',
'Ryan_Odonnell8',
'Seni_Senjaya3',
'Shane_Wright',
'Stephen_Bell10',
'Stephen_Harris11',
'Suk-Pyo_Hong',
'Tahraoui_Soumeya',
'Terry_Macfarlane',
'Tony_Auld',
'Tony_Start',
'Trevor_Whiffin',
'Trisha_Downing',
'Valerie_Kagy',
'Vernon_Heywood',
'Vincent_Savolainen',
'Warren_Wagner',
'Wayne_Gebert',
'Will_Neal2',
'Xin_Ng2',
'Ze-Long_Nie',
);

$ids=array(
'Megan_Short3',
);

$force = true;

$count = 1;

foreach ($ids as $id)
{
	$filename = dirname(__FILE__) . '/rg-jsonld/' . $id . '.json';
	
	$go = true;
	
	if (file_exists($filename) && !$force)
	{
		echo "Have $id already\n";
		$go = false;
	}
	
	if ($go)
	{


		$url = 'https://www.researchgate.net/profile/' . $id;

		$html = get($url);

		echo $html;

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
			
				file_put_contents($filename, json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
			}
		}
	
		if (($count++ % 5) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
	}	
}

?>

