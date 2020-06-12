<?php

error_reporting(E_ALL);


//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	echo $data;
	
	return $data;
}


$biostor_id = 206864;

// map to BHL Part

// get BHL creator id

// do we need to add creator id to knolwegde graph, or rely on Wikidata?


// https://www.biodiversitylibrary.org/api2/httpquery.ashx?op=GetPartByIdentifier&type=doi&value=10.4039/Ent38406-12&apikey=<key+value>


// DOI

	$parameters = array(
		'op' => 'GetPartByIdentifier',
		'type' => 'doi',
		'value' => '10.3417/2012054',
		'format' => 'json',
		'apikey' => '0d4f0303-712e-49e0-92c5-2113a5959159'
	);
	
// BioStor

	
	$parameters = array(
		'op' => 'GetPartByIdentifier',
		'type' => 'biostor',
		'value' => '247841',
		'format' => 'json',
		'apikey' => '0d4f0303-712e-49e0-92c5-2113a5959159'
	);
	
	
	$url = 'https://www.biodiversitylibrary.org/api2/httpquery.ashx?' . http_build_query($parameters);

	echo $url . "\n";
	
	$json = get($url);
	
	echo $json;
	
	$obj = json_decode($json);
	
	print_r($obj);




?>
