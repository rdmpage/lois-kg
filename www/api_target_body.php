<?php

// For an annotation target URI (e.g., a BHL PageID or a fragment identifier for a 
// PDF page), return the body of the annotation (typically a taxonomic name id)

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://www.biodiversitylibrary.org/page/13739405';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);


$query = 'prefix schema: <http://schema.org/>
prefix dc: <http://purl.org/dc/elements/1.1/>
prefix dcterm: <http://purl.org/dc/terms/>
prefix oa: <http://www.w3.org/ns/oa#>

select * where 
{
  ?annotation oa:hasTarget <' . $uri . '> .
  ?annotation oa:hasBody ?body .
  ?body schema:name | dc:title ?name .
}';

$values = sparql_query_to_kv($config['sparql_endpoint'], $query);



$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
}

if ($callback != '')
{
	echo $callback . '(';
}

echo json_encode($values);

if ($callback != '')
{
	echo ')';
}


?>
