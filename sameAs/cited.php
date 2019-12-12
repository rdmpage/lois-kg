<?php

// Given list of cited papers, get list of ids not in KG and/or map to external database


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

function get_cited_missing ($uri)
{

	$sparql = 'PREFIX schema: <http://schema.org/>
select * 
where
{
	VALUES ?publication { <' . $uri . '>} .
	
	?publication schema:citation ?placeholder .
    MINUS
	{
		?placeholder schema:sameAs ?itemstring .
      	BIND(IRI(?itemstring) AS ?item).
      ?item schema:name ?name .
	}
	
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
	
			if (isset($parameters['placeholder']))
			{
				$uris[] = $parameters['placeholder'];
			}
		}
	}

	// go

	//print_r($uris);

	return $uris;

}

//----------------------------------------------------------------------------------------
// Given a set of URIs for references that aren't matched to an identifier, 
// get them and try to match

$uris = array(
'https://doi.org/10.1017/s0960428618000124#S0960428618000124_ref010',
);



$uris = get_cited_missing('https://doi.org/10.1017/s0960428618000124');


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
	
	//echo $sparql;

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	//print_r($obj);

	if (isset($obj->results->bindings[0]))
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
						$identifier  = $obj->results[0]->doi;
					}
				}
				
				
				// JSTOR		
				if ($identifier == '')
				{
				
					if (isset($obj->results[0]->jstor))
					{
						$identifier  = 'https://www.jstor.org/stable/' . $obj->results[0]->jstor;
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
					echo '<' . $uri . '> <http://schema.org/sameAs> "' . $identifier . '" . ' . "\n";		
				}
			}
				
		}
	}
}

?>

