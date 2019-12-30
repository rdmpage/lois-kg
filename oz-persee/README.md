# Persée

Persée has a SPARQL endpoint http://data.persee.fr/explore/sparql-endpoint/?lang=en, with the default graph URI http://data.persee.fr. The RDF is rich and detailed, using mixture of vocabularies, but does not record order of authorship. To get this we need to scrape the HTML.

## People

Can fetch people directly as RDF+XML, then convert to triples and import into triple store (```php fetch.php```).

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=http://data.persee.fr -H 'Content-Type: text/rdf+n3' --data-binary '@persee.nt'  --progress-bar | tee /dev/null
```

The identifier is of the form ```http://data.persee.fr/authority/243889#Person```.

## Works

If we fetch a work (e.g., via it’s DOI) we can then parse the HTML to extract links to authors in order. We then can export these as simple triples to link creators to Persee id.

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=http://data.persee.fr -H 'Content-Type: text/rdf+n3' --data-binary '@perseework.nt'  --progress-bar | tee /dev/null
```






