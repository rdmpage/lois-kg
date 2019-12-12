<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://doi.org/10.1111/j.1469-8137.2010.03279.x';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);

$query = 'PREFIX schema: <http://schema.org/>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Other names";
				schema:dataFeedElement ?item .
	
				?item 
					rdf:type schema:DataFeedItem;
					rdf:type ?item_type;
					schema:name ?name;
					schema:description ?description;
										
			}
			WHERE
			{
              VALUES ?this {  <' . $uri . '> } .
              
              # this has a basionym
              {
                ?this tn:hasBasionym ?item .
              }
              # this is a basionym 
              UNION
              {
              	?item tn:hasBasionym ?this .	
              }
              # other names that share a basionym
              UNION
              {
              	?this tn:hasBasionym ?b .
                ?item tn:hasBasionym ?b .
                # exclude IPNI bugs
                FILTER (?b != <urn:lsid:ipni.org:names:0-0>)
                
              }
              # name has been replaced
              UNION
              {
                ?annotation tn:objectTaxonName ?this .
                ?annotation tn:noteType tn:replacementNameFor .
                ?item tn:hasAnnotation ?annotation .
              }
              
			 ?item dc:title ?name .
			 ?item rdf:type ?item_type .
			
			 OPTIONAL
			 {
				?item tcom:publishedIn ?description .
			 }		  
              
             # ensure no returning self
             FILTER (?item != ?this)
			}';


$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
}

if ($callback != '')
{
	echo $callback . '(';
}
echo sparql_construct_stream($config['sparql_endpoint'], $query);
if ($callback != '')
{
	echo ')';
}


?>
