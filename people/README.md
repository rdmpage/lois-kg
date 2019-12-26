# People

Code and ideas to link people’s names to identifiers

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




