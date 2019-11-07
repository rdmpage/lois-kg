{
  "_id": "_design/filters",
  "_rev": "6-b0532dff6356fdb1b87e522ec83cc9b8",
  "filters": {
    "people": "function(doc, req) { if (doc._deleted) { return true; } else { if(doc._id.match('/work/')) { return false; } else { return true; } } }",
    "works": "function(doc, req) { if (doc._deleted) { return true; } else { if(doc._id.match('/work/')) { return true; } else { return false; } } }"
  }
}