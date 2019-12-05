<?php

//error_reporting(E_ALL);
error_reporting(0); // there is an unexplained error in json-ld php

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
	
	// encode things that may break SPARQL, e.g. SICI entities
	$uri = str_replace('<', '%3C', $uri);
	$uri = str_replace('>', '%3E', $uri);
	
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

# identifier
?item :identifier ?identifier .
     ?identifier a <http://schema.org/PropertyValue> .
     ?identifier <http://schema.org/propertyID> ?identifier_name .
     ?identifier <http://schema.org/value> ?identifier_value .

# work
?item :creator ?role .
     ?role a <http://schema.org/Role> .
     ?role <http://schema.org/creator> ?author .
     ?role <http://schema.org/name> ?roleName .
     ?author <http://schema.org/name> ?author_name .  
	?author <http://schema.org/identifier> ?author_identifier .		
	?author_identifier <http://schema.org/propertyID> "orcid" .
	?author_identifier <http://schema.org/value> ?orcid .
     
             
#?item :citation ?cites .
#     ?cites <http://schema.org/name> ?bibliographicCitation .   
#	?cites <http://schema.org/identifier> ?cites_identifier .		
#	?cites_identifier <http://schema.org/propertyID> "doi" .
#	?cites_identifier <http://schema.org/value> ?cites_doi .
                 

?item :isPartOf ?container .
     ?container <http://schema.org/name> ?container_name . 
     ?container a ?container_type .
     ?container <http://schema.org/issn> ?issn . 
     
	# taxon name
	?item <http://rs.tdwg.org/ontology/voc/TaxonName#hasBasionym> ?hasBasionym .	
	?hasBasionym <http://purl.org/dc/elements/1.1/title> ?basionym  .               
           
    # types        
	?item <http://rs.tdwg.org/ontology/voc/TaxonName#typifiedBy> ?typifiedBy .	
		?typifiedBy <http://purl.org/dc/elements/1.1/title> ?type_name  .
		?typifiedBy <http://rs.tdwg.org/ontology/voc/TaxonName#typeName> ?typeName .                

	# published in
	?item <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?publishedInCitation .	
	?publishedInCitation a ?tpc_type .
	?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#title> ?tpc_title  .
	?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#volume> ?tpc_volume  .
	?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#number> ?tpc_number  .
	?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#pages> ?tpc_pages  .
	?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#year> ?tpc_year  .
	
	#?publishedInCitation <http://schema.org/name> ?pub_title  . 
	#?publishedInCitation <http://schema.org/thumbnailUrl> ?pub_thumbnailUrl  . 

	?publishedInCitation <http://schema.org/sameAs> ?pub_id .
	?pub_id <http://schema.org/name> ?pub_title .
	
	
	# annotation
	?item <http://rs.tdwg.org/ontology/voc/TaxonName#hasAnnotation> ?annotation .
	?annotation a ?annotation_type .
	?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#noteType> ?noteType .
	?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#objectTaxonName> ?objectTaxonName .
	?objectTaxonName <http://purl.org/dc/elements/1.1/title> ?objectName  .  	
	?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#note> ?note .
	
		
	# Person
	?item <http://rs.tdwg.org/ontology/voc/Person#alias> ?alias .	
		?alias a ?alias_type .
		 ?alias <http://rs.tdwg.org/ontology/voc/Person#forenames> ?alias_forenames  . 
		 ?alias <http://rs.tdwg.org/ontology/voc/Person#isPreferred> ?alias_isPreferred  . 
		 ?alias <http://rs.tdwg.org/ontology/voc/Person#standardForm> ?alias_standardForm  . 
		 ?alias <http://rs.tdwg.org/ontology/voc/Person#surname> ?alias_surname  . 

	# DWC media
	?item <http://rs.tdwg.org/dwc/terms/associatedMedia> ?media .	
		?media a ?media_type .
		?media <http://purl.org/dc/terms/identifier> ?media_identifier  . 

	# Taxon
	?item <http://schema.org/scientificName> ?tn .	
	?tn <http://purl.org/dc/elements/1.1/title> ?tnn  .  

	?item <http://schema.org/parentTaxon> ?parent  .	
	?parent <http://schema.org/name> ?parent_name  .  


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
  
#	OPTIONAL {
#		?item <http://schema.org/citation> ?cites .	
#         { ?cites <http://schema.org/description> ?bibliographicCitation . } UNION { ?cites <http://schema.org/name> ?bibliographicCitation . } 
#         OPTIONAL
#         {
#	?cites <http://schema.org/identifier> ?cites_identifier .		
#	?cites_identifier <http://schema.org/propertyID> "doi" .
#	?cites_identifier <http://schema.org/value> ?cites_doi .
#         
#         }
# 	}  
 	
	OPTIONAL {
		?item <http://schema.org/isPartOf> ?container .	
		OPTIONAL {
			?container rdf:type ?container_type . 
		}
		OPTIONAL {
			?container <http://schema.org/issn> ?issn . 
		}
        ?container <http://schema.org/name> ?container_name . 
 	}  

	OPTIONAL {
		?item <http://rs.tdwg.org/ontology/voc/TaxonName#typifiedBy> ?typifiedBy .	
		?typifiedBy <http://purl.org/dc/elements/1.1/title> ?type_name . 
		OPTIONAL {
			?typifiedBy <http://rs.tdwg.org/ontology/voc/TaxonName#typeName> ?typeName . 
		}
 	}  
 	
	OPTIONAL {
 		?item <http://rs.tdwg.org/ontology/voc/TaxonName#hasBasionym> ?hasBasionym .	
		?hasBasionym <http://purl.org/dc/elements/1.1/title> ?basionym  .               
	}
	
	OPTIONAL {
		?item <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?publishedInCitation .	
		#?publishedInCitation a ?tpc_type .
		
		# tdwg
		OPTIONAL { ?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#title> ?tpc_title  . }
		OPTIONAL { ?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#volume> ?tpc_volume  . }
		OPTIONAL { ?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#number> ?tpc_number  . }
		OPTIONAL { ?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#pages> ?tpc_pages  . }
		OPTIONAL { ?publishedInCitation <http://rs.tdwg.org/ontology/voc/PublicationCitation#year> ?tpc_year  . }
		
		# schema
		#OPTIONAL { ?publishedInCitation <http://schema.org/name> ?pub_title  . }
		#OPTIONAL { ?publishedInCitation <http://schema.org/thumbnailUrl> ?pub_thumbnailUrl } . 

		OPTIONAL { 	
			?publishedInCitation <http://schema.org/sameAs> ?pub_identifier .
			BIND(IRI(?pub_identifier) AS ?pub_id) .
			?pub_id <http://schema.org/name> ?pub_title .
		} . 

	
	}
	
	# annotation
	OPTIONAL {
		?item <http://rs.tdwg.org/ontology/voc/TaxonName#hasAnnotation> ?annotation .
		?annotation rdf:type ?annotation_type .
		?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#noteType> ?noteType .
		OPTIONAL {	
			?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#objectTaxonName> ?objectTaxonName .	
			?objectTaxonName <http://purl.org/dc/elements/1.1/title> ?objectName  .  	
		}
		OPTIONAL {	
			?annotation <http://rs.tdwg.org/ontology/voc/TaxonName#note> ?note .		
		}
		
	}
	
	OPTIONAL {
		?item <http://rs.tdwg.org/ontology/voc/Person#alias> ?alias .	
		?alias a ?alias_type .
		OPTIONAL { ?alias <http://rs.tdwg.org/ontology/voc/Person#forenames> ?alias_forenames  . }
		OPTIONAL { ?alias <http://rs.tdwg.org/ontology/voc/Person#isPreferred> ?alias_isPreferred  . }
		OPTIONAL { ?alias <http://rs.tdwg.org/ontology/voc/Person#standardForm> ?alias_standardForm  . }
		OPTIONAL { ?alias <http://rs.tdwg.org/ontology/voc/Person#surname> ?alias_surname  . }
	
	}
	
	OPTIONAL {
		?item <http://rs.tdwg.org/dwc/terms/associatedMedia> ?media .	
		?media a ?media_type .
		OPTIONAL { ?media <http://purl.org/dc/terms/identifier> ?media_identifier  . }	
	}	
	
	# taxon
	OPTIONAL {
		?item <http://schema.org/scientificName> ?tn .	
		?tn <http://purl.org/dc/elements/1.1/title> ?tnn  .  
	}

	OPTIONAL {
		?item <http://schema.org/parentTaxon> ?parent  .	
		?parent <http://schema.org/name> ?parent_name  .  
	}


}

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
			
			'dwc' => 'http://rs.tdwg.org/dwc/terms/',			
		);
	
			// creator is always an array
			$creator = new stdclass;
			$creator->{'@id'} = "creator";
			$creator->{'@container'} = "@set";
			
			$context->{'creator'} = $creator;

			// publishedInCitation is always an array (because we may have more than one, esp in Index Fungorum)
			$publishedInCitation = new stdclass;
			$publishedInCitation->{'@id'} = "tcom:publishedInCitation";
			$publishedInCitation->{'@container'} = "@set";
			
			$context->{'tcom:publishedInCitation'} = $publishedInCitation;

			// dwc:associatedMedia is always an array
			$associatedMedia = new stdclass;
			$associatedMedia->{'@id'} = "dwc:associatedMedia";
			$associatedMedia->{'@container'} = "@set";
			
			// tn:typifiedBy is always an array
			$typifiedBy = new stdclass;
			$typifiedBy->{'@id'} = "tn:typifiedBy";
			$typifiedBy->{'@container'} = "@set";
			
			$context->{'tn:typifiedBy'} = $typifiedBy;

			
			$context->{'dwc:associatedMedia'} = $associatedMedia;
			
			$scientificName = new stdclass;
			$scientificName->{'@id'} = "scientificName";
			$scientificName->{'@container'} = "@set";
			$context->{'scientificName'} = $scientificName;
			
			$hasAnnotation = new stdclass;
			$hasAnnotation->{'@id'} = "tn:hasAnnotation";
			$hasAnnotation->{'@container'} = "@set";
			
			$context->{'tn:hasAnnotation'} = $hasAnnotation;
			
	
	
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

//----------------------------------------------------------------------------------------
// CONSTRUCT a stream, by default return as JSON-LD
function sparql_construct_stream($sparql_endpoint, $query, $format='application/ld+json')
{
	$url = $sparql_endpoint;

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
			'@vocab' => 'http://schema.org/'	,
			'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',			
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'dcterms' => 'http://purl.org/dc/terms/',			
			'tn' => 'http://rs.tdwg.org/ontology/voc/TaxonName#',
			'tc' => 'http://rs.tdwg.org/ontology/voc/TaxonConcept#',						
			'tcom' => 'http://rs.tdwg.org/ontology/voc/Common#',
			'dwc' => 'http://rs.tdwg.org/dwc/terms/',							
		);
		
			// dataFeedElement is always an array
			$dataFeedElement = new stdclass;
			$dataFeedElement->{'@id'} = "dataFeedElement";
			$dataFeedElement->{'@container'} = "@set";
			
			$context->{'dataFeedElement'} = $dataFeedElement;
	
	
		$frame = (object)array(
			'@context' => $context,
			'@type' => 'http://schema.org/DataFeed'
		);
			
		$data = jsonld_frame($doc, $frame);
	
		
		$response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}


//----------------------------------------------------------------------------------------
// Crude text search in SPARQL
function sparql_search($sparql_endpoint, $search_string, $format='application/ld+json')
{
	$url = $sparql_endpoint;
	
	$query = 'PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT 
{
<http://example.rss>
	rdf:type schema:DataFeed;
	schema:name "Search";
	schema:dataFeedElement ?item .

	?item rdf:type schema:DataFeedItem .
  
	?item schema:name ?name .
	?item rdf:type ?type .
}
WHERE
{
  VALUES ?text { "' . addcslashes($search_string, '"') . '"  }
  {
  	?item ?p ?text .
  }
  UNION
  {
    ?identifier schema:value ?text .
    ?item schema:identifier ?identifier .    
  }
  ?item rdf:type ?type .
  {
    ?item <http://schema.org/name> ?name .
  }
  UNION
  {
     ?item dc:title ?name .
  }  
}

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
			'@vocab' => 'http://schema.org/'	,
			'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',			
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'dcterms' => 'http://purl.org/dc/terms/',			
			'tn' => 'http://rs.tdwg.org/ontology/voc/TaxonName#',
			'tc' => 'http://rs.tdwg.org/ontology/voc/TaxonConcept#',						
			'tcom' => 'http://rs.tdwg.org/ontology/voc/Common#',
			'dwc' => 'http://rs.tdwg.org/dwc/terms/',							
		);
		
			// dataFeedElement is always an array
			$dataFeedElement = new stdclass;
			$dataFeedElement->{'@id'} = "dataFeedElement";
			$dataFeedElement->{'@container'} = "@set";
			
			$context->{'dataFeedElement'} = $dataFeedElement;
	
	
		$frame = (object)array(
			'@context' => $context,
			'@type' => 'http://schema.org/DataFeed'
		);
			
		$data = jsonld_frame($doc, $frame);
	
		
		$response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	}
	

	return $response;
}


// test



if (0)
{
	$response = sparql_search(
	'http://167.71.255.145:9999/blazegraph/sparql',
//	'10.2307/25065588'
//	'Minaria'
//	'Ditassa bifurcata Rapini'
	'Alessandro Rapini'
	
	);
	
	echo $response;
}

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

if (0)
{
	$stream_query = '';
	
	$stream_query = 'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
PREFIX tp: <http://rs.tdwg.org/ontology/voc/Person#>
PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX wd: <http://www.wikidata.org/entity/>	
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>

SELECT *
WHERE
{
  ?creator_identifier schema:value "0000-0001-9036-0912" .
  ?role schema:identifier ?creator_identifier .
  ?creator schema:creator ?role .
  ?work schema:creator ?creator .
  
  # work details
  ?work schema:name ?name . 
  OPTIONAL {
    ?work schema:datePublished ?datePublished .
  }
  OPTIONAL {
    ?work schema:identifier ?doi_identifier .
    ?doi_identifier schema:propertyID "doi" .
    ?doi_identifier schema:value ?doi .
  }  
  
}';

/*
<http://example.rss>
    a :DataFeed ;
    :name "Test RSS Feed" ;
    :url "http://example.rss" ;
    :description "Dependent on the input data or the where condition ( here all datasets with alimayilov )";
    :dataFeedElement ?item .
?item
    a :DataFeedItem;
    a ?type;
*/

$stream_query = 'PREFIX schema: <http://schema.org/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT 
{
<http://example.rss>
	rdf:type schema:DataFeed;
	schema:dataFeedElement ?item .
	
	?item 
		rdf:type schema:DataFeedItem;
		rdf:type ?item_type;
		schema:name ?name;
}
WHERE
{
	?creator_identifier schema:value "0000-0001-9036-0912" .
  	?role schema:identifier ?creator_identifier .
  	?creator schema:creator ?role .
  	?item schema:creator ?creator .
  	
  	?item schema:name ?name .
  	?item rdf:type ?item_type .

}';



	$response = sparql_construct_stream(
	'http://167.71.255.145:9999/blazegraph/sparql',
	$stream_query
	
	);
	
	echo $response;
		
}

?>
