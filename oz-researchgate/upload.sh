#!/bin/sh

# People
curl http://localhost:5984/rg/_design/test/_list/triples/people-nt > people.nt

curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://researchgate.net -H 'Content-Type: text/rdf+n3' --data-binary '@people.nt'  --progress-bar | tee /dev/null


# Works
curl http://localhost:5984/rg/_design/test/_list/triples/works-nt > works.nt

curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://researchgate.net -H 'Content-Type: text/rdf+n3' --data-binary '@works.nt'  --progress-bar | tee /dev/null

