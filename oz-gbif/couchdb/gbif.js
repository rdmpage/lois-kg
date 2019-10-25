{
  "_id": "_design/gbif",
  "_rev": "5-58c86b576b78bc986a52565cf7dcf4bc",
  "views": {
    "identifiedBy": {
      "reduce": "_sum",
      "map": "function (doc) {\n  if (doc.message.identifiedBy) {\n    emit(doc.message.identifiedBy, 1);\n  }\n}"
    },
    "recordedBy": {
      "reduce": "_sum",
      "map": "function (doc) {\n  if (doc.message.recordedBy) {\n    emit(doc.message.recordedBy, 1);\n  }\n}"
    },
    "typeStatus": {
      "reduce": "_sum",
      "map": "function (doc) {\n  if (doc.message.typeStatus) {\n    emit(doc.message.typeStatus, 1);\n  }\n}"
    }
  },
  "language": "javascript"
}