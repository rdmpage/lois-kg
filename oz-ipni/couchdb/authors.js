{"_id":"_design/authors","_rev":"8-83e2080717176ba108a6ed6e193fc9cd","lists":{"triples":"function(head,req) { var row; start({ 'headers': { 'Content-Type': 'text/plain' } }); while(row = getRow()) { send(row.value); } }","values":"function(head,req) { var row; start({ 'headers': { 'Content-Type': 'text/plain' } }); while(row = getRow()) { send(row.value); } }"},"views":{"nt":{"map":"/*\n\nShared code\n\n\n*/\n//----------------------------------------------------------------------------------------\n// http://stackoverflow.com/a/25715455\nfunction isObject(item) {\n    return (typeof item === \"object\" && !Array.isArray(item) && item !== null);\n}\n\n//----------------------------------------------------------------------------------------\n// http://stackoverflow.com/a/21445415\nfunction uniques(arr) {\n    var a = [];\n    for (var i = 0, l = arr.length; i < l; i++)\n        if (a.indexOf(arr[i]) === -1 && arr[i] !== '')\n            a.push(arr[i]);\n    return a;\n}\n\n\n//----------------------------------------------------------------------------------------\n// Store a triple with optional language code\nfunction triple(subject, predicate, object, language) {\n    var triple = [];\n    triple[0] = subject;\n    triple[1] = predicate;\n    triple[2] = object;\n\n    if (typeof language === 'undefined') {} else {\n        triple[3] = language;\n    }\n\n    return triple;\n}\n\n//----------------------------------------------------------------------------------------\n// Enclose triple in suitable wrapping for HTML display or triplet output\nfunction wrap(s, html) {\n    if (s) {\n\n        if (s.match(/^(http|urn|_:)/)) {\n            s = s.replace(/\\\\_/g, '_');\n\n            // handle < > in URIs such as SICI-based DOIs\n            s = s.replace(/</g, '%3C');\n            s = s.replace(/>/g, '%3E');\n\n            if (html) {\n                s = '&lt;' + s + '&gt;';\n            } else {\n                s = '<' + s + '>';\n            }\n        } else {\n          if (s.match(/^\".*\"$/)) {\n            // already quoted, but maybe not escaped\n            s = s.replace(/^\"/, '');\n            s = s.replace(/\"$/g, '');\n          }\n          s = '\"' + s.replace(/\"/g, '\\\\\"') + '\"';\n        }\n    }\n    return s;\n}\n\n//----------------------------------------------------------------------------------------\n// https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/\nfunction htmlEntities(str) {\n    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;');\n}\n\n//----------------------------------------------------------------------------------------\nfunction detect_language(s) {\n    var language = null;\n    var matched = 0;\n    var parts = [];\n\n    var regexp = [];\n\n    // https://gist.github.com/ryanmcgrath/982242\n    regexp['ja'] = /[\\u3000-\\u303F]|[\\u3040-\\u309F]|[\\u30A0-\\u30FF]|[\\uFF00-\\uFFEF]|[\\u4E00-\\u9FAF]|[\\u2605-\\u2606]|[\\u2190-\\u2195]|\\u203B/g;\n    // http://hjzhao.blogspot.co.uk/2015/09/javascript-detect-chinese-character.html\n    regexp['zh'] = /[\\u4E00-\\uFA29]/g;\n    // http://stackoverflow.com/questions/32709687/js-check-if-string-contains-only-cyrillic-symbols-and-spaces\n    regexp['ru'] = /[\\u0400-\\u04FF]/g;\n\n    for (var i in regexp) {\n        parts = s.match(regexp[i]);\n\n        if (parts != null) {\n            if (parts.length > matched) {\n                language = i;\n                matched = parts.length;\n            }\n        }\n    }\n\n    // require a minimum matching\n    if (matched < 2) {\n        language = null;\n    }\n\n    return language;\n\n}\n\n\n//----------------------------------------------------------------------------------------\nfunction output(doc, triples) {\n    // CouchDB\n    for (var i in triples) {\n        var s = 0;\n        var p = 1;\n        var o = 2;\n\n        var lang = 3;\n\n        var nquads = wrap(triples[i][s], false) +\n            ' ' + wrap(triples[i][p], false) +\n            ' ' + wrap(triples[i][o], false);\n        if (triples[i][lang]) {\n            nquads += '@' + triples[i][lang];\n        }\n\n        nquads += ' .' + \"\\n\";\n\n        // use cluster_id as the key so triples from different versions are linked together\n        emit(doc._id, nquads);\n        //console.log(nquads);\n    }\n}\n\n\n\n//----------------------------------------------------------------------------------------\n// START COUCHDB VIEW\nfunction message(doc) {\n  var subject_id = doc._id;\n\tvar triples = [];\n\t\n\t// type\n\ttriples.push(triple(subject_id,\n        'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',\n        'http://rs.tdwg.org/ontology/voc/Person#Person'));\n\t\n\tvar parts = [];\n\t\n\tif (doc.forename) {\n\t triples.push(triple(subject_id,\n\t\t'http://rs.tdwg.org/ontology/voc/Person#forenames',\n\t\tdoc.forename));\n\t\t\n\t\tparts.push(doc.forename);\n\t\t}\n\t\t\n\tif (doc.surname) {\n\t triples.push(triple(subject_id,\n\t\t'http://rs.tdwg.org/ontology/voc/Person#surname',\n\t\tdoc.surname));\n\t\t\n\t\tparts.push(doc.surname);\n\t\t}\n\t\t\n\t\tif (parts.length > 0) {\n\t\t  triples.push(triple(subject_id,\n\t\t  'http://purl.org/dc/elements/1.1/title',\n\t    \tparts.join(' ')));\n\t\t  \n\t\t}\n\t\t\n\tif (doc.standardForm) {\n\t triples.push(triple(subject_id,\n\t\t'http://rs.tdwg.org/ontology/voc/Person#standardForm',\n\t\tdoc.standardForm));\n\t\n\t\t}\t\t\n\n\n        output(doc, triples);\n\n\n}\n\nfunction(doc) {\n    if (doc.recordType == 'author') {\n        message(doc);\n     }\n}\n// END COUCHDB VIEW"}},"language":"javascript"}