{
  "_id": "_design/export",
  "_rev": "1-5aad2ebcb373796c3177d7b2c415b8b6",
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