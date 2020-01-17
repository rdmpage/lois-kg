<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://doi.org/10.11646/phytotaxa.319.3.2';


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
	schema:name "Figures";
	schema:dataFeedElement ?item .

	?item schema:name ?name .              
	?item rdf:type ?type .
	?item schema:description ?description .
	?item schema:thumbnailUrl ?thumbnailUrl .
	
}
WHERE
{
  VALUES ?work { <' . $uri . '>} .
  {
	  ?item schema:isPartOf ?work .
  }
  UNION
  {
      ?work schema:hasPart ?item .
  }
  
	?item rdf:type <http://schema.org/ImageObject> .
	?item <http://schema.org/name> ?name .
	?item <http://schema.org/description> ?description .  
	?item <http://schema.org/thumbnailUrl> ?thumbnailUrl .  
}
ORDER BY ?part			
';


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
