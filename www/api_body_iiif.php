<?php

// Given a URI that is the body of an annotation on a page in a IIIF viewer,
// find the IIIF manifest

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'urn:lsid:ipni.org:names:929986-1';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);


$query = 'prefix dcterm: <http://purl.org/dc/terms/>
prefix oa: <http://www.w3.org/ns/oa#>
prefix sc: <http://iiif.io/api/presentation/2#>

select * where 
{
  ?annotation oa:hasBody <' . $uri . '> .
  ?annotation oa:hasTarget ?target .
  ?canvas dcterm:relation ?target .
  ?sequence sc:hasCanvases ?canvas .
  ?manifest sc:hasSequences ?sequence .
}
';

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
