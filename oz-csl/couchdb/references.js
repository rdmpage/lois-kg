{
  "_id": "_design/references",
  "_rev": "3-585394ce894d8a2e6f33918322f38db3",
  "views": {
    "unstructured": {
      "map": "\n//----------------------------------------------------------------------------------------\nfunction (doc) {\n \n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n\n\t  var csl = doc.message;\n  \n\t\tif (csl.reference) {\n\t\t  for (var i in csl.reference)\n\t\t\t  if (csl.reference[i].unstructured) {\n\t        emit(doc._id, csl.reference[i].unstructured);\n\t\t    }\n\t\t}\n\t\t}\n  }\n}\n\n"
    }
  },
  "language": "javascript"
}