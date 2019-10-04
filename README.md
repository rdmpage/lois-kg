# Lots Of Interesting Stuff Knowledge Graph

Building a knowledge graph of taxonomic data.


## Issues

###

http://localhost/~rpage/lois-kg/www/?uri=https://doi.org/10.1640/0002-8444-103.1.21 has authors with ORCIDs, e.g.
http://localhost/~rpage/lois-kg/www/?uri=https://orcid.org/0000-0003-3891-9904, why don’t these appear when we show reference (check whether author order is same in CrossREf and ORCID).

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

### Belgium

http://www.botanicalcollections.be/specimen/BR0000027428382V/rdf

RDF has ORCID!

### IPNI

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://ipni.org -H 'Content-Type: text/rdf+n3' --data-binary '@ipni.nt'  --progress-bar | tee /dev/null
```

### IndexFungorum

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=http://indexfungorum.org -H 'Content-Type: text/rdf+n3' --data-binary '@if.nt'  --progress-bar | tee /dev/null
```

### Glue

Catchall demo examples, e.g. linking names to publications, etc.

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@glue.nt'  --progress-bar | tee /dev/null
```

### The Plant List

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=http://theplantlist.org -H 'Content-Type: text/rdf+n3' --data-binary '@theplantlist.nt'  --progress-bar | tee /dev/null
```


## DigitalOcean

```
docker-machine create --digitalocean-size "s-2vcpu-4gb" --driver digitalocean --digitalocean-access-token c26a347b6ba7f96e7378ca4736cbf9a2aadb86a0b0997ea4632547484e9476d9 kg
```

```
eval $(docker-machine env kg)
```
    
### Elasticsearch 
    
See https://www.elastic.co/guide/en/elasticsearch/reference/6.7/docker.html    
    
```
docker run -d -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" docker.elastic.co/elasticsearch/elasticsearch:6.7.2
```

### Blazegraph

```
docker run -d -p 9999:9999 openkbs/blazegraph
```
### CouchDB

```
docker run -p 5984:5984 -d couchdb
```

### If droplet crashes we need to start from scratch (data goes as well)

Sigh :(

```
eval $(docker-machine env kg)
docker run -d -p 9999:9999 openkbs/blazegraph
docker run -p 5984:5984 -d couchdb
```


