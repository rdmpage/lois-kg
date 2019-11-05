{
  "_id": "_design/cleaning",
  "_rev": "5-862f16565328f89c7c9641cd4020f4fd",
  "views": {
    "subject_id": {
      "map": "\n\n//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\nfunction message(doc) {\n    if (doc.message.DOI) {\n  \tvar doi = doc.message.DOI.toLowerCase();\n\t\t\n\t\t// clean up badness\n\t\t// e.g. 0000-0002-4662-0227/work/29262377\n\t\tdoi = doi.replace(/https?:\\/\\/(dx\\.)?doi.org\\/\\s*/, '');\n\t\tdoi = doi.replace(/doi:?\\s*/i, '');\n\t\tdoi = doi.replace(/\\[pii\\]/, '');\n\t\tdoi = doi.replace(/\\s+$/, '');\t\t\n\t\tdoi = doi.replace(/^\\s+/, '');\n\t\tdoi = doi.replace(/\\s/g, '');\n\t\t\n\t\tif (doi.match(/^http/)) {\n\t\t  doi = '';\n\t\t}\n\n    if (doi != '') {\n    \n    \t// use ORCID work id\n      var subject_id = 'https://orcid.org/' + doc._id;\n      \n      // use DOI\n      if (1) {\n        subject_id = 'https://doi.org/' + doi;\n      }\n      \n      emit(subject_id, 1);\n      \n \n    }\n  }\n}\n\n\nfunction (doc) {\n   if (doc['message-format'] == 'application/vnd.citationstyles.csl+json') {\n      message(doc);\n    }\n}\n// END COUCHDB VIEW",
      "reduce": "_sum"
    }
  },
  "language": "javascript"
}