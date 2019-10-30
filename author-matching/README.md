# Author matching examples

## need a tool to search for names

Wikidata

## Additional IPNI names

Using spreadsheet from Heather, 

- get fungal authors
- match to Wikidata
- match to ORCID
- check using ORCID publication record
- check using Index Fungorum name

We would need to do manual match between person’s name, the example taxon, the IF record for that name, and ORCID.


New IPNI API https://beta.ipni.org/api/1/a/urn:lsid:ipni.org:authors:20039522-1

```
{
alternativeNames: "",
dates: "fl. 2015",
examples: "Calycina marina (Boyd) Rämä &amp; Baral 2015",
forename: "Teppo",
fqId: "urn:lsid:ipni.org:authors:20039522-1",
id: "20039522-1",
recordType: "author",
source: "Index Fungorum",
standardForm: "Rämä",
surname: "Rämä",
suppressed: false,
taxonGroups: "Mycology",
url: "/a/20039522-1",
version: "1.1",
hasBhlLinks: false
}
```

## ORCID to IPNI author id

```
PREFIX schema: <http://schema.org/>
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>

select *
WHERE
{
  	# ORCID
    VALUES ?orcid_iri { <https://orcid.org/0000-0002-5685-9338> }

    # person
    ?orcid_iri schema:name ?orcid_name .
  
  	# works linked to this ORCID 
    BIND(REPLACE(STR(?orcid_iri), 'https://orcid.org/', '') AS ?orcid) .
    ?person_identifier schema:value ?orcid .
    ?person_identifier schema:propertyID "orcid" .
    ?orcid_work_creator schema:identifier ?person_identifier .
    ?orcid_work_creator_role schema:creator ?orcid_work_creator .
    ?orcid_work_creator_role schema:roleName  ?orcid_work_creator_roleName  .
    ?orcid_work schema:creator ?orcid_work_creator_role .
  
  	# taxonomic names 
  	?ipni tcom:publishedInCitation ?orcid_work .
  
    # team
	?ipni tn:authorteam ?ipni_team .
    ?ipni_team tm:hasMember ?ipni_team_member .
    ?ipni_team_member tm:role ?ipni_role .
    ?ipni_team_member tm:index ?ipni_roleName .
    ?ipni_team_member tm:member ?ipni_member .
  
  	# these queries require that we have authors in triplestore (may be misisng more recent ones)
   	?ipni_member dc:title ?ipni_member_name .  
    BIND(REPLACE(STR(?ipni_member), "urn:lsid:ipni.org:authors:", "", "i") AS ?ipni_author_id)  

  	
    FILTER (?ipni_roleName = ?orcid_work_creator_roleName)
    FILTER (?ipni_role != 'Basionym Author')
} 
```

## IPNI name to publication plus ORCID profile (debug for one)

```
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT *



WHERE
{
   VALUES ?ipni {<urn:lsid:ipni.org:names:77080462-1>} 
           
    # publication for name
    ?ipni tcom:publishedInCitation ?pub_work .
  
	?pub_work schema:creator ?pub_role  . 
    ?pub_role schema:roleName ?pub_roleName  .    
    ?pub_role schema:creator ?pub_creator  .    
    ?pub_creator schema:name ?pub_name .
  
  	# IPNI 
    ?ipni tn:authorteam ?ipni_team .
    ?ipni_team tm:hasMember ?ipni_team_member .
    ?ipni_team_member tm:role ?ipni_role .
    ?ipni_team_member tm:index ?pub_roleName .
    ?ipni_team_member tm:member ?ipni_member .
    ?ipni_member dc:title ?ipni_member_name .  
    BIND(REPLACE(STR(?ipni_member), "urn:lsid:ipni.org:authors:", "", "i") AS ?ipni_author_id)
  
    #FILTER (?ipni_role != 'Basionym Author')
  
}


```

## IPNI name to publication plus ORCID profile

```
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT ?pub_work  ?pub_roleName
(GROUP_CONCAT(DISTINCT ?pub_name; separator="|") AS ?work_name)
(GROUP_CONCAT(DISTINCT ?ipni_author_id; separator="|") AS ?ipni_id)
(GROUP_CONCAT(DISTINCT ?ipni_member_name; separator="|") AS ?ipni_name)
(GROUP_CONCAT(DISTINCT ?pub2_name; separator="|") AS ?orcid_name)
(GROUP_CONCAT(DISTINCT ?orcid; separator="|") AS ?orcid_id)



WHERE
{
   #VALUES ?ipni {<urn:lsid:ipni.org:names:77074582-1>} 
           
    # publication for name
    ?ipni tcom:publishedInCitation ?pub_work .
  
	?pub_work schema:creator ?pub_role  . 
    ?pub_role schema:roleName ?pub_roleName  .    
    ?pub_role schema:creator ?pub_creator  .    
    ?pub_creator schema:name ?pub_name .
  
  	# IPNI 
    ?ipni tn:authorteam ?ipni_team .
    ?ipni_team tm:hasMember ?ipni_team_member .
    ?ipni_team_member tm:role ?ipni_role .
    ?ipni_team_member tm:index ?pub_roleName .
    ?ipni_team_member tm:member ?ipni_member .
    ?ipni_member dc:title ?ipni_member_name .  
    BIND(REPLACE(STR(?ipni_member), "urn:lsid:ipni.org:authors:", "", "i") AS ?ipni_author_id)
  
    FILTER (?ipni_role != 'Basionym Author')
  
  # ORCID
    OPTIONAL {
    ?pub_work schema:identifier ?pub_identifier .
    ?pub_identifier schema:propertyID "doi" .
    ?pub_identifier schema:value ?doi .
    
    ?pub2_identifier schema:value ?doi .
    ?pub2 schema:identifier ?pub2_identifier .
    ?pub2 schema:creator ?pub2_role  . 
    ?pub2_role schema:roleName ?pub_roleName  . 
    ?pub2_role schema:creator ?pub2_creator  . 
    ?pub2_creator schema:name ?pub2_name .
    
	?pub2_creator schema:identifier ?person_identifier .
    ?person_identifier schema:propertyID "orcid" .
    ?person_identifier schema:value ?orcid .
    
    BIND(IRI(CONCAT("https://orcid.org/", STR(?orcid))) AS ?orcid_uri) . 
    ?orcid_uri schema:name ?orcid_name .
    }

}
GROUP BY ?pub_work ?pub_roleName

```


