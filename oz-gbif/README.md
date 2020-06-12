# GBIF occurrences as linked data

There are two options for modelling GBIF data. One is “flat”, essentially replicating the GBIF record. This is easiest to read, and has the advantage of being similar to the RDF returned by CETAF URIs.

The other is a more complex form following Baskauf, which breaks up the Darwin Core “star” into separate objects. 

For now I use the “flat” approach.

## Export triples

```
curl http://localhost:32769/oz-gbif/_design/ld/_list/triples/nt-flat > gbif.nt
```

```
curl http://localhost:32769/oz-gbif-test/_design/ld/_list/triples/nt-flat > gbif.nt
```


## Upload triples

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://gbif.org -H 'Content-Type: text/rdf+n3' --data-binary '@gbif.nt'  --progress-bar | tee /dev/null
```

## Examples

### Types not labelled

#### Barjonia laxa
https://www.gbif.org/occurrence/1096785885
https://www.gbif.org/occurrence/1096785870

Both have typeStatus in raw but not parsed by GBIF

#### Multiple identifications

Multiple ids in raw, including type status and old names

http://coldb.mnhn.fr/catalognumber/mnhn/p/p04060480 

https://api.gbif.org/v1/occurrence/474926968/fragment

#### Field numbers not in GBIF

Leiden material doesn’t have recordNumber in GBIF, but can be in Leiden metadata FFS!
