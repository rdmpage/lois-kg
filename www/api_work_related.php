<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://doi.org/10.1111/j.1469-8137.2010.03279.x';




/*

      x      y
      |\    /|
      | \  / |
      |  \/  |
      |  /\  |
      | /  \ |
      |/    \|   
      hit   item
      
*/	


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
	schema:name "Related";
	schema:dataFeedElement ?item .

	?item schema:citation ?publication .
	?item schema:name ?name .              
	?item rdf:type ?type .
	?item     schema:description ?description .
	?item    schema:datePublished ?datePublished .
	
	?item    schema:identifier ?doi_identifier .
	 ?doi_identifier rdf:type schema:PropertyValue .
	 ?doi_identifier schema:propertyID "doi" .
	 ?doi_identifier schema:value ?doi .
	
}
WHERE
{
	VALUES ?hit {<' . $uri . '>}

	?xhit_placeholder schema:sameAs ?hit .			
	?x schema:citation ?xhit_placeholder .    

 	?xitem_placeholder schema:sameAs ?item .
 	?x schema:citation ?xitem_placeholder .

	?yhit_placeholder schema:sameAs ?hit .			
	?y schema:citation ?yhit_placeholder .    

 	?yitem_placeholder schema:sameAs ?item .
 	?y schema:citation ?yitem_placeholder .
  
	?item schema:name ?name .
	?item rdf:type ?type .
	
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
	

	FILTER (?hit != ?item)
	FILTER (?x != ?y)
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
