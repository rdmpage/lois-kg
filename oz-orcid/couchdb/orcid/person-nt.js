/*

Shared code


*/
//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/25715455
function isObject(item) {
  return (typeof item === "object" && !Array.isArray(item) && item !== null);
}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/21445415
function uniques(arr) {
  var a = [];
  for (var i = 0, l = arr.length; i < l; i++)
    if (a.indexOf(arr[i]) === -1 && arr[i] !== '')
      a.push(arr[i]);
  return a;
}


//----------------------------------------------------------------------------------------
// Store a triple with optional language code
function triple(subject, predicate, object, language) {
  var triple = [];
  triple[0] = subject;
  triple[1] = predicate;
  triple[2] = object;

  if (typeof language === 'undefined') {}
  else {
    triple[3] = language;
  }

  return triple;
}

//----------------------------------------------------------------------------------------
// Enclose triple in suitable wrapping for HTML display or triplet output
function wrap(s, html) {
  if (s) {

    if (s.match(/^(http|urn|_:)/)) {
      s = s.replace(/\\_/g, '_');

      // handle < > in URIs such as SICI-based DOIs
      s = s.replace(/</g, '%3C');
      s = s.replace(/>/g, '%3E');

      if (html) {
        s = '&lt;' + s + '&gt;';
      }
      else {
        s = '<' + s + '>';
      }
    }
    else {
      s = '"' + s.replace(/"/g, '\\"') + '"';
    }
  }
  return s;
}

//----------------------------------------------------------------------------------------
// https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

//----------------------------------------------------------------------------------------
function detect_language(s) {
  var language = null;
  var matched = 0;
  var parts = [];

  var regexp = [];

  // https://gist.github.com/ryanmcgrath/982242
  regexp['ja'] = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  // http://hjzhao.blogspot.co.uk/2015/09/javascript-detect-chinese-character.html
  regexp['zh'] = /[\u4E00-\uFA29]/g;
  // http://stackoverflow.com/questions/32709687/js-check-if-string-contains-only-cyrillic-symbols-and-spaces
  regexp['ru'] = /[\u0400-\u04FF]/g;

  for (var i in regexp) {
    parts = s.match(regexp[i]);

    if (parts != null) {
      if (parts.length > matched) {
        language = i;
        matched = parts.length;
      }
    }
  }

  // require a minimum matching
  if (matched < 2) {
    language = null;
  }

  return language;

}

//----------------------------------------------------------------------------------------
function output(doc, triples) {
  // CouchDB
  for (var i in triples) {
    var s = 0;
    var p = 1;
    var o = 2;

    var lang = 3;

    var nquads = wrap(triples[i][s], false) +
      ' ' + wrap(triples[i][p], false) +
      ' ' + wrap(triples[i][o], false);
    if (triples[i][lang]) {
      nquads += '@' + triples[i][lang];
    }

    nquads += ' .' + "\n";

    // use cluster_id as the key so triples from different versions are linked together
    emit(doc._id, nquads);
    //console.log(nquads);
  }
}

//----------------------------------------------------------------------------------------
// START COUCHDB VIEW
function message(doc) {
  if (doc.message) {

    var triples = [];

    var subject_id = doc.message['orcid-identifier'].uri;

    triples.push(triple(subject_id,
      'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
      'http://schema.org/Person'));


    var names = [];

    if (doc.message.person.name['given-names']) {
      triples.push(triple(subject_id,
        'http://schema.org/givenName',
        doc.message.person.name['given-names'].value));

      names.push(doc.message.person.name['given-names'].value);
    }

    if (doc.message.person.name['family-name']) {
      triples.push(triple(subject_id,
        'http://schema.org/familyName',
        doc.message.person.name['family-name'].value));

      names.push(doc.message.person.name['family-name'].value);
    }

    if (names.length > 0) {
      triples.push(triple(subject_id,
        'http://schema.org/name',
        names.join(' ')));
    }

    // other vaersions of the name

    var alternateName = [];

    if (doc.message.person['other-names']['other-name']) {
      for (var i in doc.message.person['other-names']['other-name']) {
        alternateName.push(doc.message.person['other-names']['other-name'][i].content);
      }
    }

    alternateName = uniques(alternateName);

    for (var i in alternateName) {
      triples.push(triple(subject_id,
        'http://schema.org/alternateName',
        alternateName[i]));
    }



    // identifiers

    var identifier_id = subject_id + '#orcid';
    var orcid = subject_id.replace(/https?:\/\/orcid.org\//, '');

    triples.push(triple(
      subject_id,
      'http://schema.org/identifier',
      identifier_id));

    triples.push(triple(
      identifier_id,
      'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
      'http://schema.org/PropertyValue'));

    triples.push(triple(
      identifier_id,
      'http://schema.org/propertyID',
      'orcid'));

    triples.push(triple(
      identifier_id,
      'http://schema.org/value',
      orcid
    ));



    output(doc, triples);

  }

}

function(doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/orcid+json') {
      message(doc);
    }
  }
}
// END COUCHDB VIEW