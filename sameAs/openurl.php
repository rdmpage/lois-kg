<?php

// OpenURL to resolve references by matching to external database


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
// get list of cited works that aren't in database

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
// Given a set of URIs for references that aren't matched to an identifier, get them and try to match

$uris = array(
'https://doi.org/10.1111/j.1756-1051.2012.00012.x%2310-1111-j-1756-1051-2012-00012-x-BIB9-cit9',
'https://doi.org/10.1111/j.1095-8339.2008.00942.x%2310-1111-j-1095-8339-2008-00942-x-BIB13',
'https://doi.org/10.1111/j.1756-1051.2012.00099.x%2310-1111-j-1756-1051-2012-00099-x-BIB10-cit10',
);


// find URIs in KG for a given ISSN that lack identifier

$issn = '1000-3142'; // Guhia

$sparql = 'PREFIX schema: <http://schema.org/>
	SELECT ?work
WHERE 
{  
  VALUES ?issn { "' . $issn . '" }
  BIND( IRI(CONCAT("http://worldcat.org/issn/", ?issn)) as ?container)

  ?work schema:isPartOf ?container .   
  ?work schema:volumeNumber ?volume .
  ?work schema:pageStart ?spage .          
  
  MINUS { ?work schema:identifier ?identifier }
      
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

print_r($uris);


foreach ($uris as $uri)
{
	$uri = str_replace('%23', '#', $uri);

	$sparql = 'PREFIX schema: <http://schema.org/>
	select ?issn ?volume ?spage where 
	{  
	  VALUES ?work { <' . $uri . '> }
	  ?work schema:volumeNumber ?volume .
	  ?work schema:pageStart ?spage .

	  ?work schema:isPartOf ?container .   
	  BIND(REPLACE(STR(?container), "http://worldcat.org/issn/", "") AS ?issn).  
	}';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	//print_r($obj);

	if (isset($obj->results->bindings))
	{
		$parameters = array();

		foreach ($obj->results->bindings[0] as $k => $v)
		{
			$parameters[$k] = $v->value;
		}
	
		//print_r($parameters);
	
		$openurl = http_build_query($parameters);
	
		$url = 'http://localhost/~rpage/microcitation/www/api_openurl.php?' . $openurl;
	
		echo "# $url\n";
	
		$json = get($url);
		$obj = json_decode($json);
		
		//print_r($obj);
	
		if ($obj->status == 200 && $obj->found)
		{
			if (count($obj->results) == 1)
			{
				$identifier = '';
				
				if ($identifier == '')
				{
				
					if (isset($obj->results[0]->doi))
					{
						$identifier  = 'https://doi.org/' . $obj->results[0]->doi;
					}
				}
				
				/*
				// URL		
				if ($identifier == '')
				{
				
					if (isset($obj->results[0]->url))
					{
						$identifier  = $obj->results[0]->url;
					}
				}
				*/
			
			
				if ($identifier != '')
				{
					echo '<' . $uri . '> <http://schema.org/sameAs> <' . $identifier . '> . ' . "\n";		
				}
			}
				
		}
	}
}

?>

