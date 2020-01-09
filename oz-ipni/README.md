# IPNI API

API no longer supports RDF, so we fetch JSON and render it as triples using CouchDB

###

```
curl http://localhost:32769/oz-ipni/_design/authors/_list/triples/nt > authors.nt
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@authors.nt'  --progress-bar | tee /dev/null
```

## BHL Page links

IPNI already has many BHL page links, we can extract these, add them to local database, then dump as annotations.

```
http://localhost:32769/oz-ipni/_design/names/_list/values/bhl_sql
```
