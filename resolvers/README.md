# Resolvers

## Bulk upload of authors from IPNI

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a0.nt'  --progress-bar | tee /dev/null
```

## Bulk upload of name from IPNI

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@n10.nt'  --progress-bar | tee /dev/null
```



## IPNI new API

https://beta.ipni.org/api/1/n/urn:lsid:ipni.org:names:94847-1

