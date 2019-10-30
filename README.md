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
CrossRef | https://crossref.org

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
SELECT CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <https://doi.org/', LOWER(doi), '> . ') 
FROM names 
WHERE doi IS NOT NULL;
```

```
SELECT CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <https://www.jstor.org/stable/', jstor, '> . ') 
FROM names 
WHERE jstor IS NOT NULL AND doi IS NULL;
```

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@glue.nt'  --progress-bar | tee /dev/null
```

### The Plant List

```
curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=http://theplantlist.org -H 'Content-Type: text/rdf+n3' --data-binary '@theplantlist.nt'  --progress-bar | tee /dev/null
```


## DigitalOcean

```
docker-machine create --digitalocean-size "s-2vcpu-4gb" --driver digitalocean --digitalocean-access-token xxx kg
```

### May need bigger droplet
```
docker-machine create --digitalocean-size "s-4vcpu-8gb" --driver digitalocean --digitalocean-access-token xxxxx kg
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


