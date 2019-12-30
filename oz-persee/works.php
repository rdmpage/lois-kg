<?php

include '../vendor/autoload.php';

use Sunra\PhpSimple\HtmlDomParser;

//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);

	if ($content_type != '')
	{
		$opts[CURLOPT_HTTPHEADER] = array(
			"Accept: " . $content_type, 
			"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" 
		);
	}	
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}


$dois = array(
'10.3406/jatba.1961.6910',
'10.3406/jatba.1960.2622',
'10.3406/jatba.1956.2342',
'10.3406/jatba.1960.2617',
'10.3406/jatba.1964.2765',
'10.3406/jatba.1970.3065',
'10.3406/jatba.1961.6903',
'10.3406/jatba.1964.2758',
'10.3406/jatba.1954.2163',
'10.3406/jatba.1955.2254',
'10.3406/jatba.1955.2215',
'10.3406/jatba.1966.2869',
'10.3406/jatba.1954.6652',
'10.3406/jatba.1954.2150',
'10.3406/jatba.1959.2533',
'10.3406/jatba.1955.6938',
'10.3406/jatba.1957.2384',
'10.3406/jatba.1956.2323',
'10.3406/jatba.1956.2308',
'10.3406/jatba.1959.2537',
'10.3406/jatba.1961.6897',
'10.3406/jatba.1963.2710',
'10.3406/jatba.1960.2644',
'10.3406/jatba.1961.6892',
'10.3406/jatba.1962.2663',
'10.3406/jatba.1962.2682',
'10.3406/jatba.1962.2683',
'10.3406/jatba.1961.6893',
'10.3406/jatba.1960.2645',
'10.3406/jatba.1961.6825',
'10.3406/jatba.1962.2665',
'10.3406/jatba.1963.2700',
'10.3406/jatba.1962.2673',
'10.3406/jatba.1967.2957',
'10.3406/jatba.1964.2769',
'10.3406/jatba.1961.6817',
);

foreach ($dois as $doi)
{
	$url = 'https://doi.org/' . $doi;

	$html = get($url);

	$dom = HtmlDomParser::str_get_html( $html );

	$count = 1;

	foreach ($dom->find('div[class=contributors] span[itemprop=creator] a') as $a)
	{
		//echo $doi . ' ' . $count . ' ' . $a->href . "\n";
	
		$persee_id = $a->href;
	
		$persee_id = str_replace('https://www.persee.fr/', 'http://data.persee.fr/', $persee_id);
		$persee_id .= '#Person';
	

		echo '<https://doi.org/' . $doi . '#creator-' . $count . '> <http://schema.org/sameAs> <' . $persee_id  . '> .' . "\n";
	
		$count++;
	
	}
}

?>
