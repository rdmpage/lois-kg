function (doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {
       if (doc.message.title) {
         var title = doc.message.title;
         if (Array.isArray(title)) {
           title = title[0];
         }
         var m = null;
         m = title.match(/(\^\|\^[a-z]+);/ig);
         if (m) {
           for (var i in m) {
              emit(m[i], 1);
           }
         }
         m = title.match(/(&[a-z]+);/ig);
         if (m) {
           for (var i in m) {
              emit(m[i], 1);
           }
         }
         
       }
    }
  }
}