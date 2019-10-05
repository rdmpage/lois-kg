{
  "_id": "_design/citation-container",
  "_rev": "2-28776dd5f99ea6e6dbcf9554f087efd8",
  "views": {
    "journal-title": {
      "map": "function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n\n\t  var csl = doc.message;\n  \n\t\tif (csl.reference) {\n\t\t  for (var i in csl.reference) {\n\t\t\tif (csl.reference[i]['journal-title']) {\n\t\t\t  emit(csl.reference[i]['journal-title'], 1);\n\t\t\t}\n\t\t  }\n\t\t\n\t  }\n\t}\t\n  }\n}\n\n",
      "reduce": "_sum"
    }
  },
  "language": "javascript"
}