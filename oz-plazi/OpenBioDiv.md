# OpenBioDiv

## Queries

```
PREFIX po: <http://www.essepuntato.it/2008/12/pattern#>
PREFIX dwc: <http://rs.tdwg.org/dwc/terms/>
PREFIX prism: <http://prismstandard.org/namespaces/basic/2.0/>
PREFIX openbiodiv: <http://openbiodiv.net/>
SELECT * {
	#?materials dwc:institutionCode ?institutionCode .
	
	?materials a openbiodiv:MaterialsExamined .
	?materials po:isContainedBy ?treatment .
	?treatment a openbiodiv:Treatment .
	?treatment po:isContainedBy ?work .
	
	OPTIONAL {
		?work prism:doi ?doi .
	}
	
	OPTIONAL {
		?materials dwc:occurrenceID ?occurrenceID .
		OPTIONAL {
			?occurrenceID dwc:catalogNumber ?catalogNumber .
		}
		OPTIONAL {
			?occurrenceID dwc:otherCatalogNumbers ?otherCatalogNumbers .
		}
	}
	
}
LIMIT 10
```

```
# occurrences
PREFIX dwc: <http://rs.tdwg.org/dwc/terms/>
SELECT * {
	?occurrenceID a <http://rs.tdwg.org/dwc/terms/Occurrence> .
	?occurrenceID dwc:catalogNumber ?catalogNumber .
	?materials dwc:occurrenceID ?occurrenceID .
	
}
LIMIT 10
```

```
PREFIX dwc: <http://rs.tdwg.org/dwc/terms/>
PREFIX openbiodiv: <http://openbiodiv.net/>
PREFIX po: <http://www.essepuntato.it/2008/12/pattern#>
PREFIX pkm: <http://proton.semanticweb.org/protonkm#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX prism: <http://prismstandard.org/namespaces/basic/2.0/>
select distinct ?taxonLabel ?recorder ?identificator ?institutionID ?institutionCode ?doi where { 
    ?article po:contains ?treatment.
    ?article prism:doi ?doi.
	?treatment a openbiodiv:Treatment.
    ?treatment po:contains ?materials.
	?treatment po:contains ?nomenclature.
    ?nomenclature a openbiodiv:NomenclatureSection.
    ?nomenclature po:contains ?tnu.
    ?tnu pkm:mentions ?taxon.
    ?taxon rdfs:label ?taxonLabel.
    ?materials a openbiodiv:MaterialsExamined.
    ?materials dwc:occurrenceID ?occ.
    ?occ dwc:recordedBy ?recorder.
    ?materials dwc:identificationID ?identification.
    ?materials ?p ?o.
    ?identification dwc:identifiedBy ?identificator.
    ?materials dwc:institutionID ?institutionID.
    ?materials dwc:institutionCode ?institutionCode.
} 
```

