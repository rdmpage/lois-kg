# ORCID to linked data

Using ORCID API and CouchDB to generate triples linking author ORCID to DOI for a work.

## Export triples

```
curl http://167.71.255.145:5984/oz-orcid/_design/orcid/_list/triples/doi-orcid-nt > orcid.nt
```

## Upload triples

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://orcid.org -H 'Content-Type: text/rdf+n3' --data-binary '@orcid.nt'  --progress-bar | tee /dev/null
```