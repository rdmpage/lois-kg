# Zenodo

## BLR images

BLR has images extracted from papers, linked to the original paper. Here we use a local CouchDB version of BLR to get just the images linked to an external DOI (I.e., for the paper), retrieve Zenodo JSON-LD, and enhance by adding links to thumbnails and images (which doesn’t Zenodo do this?)


## Upload triples

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://zenodo.org -H 'Content-Type: text/rdf+n3' --data-binary '@zenodo.nt'  --progress-bar | tee /dev/null
```

