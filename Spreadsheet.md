# Spreadsheet-style results

## Mimic my IPNI app

For a genus we list all names, publications, DOIs, and BHL pages.

```
prefix schema: <http://schema.org/>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
prefix oa: <http://www.w3.org/ns/oa#>
select ?name ?nameComplete ?year ?publishedIn ?doi ?bhl
where
{
 VALUES ?genusName { "Malleastrum" } .
  
 ?name tn:genusPart ?genusName .
  
 ?name tn:nameComplete ?nameComplete . 
  OPTIONAL {
 ?name tn:year ?year . 
  }
  
 OPTIONAL {
   ?name tcom:publishedIn ?publishedIn . 
 }
  
  # Do we have a publication?
 OPTIONAL {
   ?name tcom:publishedInCitation  ?publishedInCitation  . 
   ?publishedInCitation schema:sameAs ?work .
   
 
   
   OPTIONAL {
     ?work schema:identifier ?identifier .
     ?identifier schema:propertyID "doi" .
     ?identifier schema:value ?doi .
 }  

 }
  
  # Do we have a link to BHL?
   OPTIONAL {
  	?annotation oa:hasBody ?name .
  	?annotation oa:hasTarget ?target .
    BIND (REPLACE(STR(?target), "https://www.biodiversitylibrary.org/page/","") AS ?bhl)
   }
  
  
  
}
```

