# Lots Of Interesting Stuff Knowledge Graph

Building a knowledge graph of taxonomic data.



## Setup

```
composer init
echo "vendor/" > .gitignore
composer update
```

## Technology

Technology | Provider
-- | --
Document database | CouchDB
Triple store | Blazegraph
Templating | EJS

## Named graphs

Source | Graph URI
--|--
IPNI | https://www.ipni.org
ORCID | https://orcid.org

## Applications

## Sources

### IPNI

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni.nt'  --progress-bar | tee /dev/null
```

### IndexFungorum

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=http://indexfungorum.org -H 'Content-Type: text/rdf+n3' --data-binary '@if.nt'  --progress-bar | tee /dev/null
```



## Applications


