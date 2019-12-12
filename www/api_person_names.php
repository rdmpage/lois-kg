<?php

// Get names for person

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'urn:lsid:ipni.org:authors:35229-1';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);


$query = 'PREFIX schema: <http://schema.org/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT 
{
<http://example.rss>
	rdf:type schema:DataFeed;
	schema:name "Aliases";
	schema:dataFeedElement ?item .

	?item schema:name ?name .              
	?item rdf:type ?type .
}
WHERE
{ 
  	VALUES ?ipni_author_identifier { "' . $uri . '" }
    ?item schema:sameAs ?ipni_author_identifier .
  	?item schema:name ?name .
  	?item rdf:type ?type .
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
