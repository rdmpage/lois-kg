<?php

// Create an IIIF mainfest from triples 

//error_reporting(E_ALL);
error_reporting(0); // there is an unexplained error in json-ld php


require_once('../www/config.inc.php');


require_once('../vendor/digitalbazaar/json-ld/jsonld.php');



//----------------------------------------------------------------------------------------
// CONSTRUCT a resource, by default return as JSON-LD
function sparql_iiif_construct($sparql_endpoint, $uri, $format='application/ld+json')
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
PREFIX oa: <http://www.w3.org/ns/oa#>

CONSTRUCT 
{
?item ?p ?o .

?item <http://iiif.io/api/presentation/2#hasSequences> ?sequences .
     ?sequences ?ps ?os .
     
     ?sequences <http://iiif.io/api/presentation/2#hasCanvases> ?canvases .
     	?canvases ?pc ?oc .

     ?canvases <http://iiif.io/api/presentation/2#hasImageAnnotations> ?images .
     	?images ?pi ?oi .
 
     ?images <http://iiif.io/api/presentation/2#hasAnnotations> ?resource .
     	?resource ?pr ?or .
     


}
WHERE {
  VALUES ?item { <' . $uri . '> }
    ?item ?p ?o .
    
	?item <http://iiif.io/api/presentation/2#hasSequences> ?sequences .
    	 ?sequences ?ps ?os .
    
     ?sequences <http://iiif.io/api/presentation/2#hasCanvases> ?canvases .
     	?canvases ?pc ?oc .

     ?canvases <http://iiif.io/api/presentation/2#hasImageAnnotations> ?images .
     	?images ?pi ?oi .

     ?images <http://iiif.io/api/presentation/2#hasAnnotations> ?resource .
     	?resource ?pr ?or .

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
		// Context 
		$context = new stdclass;

		$context->sc = 'http://iiif.io/api/presentation/2#';

		$context->rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
		$context->type = 'rdf:type';
   
		$context->rdfs = 'http://www.w3.org/2000/01/rdf-schema#';

		$context->dc = 'http://purl.org/dc/elements/1.1/';
		$context->dcterms = 'http://purl.org/dc/terms/';
		$context->dctypes = 'http://purl.org/dc/dcmitype/';


		$context->exif = 'http://www.w3.org/2003/12/exif/ns#';
		$context->oa = 'http://www.w3.org/ns/oa#';


		$context->sequences = new stdclass;
		$context->sequences->{'@id'} = 'sc:hasSequences';
		$context->sequences->{'@container'} = '@set';

		$context->canvases = new stdclass;
		$context->canvases->{'@id'} = 'sc:hasCanvases';
		$context->canvases->{'@container'} = '@set';

		$context->images = new stdclass;
		$context->images->{'@id'} = 'sc:hasImageAnnotations';
		$context->images->{'@container'} = '@set';

		$context->resource = 'sc:hasAnnotations';


		// works
		$context->motivation = new stdclass;
		$context->motivation->{'@type'} = '@id';
		$context->motivation->{'@id'} = 'oa:motivatedBy';

		// works
		$context->on = new stdclass;
		$context->on->{'@type'} = '@id';
		$context->on->{'@id'} = 'oa:hasTarget';


		$context->label 	= 'rdfs:label';
		$context->format 	= 'dcterms:format';

		$context->width 	= 'exif:width';
		$context->height 	= 'exif:height';

		$frame = (object)array(
			'@context' => $context,	
			'@type' => 'http://iiif.io/api/presentation/2#manifest'
		);
		

		$data = jsonld_frame($doc, $frame);		
		
	}
	// Grab the graph, add context, and output as IIIF manifest
	
	$manifest = $data->{'@graph'}[0];
	$manifest->{'@context'} = 'http://iiif.io/api/presentation/2/context.json';
	
	
	// CONSTRUCT doesn't support sorting, so we do this manually to ensure
	// pages will be displayed in the correct order
	
	$canvases = array();
	foreach ($manifest->sequences[0]->canvases as $canvas)
	{
		$canvases[$canvas->{'@id'}] = $canvas;
	}
	
	ksort($canvases);	
	$manifest->sequences[0]->canvases = array_values($canvases);	
	
	$response = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);		
	echo $response;
	
}

sparql_iiif_construct($config['sparql_endpoint'], 'https://archive.org/details/zoologische-verhandelingen-91-001-034/manifest.json');

?>
