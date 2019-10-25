#!/bin/sh

echo 'glue-0.nt'
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@glue-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
