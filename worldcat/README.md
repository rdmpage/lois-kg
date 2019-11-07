# Worldcat

### ISSNs


```
cat *.nt > issn.nt
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://www.worldcat.org -H 'Content-Type: text/rdf+n3' --data-binary '@issn.nt'  --progress-bar | tee /dev/null
```