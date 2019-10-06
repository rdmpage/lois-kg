function(doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {
      // citations
      for (var i in doc.message.author) {
        if (doc.message.author[i].ORCID) {
          var name = '';
          if (doc.message.author[i].literal) {
            name = doc.message.author[i].literal;
          }
          else {
            name = doc.message.author[i].given + ' ' + doc.message.author[i].family;
          }
          emit(doc.message.DOI, [doc.message.author[i].ORCID.replace(/https?:\/\/orcid.org\//, ''), name, (parseInt(i) + 1)]);
        }
      }
    }

    if (doc['message-format'] == 'application/vnd.citationstyles.csl+json') {
      if (doc.message.DOI) {
        for (var i in doc.message.author) {
          if (doc.message.author[i].ORCID) {
            // ignore cases where author is actually all the authors
            var go = true;
            if (doc.message.author[i].literal.match(/ and /)) {
              go = false;
            }
            if (go) {
              emit(doc.message.DOI.toLowerCase(), [doc.message.author[i].ORCID, doc.message.author[i].literal, parseInt(i) + 1]);
            }
          }
        }
      }
    }

  }
}