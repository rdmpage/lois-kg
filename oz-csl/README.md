# CrossRef/CSL to linked data

Using CrossRef API and CouchDB to generate triples for publications

## Export triples

```
curl http://167.71.255.145:5984/oz-csl/_design/csl/_list/triples/nt > csl.nt
```

## Upload triples

### CrossRef

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@csl.nt'  --progress-bar | tee /dev/null
```