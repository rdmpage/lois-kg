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



