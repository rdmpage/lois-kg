# sameAs

A major design issue is that we will have the same entity represented multiple times. 

##

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@taiwania.nt'  --progress-bar | tee /dev/null

```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@sameas.nt'  --progress-bar | tee /dev/null

```


## Papers

For example, image a paper that has a DOI, but that DOI is not used when another paper cites that paper. We will have one record for the paper (with DOI) and a second record of the same paper (without DOI but with some made-up identifier, for example based on the “key” field in a CrossRef citation).

For example, we have paper http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.6165/tai.2005.50(1).1 which is cited by a Phytokeys paper http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.3897/phytokeys.103.25913 but without using the DOI, so I have generated http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.3897/phytokeys.103.25913%2325913_B22 for this record where “25913_B22” is based on the key used by CrossRef.. Hence our database is unaware that 10.6165/tai.2005.50(1).1 has been cited by 10.3897/phytokeys.103.25913

We could fix this by adding a sameAs statement to the cited reference:
```
<https://doi.org/10.3897/phytokeys.103.25913#25913_B22> <http://schema.org/sameAs> "https://doi.org/10.6165/tai.1976.21.229" .
```
To make use of this we need to modify our queries.

### cited by

For a work (e.g., with a DOI) we get either direct citations of that work (citations linked to the DOI), or citations linked via sameAs:

```
         # Get direct citations, or citations via sameAs
				{
					VALUES ?publication { <' . $work_id . '>} .
					?item schema:citation ?publication .
				}
				UNION
				{
					VALUES ?publication { <' . $work_id . '>} .
				    BIND (STR(?publication) AS ?publication_string) .
				    ?placeholder schema:sameAs ?publication_string .				    
					?item schema:citation ?placeholder .
				}              	

```

The ?placeholder is the #key identifier generated when parsing CrossRef data.

### cites

Cites is slightly tricker in that we want to avoid listing the same reference twice (the one with the #key identifier and the one with the DOI).

```
             	{
									?publication schema:citation ?item .
              		MINUS 
              		{ 
              			?item schema:sameAs ?itemstring 
              		}
              	}
              	UNION
              	{
              		?publication schema:citation ?placeholder .
              		?placeholder schema:sameAs ?itemstring .
              		BIND(IRI(?itemstring) AS ?item)
              	} 

```

Here we list all direct citations but exclude those that have a sameAs link, they get added by the other part of the UNION statement.

## Lists of works

Need to think how we apply this to lists of works in a journal, etc.

## People

So the question is whether we can apply this approach to other entities, such as people. For example, have standardised strings as people identifiers, then when we cluster those use sameAs statements to link to definitive version.

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org/sameas -H 'Content-Type: text/rdf+n3' --data-binary '@creator-ipni.nt'  --progress-bar | tee /dev/null

```


## Finding works that need to be linked to identifiers

Find articles in journal with ISSN 0366-4457 that lack identifier (e.g., a DOI). These would need to be matched to local database/CrossRef/etc and an identifier added via sameAs.

```
PREFIX schema: <http://schema.org/>
SELECT * WHERE
{
  ?work schema:isPartOf <http://worldcat.org/issn/0366-4457> .
  ?work schema:name ?name .
  
  MINUS {
  ?work schema:identifier ?identifier .
  }
  
  #?work schema:volumeNumber "1927" .
  #?work schema:pageStart "203" .
 }
order by ?name
```

## Finding cited works that are unstructured (no name)

```
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX schema: <http://schema.org/>

SELECT *
WHERE
{
  VALUES ?work { <https://doi.org/10.1007/s12225-010-9180-9> }
         
  ?work schema:citation ?cites .
  ?cites schema:description ?description .
  
  MINUS {
  ?cites schema:name ?name .
  }  
}

```


