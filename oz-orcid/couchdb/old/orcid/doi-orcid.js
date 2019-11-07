function(doc) {
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