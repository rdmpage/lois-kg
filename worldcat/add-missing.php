<?php

// Generate names for ISSNs that lack them
// For some reason WorldCat doesn't always have a name for a journal


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
	
	//echo $data;
	
	return $data;
}

//----------------------------------------------------------------------------------------

// get list journals with ISSN but not name but with one or more alternates
function get_alternate_names ()
{
	$data = array();

	$sparql = 'PREFIX schema: <http://schema.org/>
	SELECT ?periodical (GROUP_CONCAT(?alternateName;SEPARATOR="|") AS ?alternateNames) 
	WHERE
	{ 
		?periodical rdf:type <http://schema.org/Periodical> .
		MINUS 
		{
			?periodical <http://schema.org/name> ?name .
		}
		?periodical <http://schema.org/alternateName> ?alternateName .
	
	} 
	GROUP BY ?periodical
';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	$uris = array();

	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$data[$binding->periodical->value] = $binding->alternateNames->value;
		}
	}

	return $data;

}

$data = get_alternate_names();

// print_r($data);

foreach ($data as $k => $v)
{
	if (preg_match('/worldcat.org/', $k))
	{
		$parts = explode('|', $v);
		
		echo '<' . $k . '> <http://schema.org/name> "' . addcslashes($parts[0], '"') . '" .' . "\n";
	
	}

}


?>

