function(doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {

      var csl = doc.message;

      if (csl['container-title']) {
        var title = '';
        if (Array.isArray(csl['container-title'])) {
          title = csl['container-title'][0];
        }
        else {
          title = csl['container-title'];
        }

        var issn = '';

        if (csl['issn-type']) {
          for (var i in csl['issn-type']) {
            if (csl['issn-type'][i].type == "print") {
              issn = csl['issn-type'][i].value;
            }
          }
        }

        if (issn == '') {
          if (csl.ISSN) {
            issn = csl.ISSN[0];
          }
        }

        if (issn != '') {
          emit([title, issn], 1);

        }
      }
    }
  }
}
