# IIIF


## Upload triples

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://archive.org -H 'Content-Type: text/rdf+n3' --data-binary '@archive.nt'  --progress-bar | tee /dev/null
```


