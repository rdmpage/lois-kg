# Examples

## Tools

[DOI to ORCID](https://enchanting-bongo.glitch.me)

## IPNI names and ORCIDs

Examples to play with as we develop methods to match names, people, and identifiers.

IPNI | Name | DOI | ORCID | Wikidata | Comments
-- | -- | -- | -- |  --
77186312-1 | Miconia brigitteae | 10.18257/raccefyn.602 | 0000-0002-5685-9338 | Q6445379 | Humberto Mendoza-Cifuentes has ORCID but no public profile, ORCID in CrossRef metadata, Wikidata has IPNI id but no ORCID
77143455-1 | 	Lessingianthus dichrous | 10.3100/025.018.0214 | 0000-0001-7965-9008 | Q21389741 | ORCID not in CrossRef, but DOI in ORCID. Wikidata for author doesn’t have ORCID

### Match people to IPNI and ORCID

Sigrid Liede http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.2307/3392072

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0003-2707-0335


### ORCIDs

```
0000-0002-5685-9338
0000-0001-7965-9008
0000-0001-8109-4544
0000-0002-2465-563X
0000-0003-3340-2252
0000-0002-7887-9954
```

### Wikidata

#### IPNI

```
SELECT * WHERE {
  ?item wdt:P586 ?IPNI_author_ID.
  OPTIONAL { 
    ?item wdt:P496 ?orcid.
  }
  OPTIONAL { 
    ?article 	schema:about ?item ;
    schema:isPartOf <https://species.wikimedia.org/> .
  }
}
```

[Try it](https://w.wiki/8yL) 46725 rows 2019-09-27

#### IPNI and ORCID

```
SELECT * WHERE {
  ?item wdt:P586 ?IPNI_author_ID .
  ?item wdt:P496 ?orcid .
}
```

IPNI with ORCIDs [Try it](https://w.wiki/8yN) 400 rows 2019-09-27

#### IPNI and Wikispecies

```
SELECT * WHERE {
  ?item wdt:P586 ?IPNI_author_ID.
  ?article 	schema:about ?item ;
  schema:isPartOf <https://species.wikimedia.org/> .
}
```

[Try it](https://w.wiki/8yQ) 12223 rows 2019-09-27


#### ZooBank and Wikispecies

```
SELECT * WHERE {
  ?item wdt:P2006 ?zoobank.
  ?article schema:about ?item;
  schema:isPartOf <https://species.wikimedia.org/>.
}
```

https://w.wiki/8yT 15658 rows 2019-09-27

#### Zoobank only

```
SELECT * WHERE {
  ?item wdt:P2006 ?zoobank.
}
```
15658 rows 2019-09-27 (same as Wikispecies)

#### Zoobank and ORCID

```
SELECT * WHERE {
  ?item wdt:P2006 ?zoobank.
  ?item wdt:P496 ?orcid .
}
```

https://w.wiki/8yW 1138 rows 2019-09-27



### Taxa to look at 

_Lessingianthus_ http://localhost/~rpage/ipni-names/index.php?q=Lessingianthus. 

## Examples of GBIF having messy data due to synonyms. etc.

http://ispecies.org/?q=Astephanus

Note sparse records from S. Am. for what seems to be an African taxon.

## ZooBank


## Wikispecies


## Journals

### Journals beginning with 'B'


```
PREFIX schema: <http://schema.org/>
SELECT DISTINCT ?name WHERE { 
  ?periodical rdf:type schema:Periodical .
  ?periodical schema:name ?name .
  
  # Filter on those with name begining with letter
  FILTER (regex(str(?name), "^B")) .
  
}
ORDER BY ?name
```

### Get articles in journal by journal name

```
PREFIX schema: <http://schema.org/>
SELECT * WHERE { 
  ?periodical rdf:type schema:Periodical .
  ?periodical schema:name "Bot. Jahrb. Syst." .

  ?work  schema:isPartOf ?periodical .  
  ?work ?p ?o .
}
ORDER BY ?name
```

## Replacement names

Can now show replacement names in both directions

See http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:15507-1


## Examples to show/expore

### Lots of authors with ORCIDs

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1002/fedr.201700018. (three authors all with ORCIDs)


