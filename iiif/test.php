<?php

// suppress errors 
error_reporting(0); // there is an unexplained error in json-ld php


// Take RDF from source, convert to JSON-LD and output as JSONL

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

$nt = 
'
<https://archive.org/details/zoologische-verhandelingen-91-001-034/manifest.json> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#manifest> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/manifest.json> <http://www.w3.org/2000/01/rdf-schema#label> "zoologische-verhandelingen-91-001-034" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/manifest.json> <http://iiif.io/api/presentation/2#hasSequences> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Sequence> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> <http://www.w3.org/2000/01/rdf-schema#label> "0" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c0/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n0_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n0_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n0_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n0_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n0_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> <http://www.w3.org/2000/01/rdf-schema#label> "1" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> <http://www.w3.org/2003/12/exif/ns#width> "4498" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> <http://www.w3.org/2003/12/exif/ns#height> "6196" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c1/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n1_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n1_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n1_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n1_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4498" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n1_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6196" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> <http://www.w3.org/2000/01/rdf-schema#label> "2" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c2/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n2_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n2_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n2_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n2_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n2_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> <http://www.w3.org/2000/01/rdf-schema#label> "3" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c3/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n3_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n3_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n3_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n3_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n3_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> <http://www.w3.org/2000/01/rdf-schema#label> "4" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> <http://www.w3.org/2003/12/exif/ns#width> "4497" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> <http://www.w3.org/2003/12/exif/ns#height> "6196" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c4/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n4_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n4_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n4_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n4_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4497" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n4_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6196" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> <http://www.w3.org/2000/01/rdf-schema#label> "5" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> <http://www.w3.org/2003/12/exif/ns#width> "4497" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c5/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n5_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n5_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n5_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n5_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4497" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n5_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> <http://www.w3.org/2000/01/rdf-schema#label> "6" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c6/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n6_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n6_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n6_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n6_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n6_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> <http://www.w3.org/2000/01/rdf-schema#label> "7" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c7/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n7_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n7_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n7_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n7_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n7_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> <http://www.w3.org/2000/01/rdf-schema#label> "8" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c8/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n8_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n8_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n8_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n8_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n8_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> <http://www.w3.org/2000/01/rdf-schema#label> "9" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c9/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n9_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n9_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n9_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n9_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n9_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> <http://www.w3.org/2000/01/rdf-schema#label> "10" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c10/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n10_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n10_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n10_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n10_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n10_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> <http://www.w3.org/2000/01/rdf-schema#label> "11" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c11/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n11_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n11_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n11_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n11_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n11_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> <http://www.w3.org/2000/01/rdf-schema#label> "12" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c12/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n12_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n12_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n12_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n12_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n12_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> <http://www.w3.org/2000/01/rdf-schema#label> "13" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c13/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n13_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n13_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n13_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n13_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n13_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> <http://www.w3.org/2000/01/rdf-schema#label> "14" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c14/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n14_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n14_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n14_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n14_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n14_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> <http://www.w3.org/2000/01/rdf-schema#label> "15" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c15/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n15_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n15_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n15_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n15_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4495" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n15_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> <http://www.w3.org/2000/01/rdf-schema#label> "16" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c16/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n16_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n16_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n16_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n16_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n16_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> <http://www.w3.org/2000/01/rdf-schema#label> "17" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c17/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n17_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n17_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n17_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n17_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n17_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> <http://www.w3.org/2000/01/rdf-schema#label> "18" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c18/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n18_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n18_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n18_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n18_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n18_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> <http://www.w3.org/2000/01/rdf-schema#label> "19" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> <http://www.w3.org/2003/12/exif/ns#width> "4496" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c19/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n19_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n19_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n19_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n19_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4496" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n19_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> <http://www.w3.org/2000/01/rdf-schema#label> "20" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c20/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n20_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n20_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n20_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n20_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n20_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> <http://www.w3.org/2000/01/rdf-schema#label> "21" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c21/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n21_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n21_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n21_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n21_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n21_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> <http://www.w3.org/2000/01/rdf-schema#label> "22" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c22/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n22_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n22_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n22_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n22_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n22_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> <http://www.w3.org/2000/01/rdf-schema#label> "23" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c23/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n23_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n23_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n23_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n23_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n23_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> <http://www.w3.org/2000/01/rdf-schema#label> "24" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c24/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n24_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n24_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n24_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n24_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n24_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> <http://www.w3.org/2000/01/rdf-schema#label> "25" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c25/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n25_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n25_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n25_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n25_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n25_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> <http://www.w3.org/2000/01/rdf-schema#label> "26" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c26/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n26_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n26_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n26_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n26_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n26_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> <http://www.w3.org/2000/01/rdf-schema#label> "27" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c27/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n27_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n27_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n27_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n27_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n27_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> <http://www.w3.org/2000/01/rdf-schema#label> "28" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> <http://www.w3.org/2003/12/exif/ns#width> "4496" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c28/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n28_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n28_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n28_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n28_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4496" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n28_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6195" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> <http://www.w3.org/2000/01/rdf-schema#label> "29" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c29/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n29_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n29_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n29_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n29_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n29_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> <http://www.w3.org/2000/01/rdf-schema#label> "30" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c30/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n30_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n30_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n30_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n30_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n30_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> <http://www.w3.org/2000/01/rdf-schema#label> "31" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> <http://www.w3.org/2003/12/exif/ns#width> "4498" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> <http://www.w3.org/2003/12/exif/ns#height> "6197" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c31/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n31_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n31_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n31_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n31_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4498" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n31_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6197" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/default> <http://iiif.io/api/presentation/2#hasCanvases> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> <http://www.w3.org/2000/01/rdf-schema#label> "32" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> <http://www.w3.org/2003/12/exif/ns#height> "6190" .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> <http://iiif.io/api/presentation/2#hasImageAnnotations> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32/annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32/annotation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32/annotation> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32/annotation> <http://www.w3.org/ns/oa#hasTarget> <https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32> .
<https://archive.org/details/zoologische-verhandelingen-91-001-034/canvas/c32/annotation> <http://iiif.io/api/presentation/2#hasAnnotations> <http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n32_medium.jpg> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n32_medium.jpg> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n32_medium.jpg> <http://purl.org/dc/terms/format> "image/jpeg" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n32_medium.jpg> <http://www.w3.org/2003/12/exif/ns#width> "4490" .
<http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/page/n32_medium.jpg> <http://www.w3.org/2003/12/exif/ns#height> "6190" .';


$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

// Context 
$context = new stdclass;

$context->sc = 'http://iiif.io/api/presentation/2#';

$context->rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
$context->type = 'rdf:type';
   
$context->rdfs = 'http://www.w3.org/2000/01/rdf-schema#';

$context->dc = 'http://purl.org/dc/elements/1.1/';
$context->dcterms = 'http://purl.org/dc/terms/';
$context->dctypes = 'http://purl.org/dc/dcmitype/';


$context->exif = 'http://www.w3.org/2003/12/exif/ns#';
$context->oa = 'http://www.w3.org/ns/oa#';


$context->sequences = new stdclass;
$context->sequences->{'@id'} = 'sc:hasSequences';
$context->sequences->{'@container'} = '@set';

$context->canvases = new stdclass;
$context->canvases->{'@id'} = 'sc:hasCanvases';
$context->canvases->{'@container'} = '@set';

$context->images = new stdclass;
$context->images->{'@id'} = 'sc:hasImageAnnotations';
$context->images->{'@container'} = '@set';

$context->resource = 'sc:hasAnnotations';


// works
$context->motivation = new stdclass;
$context->motivation->{'@type'} = '@id';
$context->motivation->{'@id'} = 'oa:motivatedBy';

// works
$context->on = new stdclass;
$context->on->{'@type'} = '@id';
$context->on->{'@id'} = 'oa:hasTarget';


$context->label 	= 'rdfs:label';
$context->format 	= 'dcterms:format';

$context->width 	= 'exif:width';
$context->height 	= 'exif:height';

$frame = (object)array(
	'@context' => $context,	
	'@type' => 'http://iiif.io/api/presentation/2#manifest'
);


$data = jsonld_frame($doc, $frame);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo "\n";


/*
// make nice
 var context = {
  "sc" : "http://iiif.io/api/presentation/2#",
  
  // RDF syntax
  "rdf" : "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
  "type": "rdf:type",
   
  "rdfs" : "http://www.w3.org/2000/01/rdf-schema#",  
  
  exif: "http://www.w3.org/2003/12/exif/ns#",
  
  oa: "http://www.w3.org/ns/oa#",
  
  ia: "http://www.archive.org/download/zoologische-verhandelingen-91-001-034/zoologische-verhandelingen-91-001-034/",
  
  dctypes: "http://purl.org/dc/dcmitype/",
  
  
  
  // Dublin Core
  "dc" : "http://purl.org/dc/terms/",
  dcterms: "http://purl.org/dc/terms/",
  
  // SC
	"label": {
		"@id": "rdfs:label"
	},
	
"sequences": {
"@type": "@id",
"@id": "sc:hasSequences",
"@container": "@list"
}	,

"canvases": {
"@type": "@id",
"@id": "sc:hasCanvases",
"@container": "@list"
},

height: {
"@id": "exif:height"
},

width: {
"@id": "exif:width"
},

"images":
{
"@type": "@id",
"@id": "sc:hasImageAnnotations",
"@container": "@list"
},

motivation: {
"@type": "@id",
"@id": "oa:motivatedBy"
},

on: {
"@type": "@id",
"@id": "oa:hasTarget"
},


resources: {
"@type": "@id",
"@id": "sc:hasAnnotations",
"@container": "@list"
},

*/

?>
