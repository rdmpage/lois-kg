<?php

// Find people based on name


error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)) . '/www/config.inc.php');


//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER => array("Accept: text/x-nquads"),
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	//echo $data;
	
	return $data;
}



//----------------------------------------------------------------------------------------

// id => [n1, n2, n3]

$names = array(

	'urn:lsid:ipni.org:authors:35229-1' => array(	
		//'D J Middleton',
		//'D. J. Middleton',
		//'David J Middleton',
		//'David J. Middleton',
		//'David John Middleton',
		'D.J. Middleton',
	),


/*
'urn:lsid:ipni.org:authors:20019089-1' => array(	
'Carmen Puglisi',
'C. Puglisi',
),
*/


);


foreach ($names as $identifier => $literals)
{
	echo "# $identifier\n";
	
	foreach ($literals as $literal)
	{

		echo "# $literal\n";

		$sparql = 'PREFIX schema: <http://schema.org/>

	CONSTRUCT
	{
		# triple linking creator to identifier
		?person schema:sameAs ?identifier . 
	}
	WHERE
	{ 
		VALUES ?identifier { <' .  $identifier . '> }
		VALUES ?name { "' . $literal . '" }
	
		# person with name
		?person schema:name ?name .
	
		# ignore names already matched via sameAs
		MINUS {
			?person schema:sameAs ?sameAs
		} 
  
		?role schema:creator ?person .
	}';

		$url = $config['sparql_endpoint'] . '?query=' . urlencode($sparql);

		$nt = get($url);
		
		echo $nt . "\n";
	}
	
}



?>
