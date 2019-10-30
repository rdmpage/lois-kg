function (doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {

	  var csl = doc.message;
  
		if (csl.reference) {
		  for (var i in csl.reference) {
			if (csl.reference[i]['journal-title']) {
			  emit(csl.reference[i]['journal-title'], 1);
			}
		  }
		
	  }
	}	
  }
}

