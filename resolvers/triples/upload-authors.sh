#!/bin/sh

echo 'a0.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'a1.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a1.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'a2.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a2.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'a3.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a3.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'a4.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@a4.nt'  --progress-bar | tee /dev/null

