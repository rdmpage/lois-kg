# Glue

Code to bind entities together, such as taxonomic names with publications.

### Glue


```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@glue.nt'  --progress-bar | tee /dev/null
```
