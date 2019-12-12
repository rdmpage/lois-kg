# IPNI API

API no longer supports RDF, so we fetch JSON and render it as triples using CouchDB

###

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@authors.nt'  --progress-bar | tee /dev/null
```