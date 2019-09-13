<?php

error_reporting(E_ALL);

require_once('../vendor/digitalbazaar/json-ld/jsonld.php');

// SPARQL API wrapper


//----------------------------------------------------------------------------------------
// Upload a file of triples
// $triples_filename is the full path to a file of triples
// $graph_key_name for fuseki is 'graph', for blazegraph is 'context-uri'
function upload_from_file($sparql_endpoint, $triples_filename, $graph_key_name = 'context-uri', $graph_uri = '')
{
	$url = $sparql_endpoint;
	
	if ($graph_uri == '')
	{
	}
	else
	{
		$url .= '?' . $graph_key_name . '=' . $graph_uri;
	}
	
	// text/x-nquads is US-ASCII WTF!?
	//$command = "curl $url -H 'Content-Type: text/x-nquads' --data-binary '@$triples_filename'";

	// text/rdf+n3 is compatible with NT and is UTF-8
	// see https://wiki.blazegraph.com/wiki/index.php/REST_API#RDF_data
	$command = "curl $url -H 'Content-Type: text/rdf+n3' --data-binary '@$triples_filename'";

	echo $command . "\n";
	
	$lastline = system($command, $retval);
	
	//echo "   Last line: $lastline\n";
	//echo "Return value: $retval\n";	
	
	if (preg_match('/data modified="0"/', $lastline)) 
	{
		echo "\nError: no data added\n";
		exit();
	}
}


//----------------------------------------------------------------------------------------
// DESCRIBE a resource, by default return as JSON-LD
// Fuseki and Blazegraph both recognise application/ld+json but for quads
// Fuseki uses application/n-quads whereas Blazegraph uses text/x-nquads
function sparql_describe($sparql_endpoint, $uri, $format='application/ld+json')
{
	$url = $sparql_endpoint;
	
	// Query is string
	$data = 'query=' . urlencode('DESCRIBE <' . $uri . '>');

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
	
	// Fuseki returns nicely formatted JSON-LD, Blazegraph returns array of horrible JSON-LD
	// as first element of an array
	
	$obj = json_decode($response);
	if (is_array($obj))
	{
		$doc = $obj[0];
		
		$context = (object)array(
			'@vocab' => 'http://schema.org/',			
			'dwc' => 'http://rs.tdwg.org/dwc/terms/',			
			'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',			
			'owl' => 'http://www.w3.org/2002/07/owl#',
			
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'dcterms' => 'http://purl.org/dc/terms/',
			'tn' => 'http://rs.tdwg.org/ontology/voc/TaxonName#',
			'tc' => 'http://rs.tdwg.org/ontology/voc/TaxonConcept#',						
			'tcom' => 'http://rs.tdwg.org/ontology/voc/Common#',
			'tm' => 'http://rs.tdwg.org/ontology/voc/Team#',
			'tp' => 'http://rs.tdwg.org/ontology/voc/Person#',
			'tpc' => 'http://rs.tdwg.org/ontology/voc/PublicationCitation#',

			

		);
	
		$compacted = jsonld_compact($doc, $context);
		
		$response = json_encode($compacted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}

//----------------------------------------------------------------------------------------
// CONSTRUCT a resource, by default return as JSON-LD
function sparql_construct($sparql_endpoint, $uri, $format='application/ld+json')
{
	$url = $sparql_endpoint;
	
	// Query is string
	$query = 'CONSTRUCT {
   ?thing ?p ?o .
}
WHERE {
  VALUES ?thing { <' . $uri . '> }
   ?thing ?p ?o .
}';	

	$query = 'PREFIX : <http://schema.org/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

CONSTRUCT 
{
?item ?p ?o .

?item :identifier ?identifier .
     ?identifier a <http://schema.org/PropertyValue> .
     ?identifier <http://schema.org/propertyID> ?identifier_name .
     ?identifier <http://schema.org/value> ?identifier_value .

?item :creator ?role .
     ?role a <http://schema.org/Role> .
     ?role <http://schema.org/creator> ?author .
     ?author <http://schema.org/name> ?author_name .  
	?author <http://schema.org/identifier> ?author_identifier .		
	?author_identifier <http://schema.org/propertyID> "orcid" .
	?author_identifier <http://schema.org/value> ?orcid .
     
             
?item :citation ?cites .
     ?cites <http://schema.org/name> ?bibliographicCitation .   
	?cites <http://schema.org/identifier> ?cites_identifier .		
	?cites_identifier <http://schema.org/propertyID> "doi" .
	?cites_identifier <http://schema.org/value> ?cites_doi .
                 

?item :isPartOf ?container .
     ?container <http://schema.org/name> ?container_name .               


}
WHERE {
  VALUES ?item { <' . $uri . '> }
    ?item ?p ?o .

	OPTIONAL {
		?item <http://schema.org/identifier> ?identifier .		
		?identifier <http://schema.org/propertyID> ?identifier_name .
		?identifier <http://schema.org/value> ?identifier_value .
	} 
  
	OPTIONAL {
		?item <http://schema.org/creator> ?role .	
		?role <http://schema.org/roleName> ?roleName .	
		?role <http://schema.org/creator> ?author .
        ?author <http://schema.org/name> ?author_name . 
        
		OPTIONAL {
			?author <http://schema.org/identifier> ?author_identifier .		
			?author_identifier <http://schema.org/propertyID> "orcid" .
			?author_identifier <http://schema.org/value> ?orcid .
		} 
        
        
	} 
  
	OPTIONAL {
		?item <http://schema.org/citation> ?cites .	
         { ?cites <http://schema.org/description> ?bibliographicCitation . } UNION { ?cites <http://schema.org/name> ?bibliographicCitation . } 
         OPTIONAL
         {
	?cites <http://schema.org/identifier> ?cites_identifier .		
	?cites_identifier <http://schema.org/propertyID> "doi" .
	?cites_identifier <http://schema.org/value> ?cites_doi .
         
         }
 	}  
 	
	OPTIONAL {
		?item <http://schema.org/isPartOf> ?container .	
		?container rdf:type <http://schema.org/Periodical> .
        ?container <http://schema.org/name> ?container_name . 
 	}  
 	


}
ORDER BY ?roleName

';


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
	
	// Fuseki returns nicely formatted JSON-LD, Blazegraph returns array of horrible JSON-LD
	// as first element of an array
	
	$obj = json_decode($response);
	if (is_array($obj))
	{
		$doc = $obj[0];
		
		$doc = $obj;
		
		//echo '<pre>' . print_r($obj) . '<pre>';
		
		
		$context = (object)array(
			'@vocab' => 'http://schema.org/',			
			'dwc' => 'http://rs.tdwg.org/dwc/terms/',			
			'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',			
			'owl' => 'http://www.w3.org/2002/07/owl#',
			
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'dcterms' => 'http://purl.org/dc/terms/',
			'tn' => 'http://rs.tdwg.org/ontology/voc/TaxonName#',
			'tc' => 'http://rs.tdwg.org/ontology/voc/TaxonConcept#',						
			'tcom' => 'http://rs.tdwg.org/ontology/voc/Common#',
			'tm' => 'http://rs.tdwg.org/ontology/voc/Team#',
			'tp' => 'http://rs.tdwg.org/ontology/voc/Person#',
			'tpc' => 'http://rs.tdwg.org/ontology/voc/PublicationCitation#',

			

		);
	
			// creator is always an array
			$creator = new stdclass;
			$creator->{'@id'} = "creator";
			$creator->{'@container'} = "@set";
			
			$context->{'creator'} = $creator;
			
	
	
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
		
		
			$frame = (object)array(
					'@context' => $context,
					'@type' => $type
				);
				
			$data = jsonld_frame($doc, $frame);
				
		}
		
		$response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}

//----------------------------------------------------------------------------------------
// QUERY, by default return as JSON
function sparql_query($sparql_endpoint, $query, $format='application/json')
{
	$url = $sparql_endpoint . '?query=' . urlencode($query);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));

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

	return $response;
}


// test

if (0)
{
	
	upload_from_file(
		'http://localhost:32774/blazegraph/sparql',
		'rdf/309.nq',
		'context-uri',
		'http://www.ipni.org'
		);


}

if (0)
{
	$response = sparql_describe(
	'http://localhost:32774/blazegraph/sparql',
	'urn:lsid:ipni.org:names:309362-1'
	);
	
	echo $response;
	
	
}

if (0)
{
	$response = sparql_query(
	'http://localhost:32779/blazegraph/sparql',
	'SELECT * WHERE { ?s ?p ?o . } LIMIT 5'
	);
	
	echo $response;
		
}

?>
