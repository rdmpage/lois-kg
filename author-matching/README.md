# Author matching examples

IPNI name to publication plus ORCID profile

```
PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT *
WHERE
{
    VALUES ?ipni {<urn:lsid:ipni.org:names:77074582-1>} 
           
    ?ipni tcom:publishedInCitation ?pub_work .
  
	?pub_work schema:creator ?pub_role  . 
    ?pub_role schema:roleName ?pub_roleName  .    
    ?pub_role schema:creator ?pub_creator  .    
    ?pub_creator schema:name ?pub_name .
  
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
  
  # OPTIONAL {
  #    ?pub_creator schema:identifier ?person_identifier .
  #    ?person_identifier schema:propertyID "orcid" .
  #    ?person_identifier schema:value ?orcid .
  #    }
  
    ?ipni tn:authorteam ?ipni_team .
    ?ipni_team tm:hasMember ?ipni_team_member .
    ?ipni_team_member tm:role ?ipni_role .
    ?ipni_team_member tm:index ?pub_roleName .
    ?ipni_team_member tm:member ?ipni_member .
    ?ipni_member dc:title ?ipni_member_name .   
  
 

}

```