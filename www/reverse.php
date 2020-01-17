<?php

// PHP JSON-LD doesn't seem to do "reverse" :(
// will need to do this manually (sigh)

error_reporting(E_ALL);
//error_reporting(0); // there is an unexplained error in json-ld php

require_once('../vendor/digitalbazaar/json-ld/jsonld.php');
require_once(dirname(__FILE__) . '/config.inc.php');


// reverse experiments

//----------------------------------------------------------------------------------------
// CONSTRUCT a resource, by default return as JSON-LD
function sparql_construct_special($sparql_endpoint, $uri, $format='application/ld+json')
{
	$url = $sparql_endpoint;
	
	// encode things that may break SPARQL, e.g. SICI entities
	$uri = str_replace('<', '%3C', $uri);
	$uri = str_replace('>', '%3E', $uri);
	
	// Query is string
	$query = 'PREFIX schema: <http://schema.org/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>	
CONSTRUCT {
   ?person ?p ?o .
   
   ?person rdf:type ?type .

	?work schema:creator ?person .  
	?work schema:name ?name .
	?work rdf:type ?work_type .
   
}
WHERE {
  VALUES ?person { <' . $uri . '> }
   ?person ?p ?o .
   
   ?placeholder schema:sameAs ?person .  
   ?role schema:creator ?placeholder .  
   ?work schema:creator ?role .
   ?work schema:name ?name .
   ?work rdf:type ?work_type .
   
}';	

	$data = 'query=' . urlencode($query);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	if ($http_code != 200)
	{
		echo $response;	
		die ("Triple store returned $http_code\n");
	}
	
	curl_close($ch);
	
	//echo '<pre>' . $response . '<pre>';exit();
	
	echo $response;
	
	// Fuseki returns nicely formatted JSON-LD, Blazegraph returns array of horrible JSON-LD
	// as first element of an array
	
	$obj = json_decode($response);
		
	if (is_array($obj))
	{
		
		$doc = $obj;
		
		//echo '<pre>' . print_r($obj) . '<pre>';
		
		
		$context = (object)array(
			'@vocab' => 'http://schema.org/',
			'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',			
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'tp' => 'http://rs.tdwg.org/ontology/voc/Person#',			
			
					
		);

	
		if (0)
		{
			$data = jsonld_compact($doc, $context);
		}
		else
		{
		
		
			$n = count($doc);
			$type = '';
			$i = 0;
			while ($i < $n && $type == '')
			{
				if ($doc[$i]->{'@id'} == $uri)
				{
					$type = $doc[$i]->{'@type'};
				}
				$i++;
			}
		
		/*
			$frame = (object)array(
					'@context' => $context,
					'@type' => $type
				);
				*/
				
			$frame = json_decode('{
    "@context": {
        "@vocab": "http://schema.org/",
        "rdf": "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
        "dc": "http://purl.org/dc/elements/1.1/",
        "tp": "http://rs.tdwg.org/ontology/voc/Person#"
    },
    "@type": "tp:Person",
    "@reverse": {
        "creator": {}
    }
}');
				
			// frames but ignores @reverse
			$data = jsonld_frame($doc, $frame);
				
		}
		
		$response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}


// test

if (1)
{
$uri = 'urn:lsid:ipni.org:authors:20012913-1';

$response = sparql_construct_special(
	$config['sparql_endpoint'],
	$uri
	
);

echo $response;
}

if (0)
{

$in = '{
    "@context": {
        "ex": "http://example.org/"
    },
    "@graph": [
        {
            "@id": "ex:Sub1",
            "@type": "ex:Type1"
        },
        {
            "@id": "ex:Sub2",
            "@type": "ex:Type2",
            "ex:includes": {
                "@id": "ex:Sub1"
            }
        }
    ]
}';

$frame = '{
    "@context": {
        "ex": "http://example.org/"
    },
    "@type": "ex:Type1",
    "@reverse": {
        "ex:includes": {}
    }
}';



$doc = json_decode($in);

$frame = json_decode($frame);
				
$data = jsonld_frame($doc, $frame);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}


?>
