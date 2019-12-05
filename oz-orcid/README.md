# ORCID to linked data

Using ORCID API and CouchDB to generate triples linking author ORCID to DOI for a work, and basic triples about a person.

### Clean up

```
CLEAR GRAPH <https://orcid.org>
```

## Export triples

### Works

```
curl http://localhost:32775/oz-orcid/_design/work/_list/triples/nt > orcid.nt
```

### People


```
curl http://localhost:32775/oz-orcid/_design/people/_list/triples/nt > people.nt
```

## Upload triples

### Works

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://orcid.org -H 'Content-Type: text/rdf+n3' --data-binary '@orcid.nt'  --progress-bar | tee /dev/null
```

### People

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://orcid.org -H 'Content-Type: text/rdf+n3' --data-binary '@people.nt'  --progress-bar | tee /dev/null
```


