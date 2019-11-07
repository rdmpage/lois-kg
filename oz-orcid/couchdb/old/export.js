{
  "_id": "_design/export",
  "_rev": "13-3c4c7e2ebf7ceb4126ad176338abe832",
  "language": "javascript",
  "lists": {
    "values": "function(head,req) { var row; start({ 'headers': { 'Content-Type': 'text/plain' } }); while(row = getRow()) { send(JSON.stringify(row.value) + '\\n'); } }"
  },
  "views": {
    "jsonl": {
      "map": "function(doc) {\n  emit(doc._id, doc);\n}"
    }
  }
}