{
  "_id": "_design/cleaning",
  "_rev": "5-d2739b969feebd5a4059b9f99326d674",
  "views": {
    "html-entities": {
      "map": "function (doc) {\n  if (doc['message-format']) {\n    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {\n       if (doc.message.title) {\n         var title = doc.message.title;\n         if (Array.isArray(title)) {\n           title = title[0];\n         }\n         var m = null;\n         m = title.match(/(\\^\\|\\^[a-z]+);/ig);\n         if (m) {\n           for (var i in m) {\n              emit(m[i], 1);\n           }\n         }\n         m = title.match(/(&[a-z]+);/ig);\n         if (m) {\n           for (var i in m) {\n              emit(m[i], 1);\n           }\n         }\n         \n       }\n    }\n  }\n}",
      "reduce": "_sum"
    }
  },
  "language": "javascript"
}