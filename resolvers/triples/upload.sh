#!/bin/sh

echo 'ipni-37000000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-37000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-37500000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-37500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-38000000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-38000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-38500000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-38500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-39000000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-39000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-39500000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-39500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-40000000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-40000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
echo 'ipni-40500000.nt'
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni-40500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 30
