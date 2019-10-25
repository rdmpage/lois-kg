/*

Shared code


*/
//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/25715455
function isObject(item) {
  return (typeof item === "object" && !Array.isArray(item) && item !== null);
}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/21445415
function uniques(arr) {
  var a = [];
  for (var i = 0, l = arr.length; i < l; i++)
    if (a.indexOf(arr[i]) === -1 && arr[i] !== '')
      a.push(arr[i]);
  return a;
}


//----------------------------------------------------------------------------------------
// Store a triple with optional language code
function triple(subject, predicate, object, language) {
  var triple = [];
  triple[0] = subject;
  triple[1] = predicate;
  triple[2] = object;

  if (typeof language === 'undefined') {} else {
    triple[3] = language;
  }

  return triple;
}

//----------------------------------------------------------------------------------------
// Enclose triple in suitable wrapping for HTML display or triplet output
function wrap(s, html) {
  if (s) {
	// URL as a literal (e.g., schema:sameAs)
   if (s.match(/^"(http|urn|_:)/)) {
   		return s;
   } else {	

	  if (s.match(/^(http|urn|_:)/)) {
		s = s.replace(/\\_/g, '_');

		// handle < > in URIs such as SICI-based DOIs
		s = s.replace(/</g, '%3C');
		s = s.replace(/>/g, '%3E');
  
		if (html) {
		  s = '&lt;' + s + '&gt;';
		} else {
		  s = '<' + s + '>';
		}
	  } else {
		s = '"' + s.replace(/"/g, '\\"') + '"';
	  }}
	}
  return s;

}

//----------------------------------------------------------------------------------------
// https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

//----------------------------------------------------------------------------------------
function detect_language(s) {
  var language = null;
  var matched = 0;
  var parts = [];

  var regexp = [];

  // https://gist.github.com/ryanmcgrath/982242
  regexp['ja'] = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  // http://hjzhao.blogspot.co.uk/2015/09/javascript-detect-chinese-character.html
  regexp['zh'] = /[\u4E00-\uFA29]/g;
  // http://stackoverflow.com/questions/32709687/js-check-if-string-contains-only-cyrillic-symbols-and-spaces
  regexp['ru'] = /[\u0400-\u04FF]/g;

  for (var i in regexp) {
    parts = s.match(regexp[i]);

    if (parts != null) {
      if (parts.length > matched) {
        language = i;
        matched = parts.length;
      }
    }
  }

  // require a minimum matching
  if (matched < 2) {
    language = null;
  }

  return language;

}

//----------------------------------------------------------------------------------------
function output(doc, triples) {
  // CouchDB
  for (var i in triples) {
    var s = 0;
    var p = 1;
    var o = 2;

    var lang = 3;

    var nquads = wrap(triples[i][s], false) +
      ' ' + wrap(triples[i][p], false) +
      ' ' + wrap(triples[i][o], false);
    if (triples[i][lang]) {
      nquads += '@' + triples[i][lang];
    }

    nquads += ' .' + "\n";

    // use cluster_id as the key so triples from different versions are linked together
    emit(doc._id, nquads);
    //console.log(nquads);
  }
}

//----------------------------------------------------------------------------------------
// START COUCHDB VIEW

//----------------------------------------------------------------------------------------
// Return true if value is not empty, tests for strange stuff such as "[Not Stated]"
function not_empty(value) {
	var empty = true;
	
	if (!value) {
		return false;
	}

	if (value == null) {
		return false;
	}

	if (value.match(/\[Not Stated\]/i)) {
		return false;
	}
	
	return empty;


}

//----------------------------------------------------------------------------------------
// Get a list of specimen codes and other potential specimen identifiers
function specimen_codes(doc) {
  var codes = [];

  // By default we simply output institutionCode and catalogNumber,
  // but for some we need special processing. Setting output_default to false
  // means that the simple institutionCode + catalogNumber
  // doesn't apply.
  var output_default = true;

  // Here we do any special processing do create codes seen in the wild
  switch (doc.message.datasetKey) {

    // AMNH
    // Birds
    case '96c93a1e-f762-11e1-a439-00145eb45e9a':
      var catalogNumber = doc.message.catalogNumber;
      catalogNumber = catalogNumber.replace(/DOT-/i, '');
      catalogNumber = catalogNumber.replace(/SKIN-/i, '');
      codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      break;

      // AM
    case 'dce8feb0-6c89-11de-8225-b8a03c50a862':
      var catalogNumber = doc.message.catalogNumber;
      catalogNumber = catalogNumber.replace(/\\./, '');
      codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      if (catalogNumber.match(/^R/)) {
        codes.push(doc.message.institutionCode + catalogNumber);
      }
      codes.push('Australian Museum ' + catalogNumber);
      output_default = false;
      break;

      // ANWC
    case '0debafd0-6c8a-11de-8225-b8a03c50a862':
      if (doc.message.catalogNumber.match(/^[A-Z]/)) {
        codes.push(doc.message.institutionCode + ':' + doc.message.catalogNumber.replace(/^[A-Z][0]*/, ''));
      }
      break;

      // CASIZ
    case '44bcde48-ac71-46f2-bf73-24fc3c008b6c':
      var catalogNumber = doc.message.catalogNumber.replace(/\\.0/, '');
      codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      // CAS:CASIZ 175448 
      codes.push(doc.message.institutionCode + ':CASIZ ' + catalogNumber);
      output_default = false;
      break;

      // CAS herps
    case 'cece4fc2-1fec-4bb5-a335-7252548e3f0b':
      codes.push(doc.message.institutionCode + doc.message.catalogNumber);
      codes.push(doc.message.institutionCode + ':Herp:' + doc.message.catalogNumber);
      break;
      // CAS Ichs
        
    case '5d6c10bd-ea31-4363-8b79-58c96d859f5b':
          codes.push(doc.message.institutionCode + doc.message.catalogNumber);    
      codes.push(doc.message.institutionCode + ':Ich:' + doc.message.catalogNumber);    
      break;

      // CSIRO
      // CSIRO Ichthyology provider for OZCAM
    case '18c93d12-34fb-4d3f-903c-b77215a1dcc9':
      if (doc.message.catalogNumber.match(/^H/)) {
        // BOLD cites them like this
        codes.push(doc.message.institutionCode + ' H ' + doc.message.catalogNumber.replace(/^H/, ''));
      }
      break;

      // FMNH
    case 'e48d6c49-34a3-4df6-8206-121c061f190d':
        codes.push(doc.message.institutionCode + ':HERP:' + doc.message.catalogNumber);
      break;

      // Kew
    case 'cd6e21c8-9e8a-493a-8a76-fbf7862069e5':
        codes.push(doc.message.catalogNumber);
      output_default = false;
      break;

      // MHNG
    case '5a659248-1f70-11e3-b2c5-00145eb45e9a':
      if (doc.message.catalogNumber.match(/^MHNG-MAM-/)) {
        var catalogNumber = doc.message.catalogNumber;
        catalogNumber = catalogNumber.replace(/MHNG-MAM-/, '');  
        codes.push(doc.message.institutionCode + ' ' + catalogNumber);

        // for BOLD
        catalogNumber = catalogNumber.replace(/\\./, '');  
        codes.push(doc.message.institutionCode + '-' + catalogNumber);
        output_default = false;
      }
      break;

      // MNHN
    case '1b2af425-9f6f-4b28-a008-af9757317c4c':
      if (doc.message.catalogNumber.match(/^ENSIF/)) {
        var catalogNumber = doc.message.catalogNumber;
        catalogNumber = catalogNumber.replace(/ENSIF/, '');  
        codes.push(doc.message.institutionCode + ':ENSIF ' + catalogNumber);
      }
      break;
      // MHNH birds
    case 'ba0c03ab-fa61-4a3c-8db7-35c8c3454168':
      if (doc.message.catalogNumber.match(/^MO/)) {
        var catalogNumber = doc.message.catalogNumber;
        catalogNumber = catalogNumber.replace(/MO-/, '');  
        codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      }
      break;

      // MCZ
    case '4bfac3ea-8763-4f4b-a71a-76a6f5f243d3':
      if (doc.message.catalogNumber.match(/^[R]-/)) {
        codes.push(doc.message.institutionCode + ' ' + doc.message.catalogNumber.replace(/^[R]-/, ''));
      }
      break;

      // MVZ
    case '09c4287e-e6d5-4552-a07f-bff8a00833d8':
         codes.push(doc.message.institutionCode + ':Herp:' + doc.message.catalogNumber);   
      break;

      // NMV D
    case '39905320-6c8a-11de-8226-b8a03c50a862':
      codes.push(doc.message.institutionCode + doc.message.catalogNumber.replace(/ /, ''));
      codes.push('NMV<AUS>:' + doc.message.institutionCode + doc.message.catalogNumber.replace(/ /, ''));
      break;

      // OMNH Osaka Museum of Natural History fish
    case '849f0a76-f762-11e1-a439-00145eb45e9a':
      if (doc.message.collectionCode == 'P') {
        codes.push(doc.message.institutionCode + '-' + doc.message.collectionCode + ' ' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
        output_default = false;
      }
      break;

      // OSUC
      // C.A. Triplehorn Insect Collection, Ohio State University, Columbus, OH (OSUC)
    case '84ab7b76-f762-11e1-a439-00145eb45e9a':
      codes.push(doc.message.catalogNumber);
      output_default = false;
      break;
      

      // TTU
      // mammals
    case '854f70cc-55e3-4af2-9417-0f47d6c7902d':
      // output field numbers as tissue numbers
      if (doc.message.fieldNumber) {
        codes.push('TK' + doc.message.fieldNumber);
        codes.push('TK ' + doc.message.fieldNumber);
      }
      break;

      // UAM
      // mammals
    case '377be098-626f-4cc2-b4b5-35700050669a':
      codes.push(doc.message.institutionCode + ':Mamm:' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
      break;

      // UF (FLMNH)
      // Ichthyology
    case 'eccf4b09-f0c8-462d-a48c-41a7ce36815a':
      codes.push('FLMNH ' + doc.message.catalogNumber);
      codes.push('UF' + doc.message.catalogNumber);
      break;
      // Inverts (e.g., moolluscs)
    case '85b1cfb6-f762-11e1-a439-00145eb45e9a':
      codes.push('UF ' + doc.message.catalogNumber.replace(/\\-\\w+$/, ''));
      break;

      // USNM
    case '5df38344-b821-49c2-8174-cf0f29f4df0d':
      codes.push(doc.message.institutionCode + ' ' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));

      switch (doc.message.collectionCode) {
        case 'Amphibians & Reptiles':
          codes.push(doc.message.institutionCode + ':Herp:' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        case 'Birds':
          codes.push(doc.message.institutionCode + ':Birds:' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        case 'Fishes':
          codes.push(doc.message.institutionCode + ':Fish:' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        case 'Mammals':
          codes.push(doc.message.institutionCode + ':MAMM:' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        default:
          break;
      }
      output_default = true;
      break;

      // UTA
      // Herpetology
    case '8d88898d-a2c4-4616-a1fe-431b9c06b671':
      switch (doc.message.collectionCode) {
        case 'UTA-A':
          codes.push(doc.message.institutionCode + ' A' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          codes.push(doc.message.institutionCode + ' A-' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        case 'UTA-R':
          codes.push(doc.message.institutionCode + ' R' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          codes.push(doc.message.institutionCode + ' R-' + doc.message.catalogNumber.replace(/\\.\\d+/, ''));
          break;
        default:
          break;
      }
      break;

      // UWBM 
      // birds
    case '830fd460-f762-11e1-a439-00145eb45e9a':
      break;

      // UZA
    case '96c25708-f762-11e1-a439-00145eb45e9a':
      codes.push(doc.message.catalogNumber);
      output_default = false;
      break;

      // WAM
    case '7c93d290-6c8b-11de-8226-b8a03c50a862':
      codes.push(doc.message.institutionCode + doc.message.catalogNumber);
      codes.push(doc.message.institutionCode + ' ' + doc.message.catalogNumber.replace(/^R/, ''));

      if (doc.message.collectionCode == "REPT") {
        // Number-only codes need R prefix
        if (doc.message.catalogNumber.match(/^\\d+/)) {
          codes.push(doc.message.institutionCode + 'R' + doc.message.catalogNumber);
          codes.push(doc.message.institutionCode + ' R' + doc.message.catalogNumber);
        }
      }
      break;

      // YPM Inverts
    case '854e35e6-f762-11e1-a439-00145eb45e9a':
      // YPM entomology
    case '96404cc2-f762-11e1-a439-00145eb45e9a':
      // YPM fish
    case '96419bea-f762-11e1-a439-00145eb45e9a':
      // YPM Herps
    case '861d3d64-f762-11e1-a439-00145eb45e9a':
      // YPM birds
    case '854cf79e-f762-11e1-a439-00145eb45e9a':
      // YPM mammals
    case '854f602e-f762-11e1-a439-00145eb45e9a':
      var parts = doc.message.catalogNumber.match(/(YPM)\\s+([A-Z]+)\\s+(0+)?(\\d+)/);
      if (parts) {
        var collection = parts[2];
        var catalogNumber = parts[4];

        // YPM \\d+
        codes.push(doc.message.institutionCode + ' ' + catalogNumber);
        // YPM as is
        codes.push(doc.message.catalogNumber);
        // DwC triple
        collection = collection.substr(0, Math.min(3, collection.length));
        codes.push(doc.message.institutionCode + ':' + collection + ':' + catalogNumber);
      }
      output_default = false;
      break;
      
      // ZMA
      // ZMA fish types
    case '903b7df0-6166-11de-84bf-b8a03c50a862':
      var catalogNumber  = doc.message.catalogNumber;
      catalogNumber = catalogNumber.replace(/Pisces_/, '');
      catalogNumber = catalogNumber.replace(/,/, '.');
      codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      output_default = true;
      break;

      // ZMUC
    case 'cb643105-2e6b-403d-a23b-2c8128d1f97c':
      codes.push(doc.message.catalogNumber.replace(/-/, ' '));
      output_default = false;
      break;

      // ZMUC birds
    case 'af3bce08-0599-45a6-9bfc-08188bcd868e':
      var catalogNumber = doc.message.catalogNumber.replace(/AVES-0?/, '');
      codes.push(doc.message.institutionCode + ' ' + catalogNumber);
      // see doi:10.1007/s10336-006-0082-4
      if (catalogNumber.length == 5) {
        codes.push(doc.message.institutionCode + ' ' + catalogNumber.substr(0, 2) + '.' + catalogNumber.substr(2, 3));
      }
      output_default = false;
      break;

      // Zoologische Staatssammlung Muenchen
      // International Barcode of Life (iBOL) - Barcode of Life Project Specimen Data
    case 'f29ab192-5964-40ae-a397-fa48ffdf0661':
      codes.push(doc.message.catalogNumber);
      output_default = false;
      break;

    default:
      output_default = true; // for now while we debug, make true when all done
      break;
  }


  // add any other useful records
  if (doc.message.otherCatalogNumbers) {
    codes.push(doc.message.otherCatalogNumbers);
  }

  if (doc.message.recordNumber) {
    codes.push(doc.message.recordNumber);
    
    if (doc.message.recordedBy) {
    	codes.push(doc.message.recordedBy + ' ' + doc.message.recordNumber);
    }
  }

	
  if (output_default) {
    if (doc.message.institutionCode && doc.message.catalogNumber) {
      codes.push(doc.message.institutionCode + ' ' + doc.message.catalogNumber);
    }

    // For when we have complete specimen code as catalogue number
    if (doc.message.catalogNumber) {
      codes.push(doc.message.catalogNumber);
    }
  }

  // Make unique
  var unique = [];
  for (var i = 0; i < codes.length; i++) {
    if (unique.indexOf(codes[i]) == -1) {
      unique.push(codes[i]);
    }
  }

  return unique;
}

//----------------------------------------------------------------------------------------
function message(doc) {

    var triples = [];
    
    
	var gbifID = 0;
	if (doc.message.gbifID) {
		gbifID = doc.message.gbifID;
	}
	if (doc.message.key) {
		gbifID = doc.message.key;
	}

	var subject_id = 'https://www.gbif.org/occurrence/' + gbifID;


	var name = [];
	if (doc.message.institutionCode) {
		name.push(doc.message.institutionCode);
	}
	if (doc.message.collectionCode) {
		name.push(doc.message.collectionCode);
	}
	if (doc.message.catalogNumber) {
		name.push(doc.message.catalogNumber);
	}
	// Cases where there is not a Darwin Core triple
	if (name.length == 0) {
		if (doc.message.occurrenceID) {
	  		name.push(doc.message.occurrenceID);
		}
	}
	
	// schema.org

	triples.push(triple(subject_id,
		'http://schema.org/name',
		String(name.join(' ')) ));
		

	  // post processing

	  // alternative specimen codes
	  var codes = specimen_codes(doc);
	  for (var i in codes) {
		triples.push(triple(subject_id,
		  'http://schema.org/alternateName',
		  String(codes[i])));
	  }    
		
	// GBIF taxonomy	
	if (doc.message.acceptedTaxonKey) {
		var taxon_id = 'https://www.gbif.org/species/' + doc.message.acceptedTaxonKey;
	
		// link to GBIF taxon
		triples.push(triple(subject_id,
			'http://rs.tdwg.org/dwc/terms/taxonID',
			taxon_id
		  ));
		  
		// Add identifier so we can link
		var taxon_identifier = taxon_id + '#gbif';
		
		triples.push(triple(taxon_id,
			'http://schema.org/identifier',
			taxon_identifier
		  ));
		  
		triples.push(triple(taxon_identifier,
			'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
			'http://schema.org/PropertyValue'
		  ));
		  

		triples.push(triple(taxon_identifier,
			'http://schema.org/propertyID',
			'"https://www.wikidata.org/wiki/Property:P846"'
		  ));
		  
		triples.push(triple(taxon_identifier,
			'http://schema.org/name',
			'GBIF'
		  ));

		triples.push(triple(taxon_identifier,
			'http://schema.org/value',
			doc.message.acceptedTaxonKey.toString()
		  ));
	}			
		
	// Dublin Core and Darwin Core from here	
/*
	triples.push(triple(subject_id,
		'http://purl.org/dc/terms/title',
		String(name.join(' ')) ));
*/

	triples.push(triple(subject_id,
		'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
		'http://rs.tdwg.org/dwc/terms/Occurrence'));
		
  // fields
  for (var i in doc.message) {
    if (doc.message[i] != null) {
      switch (i) {
		
			  // Occurrence (usually a specimen)
			  // Darwin Core occurrence---------------------------------------------------
			case 'catalogNumber':
			case 'collectionCode':
			case 'collectionID':
			case 'institutionCode':
			case 'institutionID':
			case 'recordedBy':
			case 'individualCount':
			case 'organismQuantity':
			case 'sex':
			case 'lifeStage':
			case 'reproductiveCondition':
			case 'behavior':
			case 'establishmentMeans':
			case 'occurrenceStatus':
			case 'preparations':
			case 'disposition':
			case 'otherCatalogNumbers':
			case 'recordNumber':
			case 'occurrenceRemarks':
			case 'occurrenceID':

			  triples.push(triple(subject_id,
				'http://rs.tdwg.org/dwc/terms/' + i,
				String(doc.message[i])));
			  break;
			  
         // Darwin Core locality-----------------------------------------------------------
        case 'continent':
        case 'coordinateUncertaintyInM':
        case 'coordinatePrecision':
        case 'country':
        case 'county':
        case 'countryCode':
        case 'decimalLatitude':
        case 'decimalLongitude':
        case 'depth':
        case 'depthAccuracy':
        case 'elevation':
        case 'footprintWKT':
        case 'footprintSRS':
        case 'footprintSpatialFit':
        case 'geodeticDatum':
        case 'georeferencedBy':
        case 'georeferencedDate':
        case 'georeferenceSources':
        case 'georeferenceProtocol':
        case 'georeferenceVerification':
        case 'georeferenceRemarks':
        case 'higherGeography':
        case 'higherGeographyID':
        case 'island':
        case 'islandGroup':
        case 'locality':
        case 'locationAccordingTo':
        case 'locationID':
        case 'locationRemarks':
        case 'maximumElevationInMeters':
        case 'minimumElevationInMeters':
        case 'maximumDepthInMeters':
        case 'minimumDepthInMeters':
        case 'minimumDistanceAboveSurf':
        case 'maximumDistanceAboveSurf':
        case 'municipality':
        case 'pointRadiusSpatialFit':
        case 'stateProvince':
        case 'verbatimCoordinates':
        case 'verbatimLatitude':
        case 'verbatimLongitude':
        case 'verbatimCoordinateSystem':
        case 'verbatimSRS':
        case 'verbatimDepth':
        case 'verbatimElevation':
        case 'verbatimLocality':
        case 'waterBody':
          triples.push(triple(subject_id,
            'http://rs.tdwg.org/dwc/terms/' + i,
            String(doc.message[i])));
          break;			  
		  
          // Darwin Core Event----------------------------------------------------------
        case 'fieldNumber':
        case 'eventDate':
        case 'year':
        case 'month':
        case 'day':
        case 'verbatimEventDate':
        case 'fieldNotes':
        case 'eventRemarks':
        case 'eventID':
           triples.push(triple(subject_id,
            'http://rs.tdwg.org/dwc/terms/' + i,
            String(doc.message[i])));
          break;	
          
          // media
        case 'media':
 /*       
<dwc:associatedMedia rdf:resource="http://imager.mnhn.fr/imager3/w400/media/1441382723320rSt5oKNmW0bmqfJn" />
</rdf:Description>
<rdf:Description rdf:about="http://imager.mnhn.fr/imager3/w400/media/1441382723320rSt5oKNmW0bmqfJn">
    <rdfs:comment>Describing a thumbnail representation of the item.</rdfs:comment> 
    <rdf:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
    <rdf:type rdf:resource="http://xmlns.com/foaf/spec/Image" />
    <rdf:type rdf:resource="https://www.w3.org/ns/ma-ont#Image" />
<!-- Dublin Core identifier property -->
    <dc:identifier rdf:resource="http://imager.mnhn.fr/imager3/w400/media/1441382723320rSt5oKNmW0bmqfJn" />
<!-- Dublin Core type property -->
    <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />  
<!-- Dublin Core publisher property  -->
    <dc:publisher rdf:resource="https://science.mnhn.fr/institution/mnhn/collection/p/item/search" />
<!-- Dublin Core subject property -->
<!-- The thumbnail has two subjects -->
<!-- The item -->
    <dc:subject rdf:resource="http://coldb.mnhn.fr/catalognumber/mnhn/p/p05036298" />
<!-- The original media -->
    <dc:subject rdf:resource="http://mediaphoto.mnhn.fr/media/1441382723320rSt5oKNmW0bmqfJn" />
  </rdf:Description> 
  */       
        
        // CETAF-style
          for (var j in doc.message[i]) {
            if (doc.message[i][j].type == 'StillImage') {
              var media_id = doc.message[i][j].identifier;

              // link to data
              triples.push(triple(subject_id,
                'http://rs.tdwg.org/dwc/terms/associatedMedia',
                media_id));

              triples.push(triple(media_id,
                'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
                'http://purl.org/dc/dcmitype/StillImage'));
                
              for (var k in doc.message[i][j]) {
                switch (k) {
                  case 'format':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                    break;

                  case 'title':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                    break;

                  case 'description':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                    break;

                  case 'references':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/subject',
                      doc.message[i][j][k]));
                    break;

                  case 'identifier':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                     break;

                  case 'creator':
                    triples.push(triple(media_id,
                      'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                    break;

                  case 'license':
                    triples.push(triple(media_id,
                       'http://purl.org/dc/terms/' + k,
                      doc.message[i][j][k]));
                    break;


                  default:
                    break;
                }
              }
            }
          }
                 
        
        	// schema.org
        	/*
          for (var j in doc.message[i]) {
            if (doc.message[i][j].type == 'StillImage') {
              var media_id = doc.message[i][j].identifier;

              // link to data
              triples.push(triple(media_id,
                'http://schema.org/about',
                subject_id));


              triples.push(triple(media_id,
                'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
                'http://schema.org/ImageObject'));

              for (var k in doc.message[i][j]) {
                switch (k) {
                  case 'format':
                    triples.push(triple(media_id,
                      'http://schema.org/fileFormat',
                      doc.message[i][j][k]));
                    break;

                  case 'title':
                    triples.push(triple(media_id,
                      'http://schema.org/caption',
                      doc.message[i][j][k]));
                    break;

                  case 'description':
                    triples.push(triple(media_id,
                      'http://schema.org/caption',
                      doc.message[i][j][k]));
                    break;

                  case 'references':
                    triples.push(triple(media_id,
                      'http://schema.org/about',
                      doc.message[i][j][k]));
                    break;

                  case 'identifier':
                    triples.push(triple(media_id,
                      'http://schema.org/identifier',
                      doc.message[i][j][k]));
                    triples.push(triple(media_id,
                      'http://schema.org/contentUrl',
                      doc.message[i][j][k]));
                    break;

                  case 'creator':
                    triples.push(triple(media_id,
                      'http://schema.org/creator',
                      doc.message[i][j][k]));
                    break;

                  case 'license':
                    triples.push(triple(media_id,
                      'http://schema.org/license',
                      doc.message[i][j][k]));
                    break;


                  default:
                    break;
                }
              }
            }
          }
          */
          break;  
          
          // Darwin Core Identification----------------------------------------------------------
        case "kingdom":
        case "phylum":
        case "order":
        case "class":
        case "family":
        case "genus":
        case "species":
        case 'scientificName': // convenience
        case 'identificationQualifier':
        case 'typeStatus':
        case 'identifiedBy':
        case 'dateIdentified':
        case 'identificationReferences':
        case 'identificationVerificationStatus':
        case 'identificationRemarks':
        case 'taxonID':
        case 'identificationID': 
          triples.push(triple(subject_id,
            'http://rs.tdwg.org/dwc/terms/' + i,
            String(doc.message[i])));
          break;                  	  
		  
			default:
				break;
       		}
    	}
	}
		    

                
    output(doc, triples);

}

function (doc) {
   if (doc._id.match(/gbif.org/)) {
	message(doc);
   }
}
// END COUCHDB VIEW

