# Types

Searching for plant specimens in GBIf can be a challenge as people cite collector numbers, not barcodes. GBIF’s search interface is pretty poor at handling this. Can try and match institute and collector

[institutionCode=MO&recordNumber=Croat 56925](http://api.gbif.org/v1/occurrence/search?institutionCode=MO&recordNumber=Croat%2056925)

recordNumber can be just the number, or collector and number. GBIF doesn’t do fuzzy match of recordedBy, making that field not much use.


## Lessingianthus cipoensis Dematt.

http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:77121435-1

IPNI has type G.Hatschbach 30041, MBM (holo)

This is in GBIF as https://www.gbif.org/occurrence/1095391277 BRA:MBM:MBM:0000023220 as Lessingianthus brevipetiolatus Found dates to get match by looking at paper https://www.ingentaconnect.com/content/nhn/blumea/2012/00000057/00000002/art00003?crawler=true Not labelled as a type.

## Lessingianthus paraguariensis Dematt.

Three occurrences in GBIF are types, not labelled as such.

## Holotype of Pandanus sikassoensis Huynh [family PANDANACEAE]

JSTOR has https://plants.jstor.org/stable/10.5555/al.ap.specimen.p00836316 P00836316 as holotype of Pandanus sikassoensis, but also identified as Pandanus senegalensis

GBIF has just the Pandanus senegalensis id, and no info that it is a type, but this is in the RAW Darwin Core as https://api.gbif.org/v1/occurrence/1019561487/fragment as two dwc:Identification (check Paris RDF as well).

## Galeoglossum cactorum Chávez-Rendón, Avendaño & Sánchez 1604, MEXU (holo)

http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:77115370-1

In GBIF at least twice (BOLD mined from GenBank and Mexican dataset), GenBank sequence FN645940 linked to publication (but no identifier).

## Hollandaea diabolica A.J.Ford & P.H.Weston

http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:77124882-1

B.Hyland 25914RFK

B.Hyland 25914RFK, BRI (holo) https://www.gbif.org/occurrence/2418843057 (not labelled as type)
B.Hyland 25914RFK, CNS (iso) https://www.gbif.org/occurrence/994070942 (not labelled)
B.Hyland 25914RFK, NSW (iso) ?

## Teagueia barbeliana L.Jost & Shepard

http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:77112078-1

L.Jost 5132, QCA (holo)
L.Jost 5132, QCNE (iso)

L.Jost 5132, QCA (holo) is in JSTOR https://plants.jstor.org/stable/10.5555/al.ap.specimen.qca205659?searchUri=filter%3Dnamewithsynonyms%26so%3Dps_group_by_genus_species%2Basc%26Query%3DTeagueia

Specimen with same details in MO in GBIF, but not flagged as a type: https://www.gbif.org/occurrence/1258002041

## Hanguana loi (type in Kew and GBIF labelled as another species)

Hanguana loi described http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1007/s12225-012-9358-4 the type is https://www.gbif.org/occurrence/912553032 http://apps.kew.org/herbcat/detailsQuery.do?barcode=K000710125 (labelled as Hanguana malayana ).

## Zingiber phillippsiae

http://localhost/~rpage/lois-kg/www/?uri=urn:lsid:ipni.org:names:1012083-1

https://www.gbif.org/occurrence/47864336 
Catalogue Number: J.Mood398
Recorded by J.Mood

Note the non-standard way of storing collector info



