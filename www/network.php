<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://doi.org/10.1111/j.1469-8137.2010.03279.x';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

$query = 'PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX schema: <http://schema.org/>

SELECT ?source_id ?source_name ?edge_label ?target_id ?intermediate_node ?target_name (GROUP_CONCAT(?out_type;SEPARATOR="|") AS ?type)
WHERE
{
   VALUES ?source_id {<' . $uri . '> }
  {
	?source_id schema:name|dc:title ?source_name  .
  
  	?source_id ?edge_label ?target_id .
  	
  	?target_id rdf:type ?out_type .
    {
  		?target_id schema:name|dc:title ?target_name .
    }
    UNION
    {
      ?target_id schema:creator ?intermediate_node .
      ?intermediate_node schema:name ?target_name .
    }
  }
 

}
GROUP BY ?source_id ?source_name ?edge_label ?target_id ?intermediate_node ?target_name
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
echo sparql_query($config['sparql_endpoint'], $query);
if ($callback != '')
{
	echo ')';
}


?>
