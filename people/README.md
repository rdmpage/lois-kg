# People

Code and ideas to link people’s names to identifiers.


## Background

There are two problems to solve. The first is to have an identifier for a person, the second is to be able to assign that identifier to an activity by that person. In other words, we want to assert that the author (“Jane Smith”) of a work is Jane Smith ID:XXXXX. 

Some taxonomic databases have identifiers for authors (e.g., IPNI author LSIDs,  ZooBank author UUIDs). There are also author identifiers provided by aggregators such as WorldCat, ResearchGate, and Google Scholar. ORCID provides people with a way to create their own identifier and claim their publications.  This multiplicity of identifiers 

The approach I’ve adopted is to (a) have a single record for each identifier, e.g. an ORCID id (in schema.org), an IPNI author LSID (as native RDF), etc. (may have Persee RDF, etc.). For a publication, each author is treated essentially as a bnode but with a local identifier so we can refer to it. If, say, a publication record from CrossRef has an ORCID id for an author, this is stored as

DOI —> creator —> sameAs ORCID

If we match another identifier, such as an IPNI id, we also use sameAs. If we match to a record that has an ORCID as well, then we have:

creator
- sameAs ORCID
- sameAs IPNI

We can then do a SPARQL query to fetch all publications for that person by handling sameAs. So we can envisage adding additional identifiers, such as Persee in the same way (think more about this, do we want to add Persee, or query it to get VIAF, etc., do SPARQL query to get sameAs links, maybe use Persee namespace).

Can we also handle BHL/BioStor in the same way? For each BioStor article, get BHL part, add sameAs links for BHL creator, which gives us id and a way to map to other people via Wikidata.


This then raises issue of Wikidata, and do we have sameAs circular links! Or can we include Wikidata as always being the source of sameAs?:

Wikidata
- sameAs ORCID
- sameAs BHL
- sameAs IPNI

This would mean we could map identifiers “locally” then use Wikidata to connect things up (rather than relying on having at least one record that is like this:

creator
- sameAs ORCID
- sameAs IPNI


How does this work for ZooBank? ZooBank UUIDs
How does this work for ION? (BioNames)

How do we handle grouping people’s names for which we don’t have any identifier?

How do we handle ResearchGate?

### Persée

We can use Persée DOIs to retrieve Persée author ids, which in turn are linked to other ids. Persée author ids are also in Wikidata, so could add Persée as one of the sameAs links to associate with Wikidata person records.




## Name matching

Normalise strings, compute longest common subsequence, decide whether names are the same based on size of subsequence.

### Update triple store

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org/sameas -H 'Content-Type: text/rdf+n3' --data-binary '@Middleton.nt'  --progress-bar | tee /dev/null

```

## Matching to IPNI identifiers

### Matching using work as unit

```match_authors_ipni.php```

Take URI for work, SPARQL to get taxonomic names and authors, match and output sameAs link between work creator (typically a bnode) and IPNI author ID.

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org/sameas -H 'Content-Type: text/rdf+n3' --data-binary '@ipni.nt'  --progress-bar | tee /dev/null

```

## Find works by authors by name

Once we are happy that a name string is a person, we can then find all works with that named author and then generate triples to make the match. In other words, we can propagate the id.

```
PREFIX schema: <http://schema.org/>

CONSTRUCT
{
  	# triple linking creator to identifier
    ?person schema:sameAs ?identifier . 
}
WHERE
{ 
  	VALUES ?identifier { "urn:lsid:ipni.org:authors:35229-1" }
  	VALUES ?name { "D. Middleton" }
    
    # person with name
  	?person schema:name ?name .
  	
    # ignore names already matched via sameAs
 	MINUS {
    	?person schema:sameAs ?s
    } 
  
  	?role schema:creator ?person .
}
```

## Names with same familyName (for clique clustering)

```
PREFIX schema: <http://schema.org/>

SELECT DISTINCT ?givenName
WHERE
{ 
  	?person schema:familyName "Middleton" .
    ?person schema:givenName ?givenName .  	
}
```

## D-Dupe

### Try a name to se if might have d-dupe potential

Given a name see get lists of region1 and 5 names, which might suggest pairs for d-dupe.


```
prefix schema: <http://schema.org/>
select distinct ?n where
{
  #VALUES ?name { "Paul J. A. Keßler" }
  #VALUES ?name { "P.J.A. Keßler" }
  #VALUES ?name { "P.J.A. Kessler"}
 VALUES ?name { "Michael Möller"}


  ?person schema:name ?name .
  ?role schema:creator ?person.  
  ?work schema:creator ?role .  
  
  ?work schema:creator ?r .  
  ?r schema:creator ?p. 
  ?p schema:name ?n .
  
  FILTER (?name != ?n)
 }
ORDER BY ?n
```



### D-dupe graph

Generate data for d-dupe graph to help match two names that are similar and potentially the same.


```
prefix schema: <http://schema.org/>
select distinct ?name1 ?name2 ?name3a ?name3b ?name1a ?name2a where
{
 # VALUES ?name1 { "Dirk N Karger" }
  #VALUES ?name2 { "Dirk Nikolaus Karger" }
         
  VALUES ?name1 { "Wen Hong Chen" }
  VALUES ?name2 { "Wen-Hong Chen" }
 
  # region 2
  ?person1 schema:name ?name1 .
  ?role1 schema:creator ?person1.  
  ?work1 schema:creator ?role1 .  
  
  # region 4
  ?person2 schema:name ?name2 .
  ?role2 schema:creator ?person2.  
  ?work2 schema:creator ?role2 .  
  
  # region 3
  ?work1 schema:creator ?role3a .  
  ?role3a schema:creator ?person3a. 
  ?person3a schema:name ?name3a .  
 
   ?work2 schema:creator ?role3b .  
  ?role3b schema:creator ?person3b. 
  ?person3b schema:name ?name3b . 
   
  
  FILTER (?name3a = ?name3b) 
  
  # region 1
  ?work1 schema:creator ?role1a .  
  ?role1a schema:creator ?person1a.  
  ?person1a schema:name ?name1a .
  
  FILTER (?name1 != ?name1a) 
  FILTER (?name1a != ?name3a) 

   # region 5
  ?work2 schema:creator ?role2a .  
  ?role2a schema:creator ?person2a.  
  ?person2a schema:name ?name2a .
  
  FILTER (?name2 != ?name2a) 
  FILTER (?name2a != ?name3b) 
 }
```


## Author co-citation

Using names

```
prefix schema: <http://schema.org/>

select ?y_name (COUNT(?y_name) AS ?c) where 
{
  
  VALUES ?x_name { "Mark Hughes" }
 ?x_creator schema:name ?x_name . 
  ?x_role schema:creator ?x_creator .
  ?x schema:creator ?x_role .
  
  ?x_placeholder schema:sameAs ?x .
  ?a schema:citation ?x_placeholder .
     
  ?a schema:citation ?y_placeholder . 
  ?y_placeholder schema:sameAs ?y .
  
   ?y schema:creator ?y_role .
   ?y_role schema:creator ?y_creator .
  ?y_creator schema:name ?y_name . 
  
  FILTER (?x != ?y)
   FILTER (?x_name != ?y_name)
  
}GROUP BY ?y_name
ORDER BY DESC (?c)
LIMIT 10

```

Using identifiers

```
prefix schema: <http://schema.org/>

select ?y_creator_id ?y_name (COUNT(?y_name) AS ?c) where 
{
  
  VALUES ?x_creator_id { <urn:lsid:ipni.org:authors:20027529-1> }
  ?x_creator schema:sameAs ?x_creator_id .
  ?x_creator schema:name ?x_name . 
  ?x_role schema:creator ?x_creator .
  ?x schema:creator ?x_role .
 
  ?x_placeholder schema:sameAs ?x .
  ?a schema:citation ?x_placeholder .
     
  ?a schema:citation ?y_placeholder . 
  ?y_placeholder schema:sameAs ?y .
  
   ?y schema:creator ?y_role .
   ?y_role schema:creator ?y_creator .
   ?y_creator schema:name ?y_name . 
   ?y_creator schema:sameAs ?y_creator_id .
  
  FILTER (?x != ?y)
   FILTER (?x_creator_id != ?y_creator_id)
}GROUP BY ?y_creator_id ?y_name
ORDER BY DESC (?c)
LIMIT 10
```

### Find authors of works published in a year

```
prefix schema: <http://schema.org/>
select * 
where
{
  ?work schema:datePublished "2010" .
  
  ?work schema:creator ?role .
  ?role schema:creator ?creator .
  ?creator schema:name ?name . 
  
  OPTIONAL {
    
    ?creator schema:sameAs ?name_id . 
  }
  
  
}
limit 1000
```


### Find authors in ZooBank

```
prefix schema: <http://schema.org/>
select * 
where
{
  ?work schema:creator ?role .
  ?role schema:creator ?creator .
  ?creator schema:name ?name . 
  
  ?work schema:identifier ?identifier .
  ?identifier schema:propertyID  "zoobank" .
  
  OPTIONAL {
    
    ?creator schema:sameAs ?name_id . 
  }
  
  
}
order by ?name
```



