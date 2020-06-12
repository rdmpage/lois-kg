# Taxon queries

## People working on a taxon

Given a root, e.g. genus, get subtaxa and list authors or papers

```
prefix dc: <http://purl.org/dc/elements/1.1/>
prefix schema: <http://schema.org/>
prefix tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT *
WHERE
{
VALUES ?root_name {"Haniffia"}
 ?root schema:name ?root_name .
 ?child schema:parentTaxon+ ?root .
 #?child schema:parentTaxon ?parent .
 ?child schema:name ?child_name .
 #?parent schema:name ?parent_name .
  
  # scientific name
  OPTIONAL {
    ?child schema:scientificName ?scientificName .
     ?scientificName tcom:publishedIn ?publishedIn .
   
    # do we have a publication
    OPTIONAL {
      ?scientificName tcom:publishedInCitation ?publishedInCitation .
      ?scientificName tcom:publishedInCitation ?publishedInCitation .
      ?publishedInCitation schema:sameAs ?work .
      ?work schema:name ?name .
      
      # people
      OPTIONAL {
        ?work schema:creator ?role .
        ?role schema:creator ?creator .
        {
        	?creator schema:sameAs ?person .
        	?person dc:title|schema:name ?person_name .
        }
        #UNION
        #{
        #  ?creator schema:name ?person_name .
        #}
        
      }
      
      # images
    }
  }
}
```

## Taxa in a work

Given a work list taxa and associated trees

```
prefix schema: <http://schema.org/>
prefix tcom: <http://rs.tdwg.org/ontology/voc/Common#>


SELECT *
WHERE
{
  VALUES ?work { <https://doi.org/10.3897/phytokeys.1.658> }
         
  # names in publication
  ?publishedInCitation schema:sameAs ?work .
  ?scientificName tcom:publishedInCitation ?publishedInCitation .
 
  # taxa
  ?taxon schema:scientificName ?scientificName .
  ?taxon schema:name ?taxon_name .
  
  # tree
  ?taxon schema:parentTaxon+ ?node .
  ?node schema:name ?node_name .
  OPTIONAL { 
    ?node schema:parentTaxon ?parent .
    ?parent schema:name ?parent_name
  }    
  
  
  
}
```
