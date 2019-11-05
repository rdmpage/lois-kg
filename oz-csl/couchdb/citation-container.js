{
  "_id": "_design/citation-container",
  "_rev": "3-30ddb507be4e877e9de3cac01e498790",
  "views": {
    "journal-title": {
      "map": "function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n\n\t  var csl = doc.message;\n  \n\t\tif (csl.reference) {\n\t\t  for (var i in csl.reference) {\n\t\t\tif (csl.reference[i]['journal-title']) {\n\t\t\t  emit(csl.reference[i]['journal-title'], 1);\n\t\t\t}\n\t\t  }\n\t\t\n\t  }\n\t}\t\n  }\n}\n\n",
      "reduce": "_sum"
    },
    "cited-issn": {
      "map": "function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n\n\t  var csl = doc.message;\n  \n\t\tif (csl.reference) {\n\t\t  for (var i in csl.reference) {\n\t\t\tif (csl.reference[i]['journal-title']) {\n\t\t\t\n\t\t\t// 1. Do we have ISSN?\n\t\t\tvar reference_issn = '';\n\n\t\t\tif (csl.reference[i].ISSN) {\n\t\t\t\treference_issn = csl.reference[i].ISSN;\n\t\t\t\treference_issn = reference_issn.replace('http://id.crossref.org/issn/', ''); \n\t\t\t\temit(reference_issn, csl.reference[i]['journal-title']);               \t\t\n\t\t\t}\n\t\t\t\n\t\t\t  \n\t\t\t}\n\t\t  }\n\t\t\n\t  }\n\t}\t\n  }\n}\n\n"
    }
  },
  "language": "javascript"
}