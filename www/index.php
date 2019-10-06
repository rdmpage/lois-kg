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
		
	// Use a generic CONSTRUCT to get information on this entity
	$json = sparql_construct($config['sparql_endpoint'], $uri);
	
	$types = array();

	if ($json != '')
	{
		$entity = json_decode($json);
		$ok = isset($entity->{'@id'}) || isset($entity->{'@graph'});
		//$ok = isset($entity->name);
		
		$types = get_entity_types($entity);
	
		/*
		echo '<b>';
		print_r($types);
		echo '</b>';
		*/
		
		$ok = true;
	}
	
	$feed_cites = '';
	$feed_name = '';
	$feed_works = '';
	$feed_children = '';
	
	$feeds = array();
	
	
	//------------------------------------------------------------------------------------
	if ($ok)
	{
		// Get one or more streams of related content for this entity
		
		//--------------------------------------------------------------------------------
		if (in_array('Person', $types))
		{
			$identifier_value = '';
			
			if (preg_match('/https?:\/\/orcid.org\/(?<id>.*)/', $entity->{'@graph'}[0]->{'@id'}, $m))
			{
				$identifier_value = $m['id'];
			}
		
		
			// list of works
			$stream_query = 'PREFIX schema: <http://schema.org/>
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
				?creator_identifier schema:value "' . $identifier_value . '" .
				?role schema:identifier ?creator_identifier .
				?creator schema:creator ?role .
				?item schema:creator ?creator .
	
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
			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['works'] = $feed;
			} 			
		}
		
		//--------------------------------------------------------------------------------
		// Container
		if (in_array('Periodical', $types))
		{
			$container_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of works
			$stream_query = 'PREFIX schema: <http://schema.org/>
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
                    schema:description ?description;
                    schema:datePublished ?datePublished;
					
				schema:identifier ?doi_identifier .
					 ?doi_identifier rdf:type schema:PropertyValue .
					 ?doi_identifier schema:propertyID "doi" .
					 ?doi_identifier schema:value ?doi .
					
			}
			WHERE
			{
				?item schema:isPartOf <' . $container_id . '> .
	
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
			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['works'] = $feed;
			} 			
		}	
		
		//--------------------------------------------------------------------------------
		// Work
		if (in_array('ScholarlyArticle', $types) || in_array('CreativeWork', $types))
		{
			$work_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of names
			$stream_query = 'PREFIX schema: <http://schema.org/>
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
                ?item tcom:publishedInCitation ?publication .
              
                ?item schema:name ?name .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
              	VALUES ?publication { <' . $work_id . '>} .
				?item tcom:publishedInCitation ?publication .
              	?item rdf:type  ?type .
               
                ?item tn:nameComplete ?name .
 			}';
 			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['names'] = $feed;
			} 			
 			
 			// list of cited works
 			$stream_query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Cites";
				schema:dataFeedElement ?item .
	
				?publication schema:citation ?item .
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
              	VALUES ?publication { <' . $work_id . '>} .
				?publication schema:citation ?item .
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
 			}';
 			
			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['cites'] = $feed;
			}
			
 			// list of citing works
 			$stream_query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Cited by";
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
              	VALUES ?publication { <' . $work_id . '>} .
				?item schema:citation ?publication .
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
 			}';
 			
			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['citedby'] = $feed;
			}			

		}				
		
		//--------------------------------------------------------------------------------
		// taxon
		if (in_array('Taxon', $types) || in_array('dwc:Taxon', $types))
		{
			$taxon_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of child taxa
			$stream_query = 'PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Children";
				schema:dataFeedElement ?item .
	
				?item rdf:type schema:DataFeedItem .
                ?item tcom:publishedInCitation ?publication .
              
                ?item schema:name ?name .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
              	VALUES ?parent { <' . $taxon_id . '>} .
  
  				?item schema:parentTaxon ?parent .  
             	?item rdf:type  ?type .             
                ?item schema:name ?name .
 			}
 			';
 			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['children'] = $feed;
			} 			
 

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
		. 	json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    	. "\n" . '</script>';
    	
    
    $script .= "\n" . '<script>' . "\n"
		. 'var data = ' . json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    	. ";\n" . '</script>';    
    
    	
 	display_html_start($title, $meta, $script);
 	
 	echo '<ul>';
 	echo '<li><a href="?uri=https://doi.org/10.13346/j.mycosystema.130120">A new species and a new record of Cylindrosporium in China / 我国柱盘孢属一新种和一新记录种</a></li>';
	echo '<li><a href="?uri=https://doi.org/10.1017/s0024282915000328">Gibbosporina, a new genus for foliose and tripartite, Palaeotropic Pannariaceae species previously assigned to Psoroma</a></li>';
	echo '<li><a href="?uri=https://doi.org/10.1002/tax.12017">On the typification of the lichen genus Lepra Scop.</a></li>';
 	echo '<li><a href="?uri=urn:lsid:indexfungorum.org:names:553579"><i>Taphrina veronaerambellii</i> (Á. Fonseca, J. Inácio & M.G. Rodrigues) Selbmann & Cecchini2017</a></li>';
 	echo '<li><a href="?uri=urn:lsid:ipni.org:names:20007946-1"><i>Ditassa bifurcata</i> Rapini</a></li>'; 	
 	echo '<li><a href="?uri=urn:lsid:ipni.org:names:77074582-1"><i>Minaria</i> T.U.P.Konno & Rapini</a></li>';
	echo '<li><a href="?uri=urn:lsid:ipni.org:authors:39541-1">Alessandro Rapini</a></li>';
	echo '<li><a href="?uri=http://worldcat.org/issn/1672-6472">Mycosystema / 菌物学报</a></li>';
	echo '<li><a href="?uri=https://www.gbif.org/occurrence/1258918210">ISOTYPE of Ditassa bifurcata Rapini [family APOCYNACEAE]</a></li>';
	echo '<li><a href="?uri=https://orcid.org/0000-0003-3926-0478">Manuel G. Rodrigues 0000-0003-3926-0478</a></li>';
	echo '<li><a href="?uri=https://www.ncbi.nlm.nih.gov/pubmed/8984433">К синонимии родовых и гомонимии видовых названий палеарктических мошек (Diptera: Simuliidae)</a></li>';
 
 	echo '<li><a href="?uri=https://orcid.org/0000-0003-4783-3125">Jefferson Prado 0000-0003-4783-3125</a></li>';
 	echo '<li><a href="?uri=https://doi.org/10.1111/njb.01126%2310-1111-njb-01126-BIB0021-cit-0021">Plantes nouvelles ou critiques des serres du Muséum</a> cited by <a href="?uri=https://doi.org/10.1111/njb.01126">Recircumscription and two new species ofPachystachys(Tetrameriumlineage: Justicieae: Acanthaceae)</a></li>';
 
 	echo '<li><a href="?uri=https://www.jstor.org/stable/24529694">New Acanthaceae from Guatemala (JSTOR)</a></li>';
 	
 	echo '<li><a href="?uri=http://www.theplantlist.org/1.1/browse/A/Compositae/Lessingianthus/">Lessingianthus (taxon)</a></li>';
 
 	echo '</ul>';
 	
 	/*
 	echo '<div style="border:1px solid rgb(192,192,192);">';
	foreach (range('A', 'Z') as $char) 
	{
		echo '<li class="';
	
		if ($char == $letter)
		{
			echo 'tab-active';
		}
		else
		{
			echo 'tab';
		}
	
		echo '"><a href="?letter=' . $char . '">' . $char . '</a></li>';
	} 	
	echo '<div style="clear:both;"></div>';
	echo '</div>';
	*/
 	
 	
 		echo '<div id="output">Stuff goes here</div>';
 		
 		echo '<div id="feed_works"></div>';
 		echo '<div id="feed_names"></div>';
 		echo '<div id="feed_cites"></div>';
 		echo '<div id="feed_citedby"></div>';
		echo '<div id="feed_children"></div>';

	
	

	//echo '<pre>' . print_r($entity) . '</pre>';
	//echo '<pre>' . $json . '</pre>';
	
	// feeds
	echo '<script>';
	echo "\n";
	foreach ($feeds as $k => $v)
	{
		echo 'var feed_' . $k . ' = ' . json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';';
		echo "\n";
	}
	echo '</script>';
	echo "\n";
	
	$displayed = false;	
	$n = count($types);
	$i = 0;
	while (($i < $n) && !$displayed)
	{
		switch ($types[$i])
		{
			case 'CreativeWork':
			case 'ScholarlyArticle':
				echo '<script>render(template_work, { item: data }, "output");</script>';	
								
				if (isset($feeds['names']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_names }, "feed_names");';
					echo '</script>';
				}							
				if (isset($feeds['cites']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_cites }, "feed_cites");';
					echo '</script>';
				}							
				if (isset($feeds['citedby']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_citedby }, "feed_citedby");';
					echo '</script>';
				}							
									
						
				break;
		
			case 'tn:TaxonName':
				echo '<script>render(template_taxon_name, { item: data }, "output");</script>';			
				break;
				
			case 'tp:Person':
			case 'Person':
				echo '<script>render(template_person, { item: data }, "output");</script>';
				
				if (isset($feeds['works']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_works }, "feed_works");';
					echo '</script>';
				}							
				break;

			case 'Periodical':
				echo '<script>render(template_container, { item: data }, "output");</script>';	
				
				if (isset($feeds['works']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_works }, "feed_works");';
					echo '</script>';
				}							
				break;
				
			case 'dwc:Occurrence':
				echo '<script>render(template_occurrence, { item: data }, "output");</script>';			
				break;

            case 'dwc:Taxon':
            case 'tc:TaxonConcept':
			case 'Taxon':
				echo '<script>render(template_taxon, { item: data }, "output");</script>';	
				
				if (isset($feeds['children']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_children }, "feed_children");';
					echo '</script>';
				}							
						
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
    
    
    echo '<!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">';


	echo '<!-- templates -->';
	echo '<script src="js/ejs.js"></script>';
	echo '<script src="taxon_name.js"></script>';
	echo '<script src="work.js"></script>';
	echo '<script src="person.js"></script>';
	echo '<script src="container.js"></script>';
	echo '<script src="occurrence.js"></script>';
	echo '<script src="feed.js"></script>';
	echo '<script src="taxon.js"></script>';
	
	echo '<script>
	function render(template, data, element_id) {

	// Render template 	
	html = ejs.render(template, data);
	
	// Display
	document.getElementById(element_id).innerHTML = html;
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
		
		/*
		a {
			color: black;
			font-weight:bold;
		}
		*/
		
		/* links do not have underlines */
		a:link {
  			text-decoration: none;
		}
		
		a:hover {
  			text-decoration: underline;
		}
		
		/* heading */
		.heading { 
			font-weight:bold; 
			color:black;
		}
		
		
       .tab {
			padding:10px;
			display:block;
			float:left;
			border-right:1px solid rgb(192,192,192);
		}	
		
		.tab-active {
			padding:10px;
			display:block;
			float:left;
			border-right:1px solid rgb(192,192,192);

			background: #6FF;			
		
		}	
		
		/* https://alexcican.com/post/hide-and-show-div/ */
		.hidden>div {
			display:none;
		}

		.visible>div {
			display:block;
		}
		
		.visible>h3::after {
			content: "-"
		}				
		
		.hidden>h3::after {
			content: "+"
		}		
		
		.text_container {
			border:1px solid rgb(222,222,222);
			padding:4px;
			margin-bottom:10px;
			border-radius:8px;
		}		
		
		
	</style>
';

	echo '<script>

	function hasClass(el, className)
	{
		if (el.classList)
			return el.classList.contains(className);
		return !!el.className.match(new RegExp(\'(\\s|^)\' + className + \'(\\s|$)\'));
	}

	function addClass(el, className)
	{
		if (el.classList)
			el.classList.add(className)
		else if (!hasClass(el, className))
			el.className += " " + className;
	}

	function removeClass(el, className)
	{
		if (el.classList)
			el.classList.remove(className)
		else if (hasClass(el, className))
		{
			var reg = new RegExp(\'(\\s|^)\' + className + \'(\\s|$)\');
			el.className = el.className.replace(reg, \' \');
		}
	}
	
	function show_hide(element) {

			if (hasClass(element, "hidden")) {
				removeClass(element, "hidden");
				addClass(element, "visible");
			} else {
				removeClass(element, "visible");
				addClass(element, "hidden");
			}
			
		}
	</script>
	';

	echo '<style type="text/css">
		body { 
			padding:20px; 
			font-family: "Open Sans", sans-serif; 
			line-height:1.5em;
			color: rgb(128,128,128);
		}
		h1 { 
			line-height:1.2em;
			color: black;
		}
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