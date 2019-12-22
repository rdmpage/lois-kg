# Annotations

Use W3C annotations to link taxonomic names to literature, such as pages in BHL, locations in XML documents, etc.

## Generate annotations

### IPNI

Simple link between IPNI LSID and BHL PageID

## Upload triples

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org/annotation -H 'Content-Type: text/rdf+n3' --data-binary '@ipni.nt'  --progress-bar | tee /dev/null
```

