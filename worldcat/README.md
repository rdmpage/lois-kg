# Worldcat

### ISSNs


```
cat *.nt > issn.nt
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://www.worldcat.org -H 'Content-Type: text/rdf+n3' --data-binary '@issn.nt'  --progress-bar | tee /dev/null
```


## Find journals with no name

```
select * where 
{ ?s rdf:type <http://schema.org/Periodical> .
  OPTIONAL {
    ?s <http://schema.org/name> ?name .
    }
} LIMIT 10
```

Code in add-missing.php generates names based on alternateName

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://www.worldcat.org -H 'Content-Type: text/rdf+n3' --data-binary '@missing.nt'  --progress-bar | tee /dev/null

```


## ISSN.org

The ISSN portal also has RDF, e.g.

https://portal.issn.org/resource/ISSN/0006-8101?format=json

https://portal.issn.org/resource/ISSN/0006-8101?format=xml

