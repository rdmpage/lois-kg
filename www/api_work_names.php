<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://doi.org/10.26492/gbs69(2).2017-08';

if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);

$query = 'PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Taxonomic names";
				schema:dataFeedElement ?item .
	
				?item rdf:type schema:DataFeedItem .
                ?item tcom:publishedInCitation ?publishedInCitation .
                ?publishedInCitation schema:sameAs ?publication .
              
                ?item schema:name ?name .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
              	VALUES ?publication { <' . $uri . '>} .
              	?pub schema:sameAs ?publication .
              	?item tcom:publishedInCitation ?pub .
               	?item rdf:type  ?type .               
                ?item tn:nameComplete ?name .              
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
