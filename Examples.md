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

### Bad ORCIDs

https://orcid.org/0000-0002-5202-9708 J.A Perez-Taborda seems to combine e two different people, a physicist and a taxonomist

### ORCIDs in CrossRef with no or few pubs in CrossRef

https://api.crossref.org/v1/works/10.1111/jbi.13190 (if papers cites those authors can we then infer ORCIDs apply to those authors (e.g., Bonaventure Sonké has ORCID http://orcid.org/0000-0002-4310-3603 but that profile is empty, if papers by Sonké are cited by that paper, then likely that Sonké is same person).


### Lots of authors with ORCIDs

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1002/fedr.201700018. (three authors all with ORCIDs)

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1007/s00606-018-1504-5  (three authors all with ORCIDs)

### Interesting people

Keooudone Souvannakhoummane 0000-0003-4875-8307 

运洪 谭 0000-0001-6238-2743



### Authors with empty ORCID profiles but ORCIDs in publications

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0002-0585-5513 Van Truong Do

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0002-8195-6738 Xiaofeng Jin

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0001-8464-8688 Lin Bai

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0001-9197-9805 Jia-chen Hao

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0001-8026-6631 Arjun Tiwari

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0002-5994-8117 Haidar Ali

http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0001-9060-0891 Pu Zou

https://orcid.org/0000-0002-6331-3456 Ian (WTF!)


## GenBank specimens

### Cyrtochilum meirax (and others)

Whitten 2686 FLAS
FJ565600
FJ565089

https://www.gbif.org/occurrence/1457774952

https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5430592
https://dx.doi.org/10.1186%2Fs40529-017-0164-z

Specimens listed in Excel spreadsheet


## Interesting clade

### Australian genera all closely related

https://tree.opentreeoflife.org/opentree/opentree11.4@mrcaott14863ott486102/Triplarina--Homalocalyx

https://www.gbif.org/species/3177392

http://ispecies.org/?q=Micromyrtus

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1071/sb14011

### New Caledonia

http://ispecies.org/?q=Pycnandra

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1071/sb09029

## Interesting papers

### A new subfamily classification of the Leguminosae based on a taxonomically comprehensive phylogeny – The Legume Phylogeny Working Group (LPWG)

Lots of authors, lots of ORCIDs http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.12705/661.3

However, only manage to connect 1 ORCID to this work, perhaps because of way it appears in author’s profiles:

ORCIDS linked to this work https://enchanting-bongo.glitch.me/search?q=10.12705%2F661.3

 orcid.org/0000-0002-1569-8849
 orcid.org/0000-0002-7204-0744
 orcid.org/0000-0002-6718-6669
 orcid.org/0000-0003-3212-9688
 orcid.org/0000-0003-4482-0409
 orcid.org/0000-0002-7728-6487
 orcid.org/0000-0003-2135-7650
 orcid.org/0000-0002-9591-3085
 orcid.org/0000-0002-9350-3306
 orcid.org/0000-0001-5547-0796
 orcid.org/0000-0002-5788-9010
 orcid.org/0000-0003-4205-1802
 orcid.org/0000-0001-5112-7077
 orcid.org/0000-0001-9269-7316
 orcid.org/0000-0003-2657-8671
 orcid.org/0000-0002-2500-824X
 orcid.org/0000-0002-5740-3666
 orcid.org/0000-0003-0134-3438
 orcid.org/0000-0002-7875-4510
 orcid.org/0000-0001-5105-7152
 orcid.org/0000-0002-0855-4169
 orcid.org/0000-0002-9765-2907
 orcid.org/0000-0001-7072-2656
 orcid.org/0000-0002-5732-1716
 orcid.org/0000-0002-7940-5435
 orcid.org/0000-0002-4484-3566
 orcid.org/0000-0003-4977-4341
 orcid.org/0000-0002-9644-0566

### A new species of Dalbergia (Fabaceae: Dalbergieae) from Tamil Nadu, India

All authors have ORCIDs http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.11646/phytotaxa.360.3.8


