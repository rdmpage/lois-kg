# CrossRef/CSL to linked data

Using CrossRef API and CouchDB to generate triples for publications

## Export triples

### Works

```
curl http://localhost:32775/oz-csl/_design/work/_list/triples/nt > works.nt
```

### References cited by works (now a separate view)

```
curl http://localhost:32775/oz-csl/_design/references/_list/triples/nt > references.nt
```


## Upload triples

### CrossRef

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@csl.nt'  --progress-bar | tee /dev/null
```

```
curl http://localhost:32771/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@csl.nt'  --progress-bar | tee /dev/null
```

## Clean up

```
CLEAR GRAPH <https://crossref.org>
```