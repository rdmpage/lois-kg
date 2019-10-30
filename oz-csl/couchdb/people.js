{
  "_id": "_design/people",
  "_rev": "2-3bee6d41f2c12675e219f20921fd3846",
  "views": {
    "orcid": {
      "map": "function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n     // citations\n     for (var i in doc.message.author) {\n       if (doc.message.author[i].ORCID) {\n          emit(doc.message.author[i].ORCID.replace(/https?:\\/\\/orcid.org\\//, ''), 1);\n       }    \n     }\n    }\n    \n  if (doc['message-format'] == 'application/vnd.citationstyles.csl+json') {\n    if (doc.message.DOI) {\n      for (var i in doc.message.author) {\n        if (doc.message.author[i].ORCID) {\n          // ignore cases where author is actually all the authors\n          var go = true;\n          if (doc.message.author[i].literal.match(/ and /)) {\n            go = false;\n          }\n          if (go) {\n            emit(doc.message.author[i].ORCID, 1);\n          }\n        }\n      }\n    }\n  }    \n    \n  }\n}",
      "reduce": "_sum"
    }
  },
  "language": "javascript"
}