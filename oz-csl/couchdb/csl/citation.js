function (doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {

	  var csl = doc.message;
  
	  if (csl.DOI) {
		if (csl.reference) {
		  for (var i in csl.reference) {
			if (csl.reference[i].DOI) {
			  emit(csl.reference[i].DOI, 1);
			}
		  }
		}
	  }
	}	
  }
}