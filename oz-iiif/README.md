# IIIF

For scanned items in Internet Archive, such as BHL content, we create a IIIF manifest for the corresponding IA item, adding BHL page identifiers to each page (if available) as dcterms:relation. To render the item we retrieve the manifest by a SPARQL CONSTRUCT query, which is converted to the JSON-LD representation expected by an IIIF viewer.

## IA problems

- some scans lack scandata.xml files (may be zipped) and/or BHL METs.
- image size in IA scandata.xml can’t always be trusted, may have to manually recompute some by grabbing page images (sigh), e.g. see http://localhost/~rpage/lois-kg/www/iiif-viewer.php?uri=https://archive.org/details/annalsmagazineof861910lond/manifest.json&locator=https://www.biodiversitylibrary.org/page/15628854 (for the moment I set image height as well to force image to fit).

 

## SPARQL construct

## Linking taxonomic names to BHL

Names are linked to BHL pages as W3C annotations.

## Displaying linked names

In the IIIF viewer, when a page is scrolled into view a SPARQL query retrieves any names annotations linked to that page. It does this by posting a message to the parent window that includes the BHL Page id, which then triggers an API call to a SPARQL query to get any names linked to the BHL page.

## Upload triples

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://archive.org -H 'Content-Type: text/rdf+n3' --data-binary '@iiif.nt'  --progress-bar | tee /dev/null
```

## Queries

### Get name annotations on page

Given a BHL PageID, what names are linked to that page? These will be represented as annotations.

```
prefix schema: <http://schema.org/>
prefix dc: <http://purl.org/dc/elements/1.1/>
prefix dcterm: <http://purl.org/dc/terms/>
prefix oa: <http://www.w3.org/ns/oa#>

select * where 
{
  ?annotation oa:hasTarget <https://www.biodiversitylibrary.org/page/13739402> .
  ?annotation oa:hasBody ?taxonomic_name .
  ?taxonomic_name schema:name | dc:title ?name .
}
```

### Given taxon name published on a BHL page get IIIF manifest

Given that data will be linked to a BHL page, we need to be able to find the corresponding IA item IIIF manifest to display. We can do this via the BHL page id linked to the name and the IIIF manifest’s use of dcterms:relation.

```
prefix dcterm: <http://purl.org/dc/terms/>
prefix oa: <http://www.w3.org/ns/oa#>
prefix sc: <http://iiif.io/api/presentation/2#>

select * where 
{
  ?annotation oa:hasBody <urn:lsid:ipni.org:names:929986-1> .
  ?annotation oa:hasTarget ?target .
  ?canvas dcterm:relation ?target .
  ?sequence sc:hasCanvases ?canvas .
  ?manifest sc:hasSequences ?sequence .
}
```







