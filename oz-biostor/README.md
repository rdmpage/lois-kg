# BioStor

BioStor references are imported into CSL store and triples are generated as for other sources, with the exception of annotations.

## BioStor and BHL

The IIIF model used to display BHL items includes taxon names as annotations, that is, each page may have one or more annotations.

We can apply the same approach to BioStor “parts”, where each page in a BioStor article is linked to the article via an annotation. 

We could also model parts as IIIF “ranges” which can specify lists of pages corresponding to articles. 

## External ids

How do we handle external, such as DOIs, do we make them the default id?

## Creator ids

Can we add BHL creator ids to BioStor records, so we can (eventually) link to Wikidata?

