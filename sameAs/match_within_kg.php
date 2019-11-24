<?php

// Match within KG using OpenURL-like query


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

// get list of works with identifiers
function get_works_for_issn ($issn)
{

	$sparql = 'PREFIX schema: <http://schema.org/>
		SELECT ?work
	WHERE 
	{  
	  VALUES ?issn { "' . $issn . '" }
	  BIND( IRI(CONCAT("http://worldcat.org/issn/", ?issn)) as ?container)

	  ?work schema:isPartOf ?container .   
	  ?work schema:volumeNumber ?volume .
	  ?work schema:pageStart ?spage .          
  
  		# only get works with identifier
	  ?work schema:identifier ?identifier .
	  
	}';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	$uris = array();

	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$parameters = array();

			foreach ($binding as $k => $v)
			{
				$parameters[$k] = $v->value;
			}
	
			if (isset($parameters['work']) && ($parameters['work'] != ''))
			{
				$uris[] = $parameters['work'];
			}
		}
	}

	// go

	//print_r($uris);

	return $uris;

}


//----------------------------------------------------------------------------------------


// Given a "definitive" URI (e.g., DOI) find other works in KG that don't have that
// identifier but which match issn, volume, spage triple and add sameAs link

$uris = array(
'https://doi.org/10.11931/guihaia.gxzw201512015',
);


$issn = '1000-3142';

$uris = get_works_for_issn($issn);

foreach ($uris as $uri)
{
	$uri = str_replace('%23', '#', $uri);

	$sparql = 'PREFIX schema: <http://schema.org/>
	select * where 
	{  
      # Definitive version of work
	  VALUES ?work { <' . $uri .'> }
	  ?work schema:volumeNumber ?volume .
	  ?work schema:pageStart ?spage .

	  ?work schema:isPartOf ?container .   
	  BIND(REPLACE(STR(?container), "http://worldcat.org/issn/", "") AS ?issn). 
      
      ?other_version schema:volumeNumber ?volume .
      ?other_version schema:pageStart ?spage .
      ?other_version schema:isPartOf ?container . 
      
      FILTER (?other_version != ?work)
	}';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	//print_r($obj);

	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$parameters = array();

			foreach ($binding as $k => $v)
			{
				$parameters[$k] = $v->value;
			}
		
			if (isset($parameters['other_version']) && ($parameters['other_version'] != ''))
			{
				echo '<' . $parameters['other_version'] . '> <http://schema.org/sameAs> "' . $uri . '" . ' . "\n";	
			}
		}
	}
}

?>

