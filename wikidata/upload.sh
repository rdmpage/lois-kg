#!/bin/sh

curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://wikidata.org -H 'Content-Type: text/rdf+n3' --data-binary '@wikidata.nt'  --progress-bar | tee /dev/null
