# CrossRef/CSL to linked data

Using CrossRef API and CouchDB to generate triples for publications


## Get lists of works to add

### Add via microcitation database

```
select distinct concat("'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=", handle, "',") from names where issn='0030-8870' and handle is not null;
```

## Export triples

### Works

```
curl http://localhost:32775/oz-csl/_design/work/_list/triples/nt > works.nt
```

### References cited by works (now a separate view)

```
curl http://localhost:32775/oz-csl/_design/references/_list/triples/nt > references.nt
```


## Upload triples

Use ```php chunk.php``` to chunk data, then ```./upload.sh```. 
May have to ```xattr -d com.apple.quarantine upload.sh``` if you get "bad interpreter" error message.




## Clean up

```
CLEAR GRAPH <https://crossref.org>
```