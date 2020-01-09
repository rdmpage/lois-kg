# Annotations

Use W3C annotations to link taxonomic names to literature, such as pages in BHL, locations in XML documents, etc.

## Generate annotations

### IPNI

Simple link between IPNI LSID and BHL PageID.

```
php ipni.nt 
```

Will generate triples as annotations. If you have the BHL item in the triple store as IIIF (see ```oz-iiif`) then you should be able to jump between name and BHL page.

### Making BHL links

To make links, see ```bionames-data/ipni/biostor-to-bhl.php``` to extract BHL pages from BioStor and add them to IPNI database.


## Upload triples

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org/annotation -H 'Content-Type: text/rdf+n3' --data-binary '@ipni.nt'  --progress-bar | tee /dev/null
```

