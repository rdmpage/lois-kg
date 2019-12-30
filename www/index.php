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
	$feed_basionym = '';
	$feed_taxa = '';
	$feed_related = '';
	
	$feeds = array();
	
	
	//------------------------------------------------------------------------------------
	if ($ok)
	{
		// Get one or more streams of related content for this entity
		
		//--------------------------------------------------------------------------------
		if (in_array('tn:TaxonName', $types))
		{
			$taxon_name_id  = $entity->{'@graph'}[0]->{'@id'};
		
			// list of names that share basionym with this name
			$stream_query = 'PREFIX schema: <http://schema.org/>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Other names";
				schema:dataFeedElement ?item .
	
				?item 
					rdf:type schema:DataFeedItem;
					rdf:type ?item_type;
					schema:name ?name;
					schema:description ?description;
										
			}
			WHERE
			{
              VALUES ?this {  <' . $taxon_name_id . '> } .
              
              # this has a basionym
              {
                ?this tn:hasBasionym ?item .
              }
              # this is a basionym 
              UNION
              {
              	?item tn:hasBasionym ?this .	
              }
              # other names that share a basionym
              UNION
              {
              	?this tn:hasBasionym ?b .
                ?item tn:hasBasionym ?b .
                # exclude IPNI bugs
                FILTER (?b != <urn:lsid:ipni.org:names:0-0>)
                
              }
              # name has been replaced
              UNION
              {
                ?annotation tn:objectTaxonName ?this .
                ?annotation tn:noteType tn:replacementNameFor .
                ?item tn:hasAnnotation ?annotation .
              }
              
			 ?item dc:title ?name .
			 ?item rdf:type ?item_type .
			
			 OPTIONAL
			 {
				?item tcom:publishedIn ?description .
			 }		  
              
             # ensure no returning self
             FILTER (?item != ?this)
			}';
			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['basionym'] = $feed;
			} 		
			
			
			// taxa with this name 
			$stream_query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Taxa with this name";
				schema:dataFeedElement ?item .
	
				?item rdf:type schema:DataFeedItem .
              
                ?item schema:name ?name .
              	?item schema:description ?description .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
              	?item schema:scientificName <' . $taxon_name_id . '> .
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
				$feeds['taxa'] = $feed;
			} 
			
			// occurrences for this name (via taxon) (e.g., looking for types) 
			$stream_query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX dwc: <http://rs.tdwg.org/dwc/terms/>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Occurrences for taxa with this name";
				schema:dataFeedElement ?item .
	
				?item rdf:type schema:DataFeedItem .
              
                ?item schema:name ?name .
              	?item schema:description ?description .
                ?item rdf:type ?type .
 					
			}
			WHERE
{
   # name
   ?taxon schema:scientificName <' . $taxon_name_id . '> .
  
   # taxon for name
   ?taxon schema:identifier ?identifier .   
   ?identifier schema:propertyID "https://www.wikidata.org/wiki/Property:P846" .
   ?identifier schema:value ?value .
  
   # taxon for occurrence
   ?occurrence_taxon_identifier schema:value ?value .
   ?occurrence_taxon schema:identifier ?occurrence_taxon_identifier .
   
   # occurrences
   ?item dwc:taxonID ?occurrence_taxon .
   ?item schema:name ?name .
   ?item rdf:type ?type .
}
 			';
 			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['occurrence'] = $feed;
			} 				
				
			/*	
			// annotations for this name
			// to do: this needs to be improved as we need to handle multile data types,
			// links to names, text, etc.
			$stream_query = 'PREFIX schema: <http://schema.org/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
			CONSTRUCT 
			{
			<http://example.rss>
				rdf:type schema:DataFeed;
				schema:name "Annotations";
				schema:dataFeedElement ?item .
	
				?item rdf:type schema:DataFeedItem .
              
                ?item schema:name ?name .
              	?item schema:description ?description .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
				<' .  $taxon_name_id . '> tn:hasAnnotation ?item
				{	
					?item <http://rs.tdwg.org/ontology/voc/TaxonName#objectTaxonName> ?objectTaxonName .	
					?objectTaxonName <http://purl.org/dc/elements/1.1/title> ?name  .  	
				}
				UNION {	
					?item <http://rs.tdwg.org/ontology/voc/TaxonName#note> ?name .		
				}			
			}
 			';
 			
			$json = sparql_construct_stream(
				$config['sparql_endpoint'],
				$stream_query);
				
			$feed = json_decode($json);
			if (isset($feed->{'@graph'}) && count($feed->{'@graph'}) > 0)
			{
				$feeds['annotation'] = $feed;
			} 	
			*/						
				
		}		
		
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
                    schema:thumbnailUrl ?thumbnailUrl;
					
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
              		?item schema:thumbnailUrl ?thumbnailUrl .
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
                ?item tcom:publishedInCitation ?publishedInCitation .
                ?publishedInCitation schema:sameAs ?publication .
              
                ?item schema:name ?name .
                ?item rdf:type ?type .
 					
			}
			WHERE
			{
              	VALUES ?publication { <' . $work_id . '>} .
               	BIND(STR(?publication) AS ?pub_identifier )
              	?pub schema:sameAs ?pub_identifier .
              	?item tcom:publishedInCitation ?pub .
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
 			
 			if (1)
 			{
				// list of cited works
				
				// for some reason combining the two queries using UNION is very slow,
				// so split into two parts and merge results
				
				// part one
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
				
				
					#{
					#	?publication schema:citation ?item .
					#	MINUS 
					#	{ 
					#		?item schema:sameAs ?itemstring 
					#	}
					#}
					#UNION
					{
						?publication schema:citation ?placeholder .
						?placeholder schema:sameAs ?itemstring .
						BIND(IRI(?itemstring) AS ?item)
					} 
				
				
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
				
				$feed1 = json_decode($json);
			
				/*
				// part two
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
								
					{
						?publication schema:citation ?item .
						MINUS 
						{ 
							?item schema:sameAs ?itemstring 
						}
					}
					#UNION
					#{
					#	?publication schema:citation ?placeholder .
					#	?placeholder schema:sameAs ?itemstring .
					#	BIND(IRI(?itemstring) AS ?item)
					#} 
				
				
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
				
				$feed2 = json_decode($json);
				
				//print_r($feed2);
				
				if (isset($feed2->{'@graph'}) && count($feed2->{'@graph'}) > 0)
				{
					if (count($feed1->{'@graph'}) == 0)
					{
						$feed1 = $feed2;
					}
					else
					{
						foreach ($feed2->{'@graph'}[0]->dataFeedElement as $item)
						{
							$feed1->{'@graph'}[0]->dataFeedElement[] = $item;
						}
					}
				
				}
					
				*/
				
				if (isset($feed1->{'@graph'}) && count($feed1->{'@graph'}) > 0)
				{
					$feeds['cites'] = $feed1;
				}
				
							
				
				
			}
			if (1)
			{
			
				// list of citing works
				
			
				// for some reason combining the two queries using UNION is very slow,
				// so split into two parts and merge results
				
				// part one					
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
					# Get direct citations, or citations via sameAs
					#{
					#	VALUES ?publication { <' . $work_id . '>} .
					#	?item schema:citation ?publication .
					#}
					#UNION
					{
						VALUES ?publication { <' . $work_id . '>} .
						BIND (STR(?publication) AS ?publication_string) .
						?placeholder schema:sameAs ?publication_string .				    
						?item schema:citation ?placeholder .
					}              	
				
					#?item schema:citation ?publication .
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
				
				$feed1 = json_decode($json);
				
				/*
				// part two					
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
					# Get direct citations, or citations via sameAs
					{
						VALUES ?publication { <' . $work_id . '>} .
						?item schema:citation ?publication .
					}
					#UNION
					#{
					#	VALUES ?publication { <' . $work_id . '>} .
					#	BIND (STR(?publication) AS ?publication_string) .
					#	?placeholder schema:sameAs ?publication_string .				    
					#	?item schema:citation ?placeholder .
					#}              	
				
					#?item schema:citation ?publication .
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
				
				$feed2 = json_decode($json);				
			
				if (isset($feed2->{'@graph'}) && count($feed2->{'@graph'}) > 0)
				{
					if (count($feed1->{'@graph'}) == 0)
					{
						$feed1 = $feed2;
					}
					else
					{
						foreach ($feed2->{'@graph'}[0]->dataFeedElement as $item)
						{
							$feed1->{'@graph'}[0]->dataFeedElement[] = $item;
						}
					}
				
				}	
				*/		
			
				if (isset($feed1->{'@graph'}) && count($feed1->{'@graph'}) > 0)
				{
					$feeds['citedby'] = $feed1;
				}			
			}
			
			
			if (0)
			{
			
				// "related" works, e.g. co-citations
				
				// only works on simple, direct citations
				$stream_query = 'PREFIX schema: <http://schema.org/>
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
  VALUES ?hit {<' . $work_id . '>}

	?x schema:citation ?hit .
	?x schema:citation ?item .

	?y schema:citation ?hit .
	?y schema:citation ?item .

  
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

			// handle sameAs as well
			$stream_query = 'PREFIX schema: <http://schema.org/>
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
  VALUES ?hit {<' . $work_id . '>}
	BIND (STR(?hit) AS ?hit_string) .

    {
		?x schema:citation ?hit .
    }
  	UNION
    {
		?x_placeholder schema:sameAs ?hit_string .				    
		?x schema:citation ?x_placeholder .    
    }
  
    {
		?x schema:citation ?item .
    }
  	UNION
    {
        ?x schema:citation ?xitem_placeholder .
      	?xitem_placeholder schema:sameAs ?item_string .
    }
 
    {
		?y schema:citation ?hit .
    }
  	UNION
    {
 		?y_placeholder schema:sameAs ?hit_string .				    
		?y schema:citation ?y_placeholder .    
	}

    {
		?y schema:citation ?item .
    }
  	UNION
    {
       ?y schema:citation ?yitem_placeholder .
       ?yitem_placeholder schema:sameAs ?item_string .
    }   
	
	BIND(IRI(?item_string) AS ?item)
  
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
			
				$json = sparql_construct_stream(
					$config['sparql_endpoint'],
					$stream_query);
				
				$feed = json_decode($json);
		
			
				if (isset($feed1->{'@graph'}) && count($feed1->{'@graph'}) > 0)
				{
					$feeds['related'] = $feed;
				}			
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
 	
 	/*
 	
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
 	
 	echo '<li><a href="?uri=http://www.wikidata.org/entity/Q5811470">Ditassa (taxon)</a></li>';

 	echo '<li><a href="?q=10.2307/25065588">10.2307/25065588 (search)</a></li>';
 
 	echo '</ul>';
 	
 	
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
 		echo '<div id="feed_related"></div>';
		echo '<div id="feed_children"></div>';
		echo '<div id="feed_taxa"></div>';
		echo '<div id="feed_basionym"></div>';
		echo '<div id="feed_occurrence"></div>';
		echo '<div id="feed_annotation"></div>';
		
		
		
		echo '<div class="text_container hidden" onclick="show_hide(this)">';		
		echo '<h3>JSON-LD</h3>';
		echo '<div style="font-family:monospace;white-space:pre;line-height:1em;">';
		echo json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		echo '</div>';		
		echo '</div>';
		
		if (0)
		{
			echo '<h3>Network</h3>';
			echo '<div id="mynetwork"></div>';
			echo '</div>';
		}
	
	

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
				if (isset($feeds['related']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_related }, "feed_related");';
					echo '</script>';
				}							
									
						
				break;
		
			case 'tn:TaxonName':
				echo '<script>render(template_taxon_name, { item: data }, "output");</script>';	
				
				if (isset($feeds['basionym']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_basionym }, "feed_basionym");';
					echo '</script>';
				}	
				
				if (isset($feeds['taxa']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_taxa }, "feed_taxa");';
					echo '</script>';
				}							

				if (isset($feeds['occurrence']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_occurrence }, "feed_occurrence");';
					echo '</script>';
				}		
				
				if (isset($feeds['annotation']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_annotation }, "feed_annotation");';
					echo '</script>';
				}							
									
										
						
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
					echo 'render(template_decade_feed, { item: feed_works }, "feed_works");';
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
	
	// network?
	if (0)
	{
		echo '<script> show_network("mynetwork", "' . $uri . '"); </script>';
	}
	
	
	display_html_end();	
}

//----------------------------------------------------------------------------------------
function display_entity_ajax($uri)
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
		
		$ok = true;
	}	
	
	//------------------------------------------------------------------------------------
	if ($ok)
	{
		// Get one or more streams of related content for this entity
		
		//--------------------------------------------------------------------------------
		if (in_array('tn:TaxonName', $types))
		{
			$taxon_name_id  = $entity->{'@graph'}[0]->{'@id'};
			
			// basionym
			
			// taxa with this name 
			
			// occurrences for this name (via taxon) (e.g., looking for types) 

		}		
		
		//--------------------------------------------------------------------------------
		if (in_array('Person', $types))
		{
			$identifier_value = '';
			
			if (preg_match('/https?:\/\/orcid.org\/(?<id>.*)/', $entity->{'@graph'}[0]->{'@id'}, $m))
			{
				$identifier_value = $m['id'];
			}
		
		
			// list of works
			
		}
		
		//--------------------------------------------------------------------------------
		// Container
		if (in_array('Periodical', $types))
		{
			$container_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of works
			
		}	
		
		//--------------------------------------------------------------------------------
		// Work
		if (in_array('ScholarlyArticle', $types) || in_array('CreativeWork', $types))
		{
			$work_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of names
		
			// list of cited works
			// list of citing works
			// "related" works, e.g. co-citations
			
			
			

			
		}				
		
		//--------------------------------------------------------------------------------
		// taxon
		if (in_array('Taxon', $types) || in_array('dwc:Taxon', $types))
		{
			$taxon_id  = $entity->{'@graph'}[0]->{'@id'};
				
			// list of child taxa
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
 	
 	
	echo '<div id="output">Stuff goes here</div>';
	
	echo '<div id="feed_names"></div>';
	echo '<div id="feed_works"></div>';
	echo '<div id="feed_cites"></div>';
	echo '<div id="feed_cited_by"></div>';
	echo '<div id="feed_related"></div>';
	echo '<div id="feed_children"></div>';
	echo '<div id="feed_taxa"></div>';
	echo '<div id="feed_basionym"></div>';
	echo '<div id="feed_occurrence"></div>';
	echo '<div id="feed_annotation"></div>';
	
	
	echo '<details>';		
	echo '<summary>JSON-LD</summary>';
	echo '<div style="font-family:monospace;white-space:pre;line-height:1em;">';
	echo json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo '</div>';		
	echo '</details>';
	
	$feeds = array();
	
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
				
				echo '<script>work_names("' . $uri . '");</script>';
				echo '<script>work_cites("' . $uri . '");</script>';
				echo '<script>work_cited_by("' . $uri . '");</script>';
				echo '<script>work_related("' . $uri . '");</script>';
				break;
		
			case 'tn:TaxonName':
				echo '<script>render(template_taxon_name, { item: data }, "output");</script>';	
				
				echo '<script>name_basionym("' . $uri . '");</script>';
				echo '<script>name_annotation("' . $uri . '");</script>';
				
				/*
				if (isset($feeds['basionym']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_basionym }, "feed_basionym");';
					echo '</script>';
				}	
				
				if (isset($feeds['taxa']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_taxa }, "feed_taxa");';
					echo '</script>';
				}							

				if (isset($feeds['occurrence']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_occurrence }, "feed_occurrence");';
					echo '</script>';
				}		
				
				if (isset($feeds['annotation']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_annotation }, "feed_annotation");';
					echo '</script>';
				}							
				*/											
						
				break;
				
			case 'foaf:Person':
			case 'tp:Person':
			case 'Person':
				echo '<script>render(template_person, { item: data }, "output");</script>';				
				
				echo '<script>person_names("' . $uri . '");</script>';
								
				echo '<script>person_works("' . $uri . '");</script>';
				break;

			case 'Periodical':
				echo '<script>render(template_container, { item: data }, "output");</script>';			
				
				echo '<script>container_works("' . $uri . '");</script>';
				break;
				
			case 'dwc:Occurrence':
				echo '<script>render(template_occurrence, { item: data }, "output");</script>';			
				break;

            case 'dwc:Taxon':
            case 'tc:TaxonConcept':
			case 'Taxon':
				echo '<script>render(template_taxon, { item: data }, "output");</script>';	
				
				/*
				if (isset($feeds['children']))
				{				
					echo '<script>';
					echo 'render(template_datafeed, { item: feed_children }, "feed_children");';
					echo '</script>';
				}							
				*/
						
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


	echo '<script src="js/jquery-1.11.2.min.js"></script>';

	echo '<!-- templates -->';
	echo '<script src="js/ejs.js"></script>';
	
	echo '<script src="views/partials/utils.ejs"></script>';
	echo '<script src="views/taxon_name.ejs"></script>';
	echo '<script src="views/work.ejs"></script>';
	echo '<script src="views/person.ejs"></script>';	
	echo '<script src="views/container.ejs"></script>';
	
	
	echo '<script src="views/feed.ejs"></script>';
	echo '<script src="views/feed_search.ejs"></script>';
	echo '<script src="views/feed_tags.ejs"></script>';
	
	echo '<script src="views/decade_feed.ejs"></script>';
	
	// to do:
	echo '<script src="occurrence.js"></script>';
	echo '<script src="taxon.js"></script>';
	
	
	echo '<script>
	function render(template, data, element_id) {

	// Render template 	
	html = ejs.render(template, data);
	
	// Display
	document.getElementById(element_id).innerHTML = html;
}
</script>';


	echo '<!-- API functions to add content -->' . "\n";
	echo '<script src="enhance-content.js"></script>';



  //echo '<script type="text/javascript" src="vis-network.min.js"></script>';
  //echo '<script type="text/javascript" src="network.js"></script>';

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
		
		.visible>h3::before {
			content: "▼ "
		}				
		
		.hidden>h3::before {
			content: "▶ "
		}		
		
		.text_container {
			border:1px solid rgb(222,222,222);
			padding:4px;
			margin-bottom:10px;
			border-radius:8px;
		}		
		
		/* links */
.external:after {
  /*content: "\f35d";
  font-family: "Font Awesome 5 Free";
  font-weight: normal;
  font-style: normal; 
  display: inline-block;
  text-decoration: none; */
  
  content: url("images/external.png");
  vertical-align: middle;

  
  padding-left: 3px;
}

/* network */
    #mynetwork {
      width: 600px;
      height: 400px;
      border: 1px solid lightgray;
    }

/* see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/details */
details {
    border: 1px solid #aaa;
    border-radius: 4px;
    padding: .5em .5em 0;
    margin-bottom: .5em;
    
    background-color:white;
}

summary {
    font-weight: bold;
    margin: -.5em -.5em 0;
    padding: .5em;
}

details[open] summary {
    border-bottom: 1px solid #aaa;
}



	details style="border: 1px solid #aaa;padding: .5em;border-radius: 4px;">

		
		
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
	
	echo '<script>
	
 		// http://stackoverflow.com/a/11407464
		document.addEventListener("keypress", function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == "13"){
				document.getElementById("search_button").click();   
			}
		});    
	
	
	function search() {
		var query = document.getElementById("search").value;  
		//alert(query);
		window.location = "?q=" + query;
	}
	
	</script>';

	echo '<style type="text/css">
		body { 
			padding:20px; 
			font-family: "Open Sans", sans-serif; 
			line-height:1.5em;
			/* color: rgb(128,128,128); */
			background-color:#eee;
		}
		h1 { 
			line-height:1.2em;
			color: black;
		}
		
	/* based on https://stackoverflow.com/a/43936462/9684 */
	.search_form {
	  /* This bit sets up the horizontal layout */
	  display:flex;
	  flex-direction:row;
  
	  /* This bit draws the box around it */
	  border:1px solid rgb(192,192,192); 
	  background-color:white;
	  padding:2px;
	}

	.search_input {
	  /* Tell the input to use all the available space */
	  flex-grow:2;
	  /* And hide the input\'s outline, so the form looks like the outline */
	  border:none;
	  
	  font-size:18px;
	}

	.search_button {
	  /* Just a little styling to make it pretty */
	  border:1px solid rgb(192,192,192);
	  background:rgb(64,64,64);
	  color:white;
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
	
	echo '<div class="search_form">
  <input id="search" class="search_input" value=""/>
  <button id="search_button" class="search_button" onclick="search()">Search</button>
</div>';
	
	
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
// Search
function display_search($query)
{
	global $config;
	
	$title = $config['site_name'];
	$meta = '';
	$script = '';
	
	$feed = '';
		
	// Use a generic CONSTRUCT to get information on this entity
	$json = sparql_search($config['sparql_endpoint'], $query);

	if ($json != '')
	{
		$feed = json_decode($json);
	}

	// JSON-LD for structured data in HTML
	$script = "\n" . '<script type="application/ld+json">' . "\n"
		. 	json_encode($feed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    	. "\n" . '</script>';
    	
    $script .= "\n" . '<script>' . "\n"
		. 'var feed_search=' . json_encode($feed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';'
    	. "\n" . '</script>';
        
    	
 	display_html_start($title, $meta, $script);	
 	
 	echo '<div id="feed"></feed>';
	
	echo '<script>';
	echo 'render(template_searchfeed, { item: feed_search }, "feed");';
	echo '</script>';
	
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
						
		display_entity_ajax($uri);
		exit(0);
	}
		
	
	// Show search
	if (isset($_GET['q']))
	{	
		$query = $_GET['q'];
		display_search($query);
		exit(0);
	}
	
}


main();

?>