<?php

error_reporting(E_ALL);

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/sparql.php');


//----------------------------------------------------------------------------------------
function get_entity_types($entity)
{
	/*
	echo '<pre>';
	print_r($entity);
	echo '</pre>';
	*/

	$types = array();
	
	if (isset($entity->{'@graph'}))
	{
		if (is_array($entity->{'@graph'}[0]->{'@type'}))
		{
			$types = $entity->{'@graph'}[0]->{'@type'};
		}
		else
		{
			$types[] = $entity->{'@graph'}[0]->{'@type'};
		}	
	}
	else
	{	
		if (is_array($entity->{'@type'}))
		{
			$types = $entity->{'@type'};
		}
		else
		{
			$types[] = $entity->{'@type'};
		}
	}
	
	return $types;
}

//----------------------------------------------------------------------------------------
function display_entity($uri)
{
	global $config;
		
	$ok = false;	
	
	// Handle hash identifiers
	$uri = str_replace('%23', '#', $uri);
		
	// By default we assume we have this entity so we can get basic info using DESCRIBE
	//$json = sparql_describe($config['sparql_endpoint'], $uri);
	$json = sparql_construct($config['sparql_endpoint'], $uri);

	if ($json != '')
	{
		$entity = json_decode($json);
		$ok = isset($entity->{'@id'}) || isset($entity->{'@graph'});
		//$ok = isset($entity->name);
		
		$ok = true;
	}
	
	// This may be an entity that we refer to but which doesn't exist in the triple store,
	// so we use CONSTRUCT
	if (!$ok)
	{
		$json = sparql_construct($config['sparql_endpoint'], $uri);

		if ($json != '')
		{
			$entity = json_decode($json);
			
			$ok = isset($entity->{'@id'});
			// Not everything has a name so need a better test
			//$ok = isset($entity->name);
		}
	}
		
	if (!$ok)
	{
		// bounce
		header('Location: ' . $config['web_root'] . '?error=Record not found' . "\n\n");
		exit(0);
	}
	
	$title = '';
	$meta = '';
		
	// JSON-LD for structured data in HTML
	$script = "\n" . '<script type="application/ld+json">' . "\n"
		. json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    	. "\n" . '</script>';
    	
    $script .= "\n" . '<script>' . "\n"
		. 'var data = ' . json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    	. ";\n" . '</script>';
    	
 	display_html_start($title, $meta, $script);
 	
 		echo '<div id="output">xxx</div>';

	
	
	$types = get_entity_types($entity);
	
	/*
	echo '<b>';
	print_r($types);
	echo '</b>';
	*/
	
	//echo '<pre>' . print_r($entity) . '</pre>';
	//echo '<pre>' . $json . '</pre>';
	
	$displayed = false;	
	$n = count($types);
	$i = 0;
	while (($i < $n) && !$displayed)
	{
		switch ($types[$i])
		{
		
			case 'ScholarlyArticle':
				echo '<script>render(template_work);</script>';			
				break;
		
			case 'tn:TaxonName':
				echo '<script>render(template_taxon_name);</script>';			
				break;
				
				
			default:
				echo 'Unknown type' . $types[$i];
				break;		
		}	
		$i++;
	}	
	
	
	display_html_end();	
}

//----------------------------------------------------------------------------------------
function display_html_start($title = '', $meta = '', $script = '', $onload = '')
{
	global $config;
	
	echo '<!DOCTYPE html>
<html lang="en">
<head>';

	echo '<meta charset="utf-8">';
	echo $meta;
	echo '
    <!-- base -->
    <base href="' . $config['web_root'] . '" /><!--[if IE]></base><![endif]-->';


	echo '<script src="js/ejs.js"></script>';
	echo '<script src="taxon_name.js"></script>';
	echo '<script src="work.js"></script>';
	
	echo '<script>
function render(template) {

	// Render template 	
	html = ejs.render(template);
	
	// Display
	document.getElementById("output").innerHTML = html;
}
</script>';

	echo $script;
	
	echo '<title>' . $title . '</title>';
	
	echo '	<style>
		/* taxonomic name */
		.genusPart {
			font-style: italic;
		}
		.infragenericEpithet {
			font-style: italic;
		}
		.specificEpithet {
			font-style: italic;
		}
		.infraspecificEpithet {
			font-style: italic;
		}
		
		/* links do not have underlines */
		a:link {
  			text-decoration: none;
		}
		
	</style>
';

	echo '<style type="text/css">
		body { padding:20px; font-family: "Arial Narrow", Arial, sans-serif; line-height:1.5em;}
		h1 { line-height:1.2em;}
	</style>	
	</head>';
	
	if ($onload == '')
	{
		echo '<body>';
	}
	else
	{
		echo '<body onload="' . $onload . '">';
	}
}



//----------------------------------------------------------------------------------------
function display_html_end()
{
	global $config;
	echo '</body>';
	echo '</html>';
}





//----------------------------------------------------------------------------------------
// Home page, or badness happened
function default_display($error_msg = '')
{
	global $config;
	
	$title = $config['site_name'];
	$meta = '';
	$script = '';
	
	display_html_start($title, $meta, $script);
	
	echo '<h1>Hello</h1>';
	
	
	if ($error_msg != '')
	{
		echo '<p>' . $error_msg . '</p>';
	}
	


	display_html_end();
}

//----------------------------------------------------------------------------------------
function main()
{
	$query = '';
		
	// If no query parameters 
	if (count($_GET) == 0)
	{
		default_display();
		exit(0);
	}
		
	// Error message
	if (isset($_GET['error']))
	{	
		$error_msg = $_GET['error'];
		
		default_display($error_msg);
		exit(0);			
	}
	
	// Show entity
	if (isset($_GET['uri']))
	{	
		$uri = $_GET['uri'];
						
		display_entity($uri);
		exit(0);
	}
		
	/*
	// Show search
	if (isset($_GET['q']))
	{	
		$query = $_GET['q'];
		display_search($query);
		exit(0);
	}
	*/	
	
}


main();

?>