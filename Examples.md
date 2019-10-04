# Examples

## Tools

[DOI to ORCID](https://enchanting-bongo.glitch.me)

## IPNI names and ORCIDs

Examples to play with as we develop methods to match names, people, and identifiers.

IPNI | Name | DOI | ORCID | Wikidata | Comments
-- | -- | -- | -- |  --
77186312-1 | Miconia brigitteae | 10.18257/raccefyn.602 | 0000-0002-5685-9338 | Q6445379 | Humberto Mendoza-Cifuentes has ORCID but no public profile, ORCID in CrossRef metadata, Wikidata has IPNI id but no ORCID
77143455-1 | 	Lessingianthus dichrous | 10.3100/025.018.0214 | 0000-0001-7965-9008 | Q21389741 | ORCID not in CrossRef, but DOI in ORCID. Wikidata for author doesn’t have ORCID

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

## ZooBank


## Wikispecies
