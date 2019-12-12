<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'http://worldcat.org/issn/1000-3142';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

// encode things that may break SPARQL, e.g. SICI entities
$uri = str_replace('<', '%3C', $uri);
$uri = str_replace('>', '%3E', $uri);

/*
// ORCID
if (preg_match('/https?:\/\/orcid.org\/(?<id>.*)/', $uri, $m))
{
	$identifier_value = $m['id'];
}
*/

$query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Publications";
				schema:dataFeedElement ?item .
	
				?item 
					rdf:type schema:DataFeedItem;
					rdf:type ?item_type;
					schema:name ?name;
					schema:datePublished ?datePublished;
					schema:description ?description;
					
				schema:identifier ?doi_identifier .
					 ?doi_identifier rdf:type schema:PropertyValue .
					 ?doi_identifier schema:propertyID "doi" .
					 ?doi_identifier schema:value ?doi .
					
			}
			WHERE
			{
              	?creator schema:sameAs "' . $uri . '" .
				?role schema:creator ?creator .
				?item schema:creator ?role .
	
				?item schema:name ?name .
				?item rdf:type ?item_type .
				
               	OPTIONAL
				{
              		?item schema:description ?description .
                }

             	OPTIONAL
				{
              		?item schema:datePublished ?datePublished .
                }				
				
				OPTIONAL
				{
					?item schema:identifier ?doi_identifier .		
					?doi_identifier schema:propertyID "doi" .
					?doi_identifier schema:value ?doi .		
				}  				

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
