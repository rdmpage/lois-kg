# Thumbnails

We need thumbnails for many (all?) entities.

## Cleaning up

If we make a mistake, this will delete thumbnails for a given journal:

```
PREFIX schema: <http://schema.org/>
WITH <https://crossref.org>
DELETE { ?item schema:thumbnailUrl ?thumbnailUrl }
WHERE 
{ 
 ?item schema:isPartOf <http://worldcat.org/issn/2304-7534> .
 ?item schema:thumbnailUrl ?thumbnailUrl . 
}
```


## Works


Internet Archive in microcitations

```
SELECT CONCAT('<https://doi.org/', LOWER(doi), '> <http://schema.org/thumbnailUrl> "https://archive.org/services/img/', internetarchive, '" .') FROM publications WHERE issn='0006-5196' AND doi IS NOT NULL AND internetarchive IS NOT null;
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@thumbnails.nt'  --progress-bar | tee /dev/null
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@jstor.nt'  --progress-bar | tee /dev/null
```

### Internet Archive 

Works in Internet Archive can get a thumbnail via
```https://archive.org/services/img/ia-identifier-goes-here```

This will include BioStor articles that have been added to IA, e.g. https://archive.org/services/img/biostor-217672, and JSTOR early content, e.g. https://archive.org/services/img/jstor-3238237 which will show the JSTOR-inserted page :(

To get thumbnail of specific page by 0-based index:

```https://archive.org/download/jstor-3238237/page/n1_thumb.jpg```

Page n1 is the 2nd page in the item, which in this case skips over the JSTOR inserted first page.

### To show page in context of IA item

See https://openlibrary.org/dev/docs/bookurls for details, 

### Pubmed Central (Internet Archive)

For example, Phytokeys is in PMC and in IA (not sure how much of the journal is there). https://archive.org/details/pubmed-PMC3391712, thumbnail is https://archive.org/download/pubmed-PMC3391712/page/n0_thumb.jpg

Specific page (third) https://ia802205.us.archive.org/BookReader/BookReaderImages.php?id=pubmed-PMC3391712&itemPath=%2F10%2Fitems%2Fpubmed-PMC3391712&server=ia802205.us.archive.org&page=n2_thumb.jpg

(Can we use this as a way to show individual species descriptions?) 

### BHL

https://www.biodiversitylibrary.org/pagethumb/page-id-goes-here,100,100

### BioNames

### JSTOR

Thumbnails for works with DOI

```
SELECT DISTINCT CONCAT(REPLACE(doi, '10.2307/', ''), ',') FROM names WHERE issn='1055-3177' and doi LIKE '10.2307/%';
```


## People

## Taxa

## Containers







