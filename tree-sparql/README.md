# Trees for display

## Tree as texted RSS type feed

```
{
  "@context" : { "@vocab": "http://schema.org/"},
  "@type": "http://schema.org/DataFeed"
}
```

PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix : <http://purl.org/rss/1.0>
prefix schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

CONSTRUCT
{
  ?root
	rdf:type schema:DataFeed;
	schema:name ?root_name;
    schema:thumbnailUrl ?root_thumbnailUrl;
    
    
	schema:dataFeedElement ?level_one .

	?level_one rdf:type schema:DataFeedItem .
    ?level_one rdf:type schema:ItemList .
    ?level_one schema:name ?level_one_name .
  
    ?level_one schema:itemListElement ?level_two .
    ?level_one <http://schema.org/thumbnailUrl> ?level_one_thumbnailUrl .

 	?level_two rdf:type schema:DataFeedItem .
    ?level_two schema:name ?level_two_name .
    ?level_two <http://schema.org/thumbnailUrl> ?level_two_thumbnailUrl .
 
}


WHERE 
{
VALUES ?root_name {"BOMBYLIIDAE"}
       ?root rdf:type <http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept> .
?root <http://schema.org/name> ?root_name .
  
	OPTIONAL 
	{
      ?root <http://schema.org/image> ?root_image .
      ?root_image <http://schema.org/thumbnailUrl> ?root_thumbnailUrl .
     }  
  
  
OPTIONAL
{
	?level_one rdfs:subClassOf ?root .
	?level_one <http://schema.org/name> ?level_one_name .
  
	OPTIONAL 
	{
      ?level_one <http://schema.org/image> ?level_one_image .
      ?level_one_image <http://schema.org/thumbnailUrl> ?level_one_thumbnailUrl .
     }  
	
	OPTIONAL 
	{
		?level_two rdfs:subClassOf ?level_one .
		?level_two <http://schema.org/name> ?level_two_name .
      
      OPTIONAL 
	{
      ?level_two <http://schema.org/image> ?level_two_image .
      ?level_two_image <http://schema.org/thumbnailUrl> ?level_two_thumbnailUrl .
     }
	
	}
 

}
}