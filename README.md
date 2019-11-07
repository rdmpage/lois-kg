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
SELECT CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <https://doi.org/', LOWER(REPLACE(REPLACE (doi, '<', '%3C'), '>', '%3E')), '> . ') 
FROM names 
WHERE doi IS NOT NULL;
```

```
SELECT CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <https://www.jstor.org/stable/', jstor, '> . ') 
FROM names 
WHERE jstor IS NOT NULL AND doi IS NULL;
```

```
curl http://167.99.58.120:9999/blazegraph/sparql?context-uri=https://bionames.org -H 'Content-Type: text/rdf+n3' --data-binary '@glue.nt'  --progress-bar | tee /dev/null
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

If completely dead (e.g., DigitalOcean restarts the droplet) we may have to recreate from scratch, which means removing kg from docker. This will remove local reference.

#### Removing existing docker machine

```
docker-machine rm -f kg

```

To remove remote kg on DigitalOcean we need to be authenticated:

```
curl -X DELETE "https://api.digitalocean.com/v2/account/keys/MY_DROPLET_ID" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer MY_API_TOKEN"  
```

You can get the DigitalOcean URL when running ```docker-machine rm -f kg``` as it will complain that you aren’t authenticated.

#### Reinstalling containers

```
eval $(docker-machine env kg)
docker run -d -p 9999:9999 openkbs/blazegraph
docker run -p 5984:5984 -d couchdb
```

### Interface ideas

See Springer

https://scigraph.springernature.com/pub.10.1038/116684a0

Visualisation by https://github.com/visjs/vis-network

#### Validation using Google

https://search.google.com/structured-data/testing-tool/u/0/#url=http%3A%2F%2Fscigraph.springernature.com%2Fpub.10.1038%2F116684a0.json.schema

#### JSON-LD

https://json-ld.org/playground/#startTab=tab-expanded&json-ld=http://scigraph.springernature.com/pub.10.1038/116684a0.json

#### Citations

http://opencitations.net/index/coci/api/v1/citations/10.1038/116684a0

#### Vis

```
  // create an array with nodes

  var nodeData=[
    
        { id: "schema:ScholarlyArticle",
          label: "schema:ScholarlyArticle",
          uri : "http://schema.org/ScholarlyArticle",
                  
        
          group: "text"
        
        },
    
        { id: "rdf:type",
          label: "rdf:type",
          uri : "http://www.w3.org/1999/02/22-rdf-syntax-ns*hash*type",
                  
        
          group: "predicate_uri",
          color:{border:'#4ae54a'},
        
        },
    
        { id: "sg:journal.1018957",
          label: "Nature",
          uri : "http://scigraph.springernature.com/journal.1018957",
                  
        
          group: "journal"
        
        },
    
        { id: "https://doi.org/10.1038/116684a0",
          label: "https://doi.org/10.1038/116684a0",
          uri : "https://doi.org/10.1038/116684a0",
                  
        
          group: "text"
        
        },
    
        { id: "anzsrc\u002Dfor:2103",
          label: "Historical Studies",
          uri : "http://purl.org/au-research/vocabulary/anzsrc-for/2008/2103",
                  
        
          group: "concept"
        
        },
    
        { id: "anzsrc\u002Dfor:21",
          label: "History and Archaeology",
          uri : "http://purl.org/au-research/vocabulary/anzsrc-for/2008/21",
                  
        
          group: "concept"
        
        },
    
        { id: "schema:isPartOf",
          label: "schema:isPartOf",
          uri : "http://schema.org/isPartOf",
                  
        
          group: "predicate_uri",
          color:{border:'#9E9E9E'},
        
        },
    
        { id: "schema:sameAs",
          label: "schema:sameAs",
          uri : "http://schema.org/sameAs",
                  
        
          group: "predicate_uri",
          color:{border:'#9E9E9E'},
        
        },
    
        { id: "schema:about",
          label: "schema:about",
          uri : "http://schema.org/about",
                  
        
          group: "predicate_uri",
          color:{border:'#9E9E9E'},
        
        },
    
        { id: "sg:pub.10.1038/116684a0",
          label: "Mr. J. S. Gamble, C.I.E., F.R.S",
          uri : "http://scigraph.springernature.com/pub.10.1038/116684a0",
        
          icon: { size: 110, color: 'grey'},
                  
        
          group: "article"
        
        },
    
        { id: "https://app.dimensions.ai/details/publication/pub.1028763515",
          label: "https://app.dimensions.ai/details/publication/pub.1028763515",
          uri : "https://app.dimensions.ai/details/publication/pub.1028763515",
                  
        
          group: "text"
        
        },
    
  ]
  // console.log(nodeData)


  // create an array with edges
  var edgeData=[
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "schema:about"
        },
        {  from: "schema:about",
            to: "anzsrc\u002Dfor:21"
          },
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "schema:about"
        },
        {  from: "schema:about",
            to: "anzsrc\u002Dfor:2103"
          },
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "schema:isPartOf"
        },
        {  from: "schema:isPartOf",
            to: "sg:journal.1018957"
          },
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "schema:sameAs"
        },
        {  from: "schema:sameAs",
            to: "https://app.dimensions.ai/details/publication/pub.1028763515"
          },
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "schema:sameAs"
        },
        {  from: "schema:sameAs",
            to: "https://doi.org/10.1038/116684a0"
          },
    
        {  from: "sg:pub.10.1038/116684a0",
          to: "rdf:type"
        },
        {  from: "rdf:type",
            to: "schema:ScholarlyArticle"
          },
    
    ]
  // console.log(edgeData)


  var nodes = new vis.DataSet(nodeData);
  var edges = new vis.DataSet(edgeData);
  

  var options = {
    autoResize: true,
    clickToUse: false,
    physics:{
      barnesHut:{gravitationalConstant:-30000},
      stabilization: {iterations:2500}
    },
    interaction:{
        hover: true,
        navigationButtons: true,
        zoomView: false
      },
    nodes: {
        shape: 'dot',
        size: 30,
        font: {
            size: 15,
            color: 'black'
        },
        borderWidth: 2,
    },
    edges: {
      color: 'gray',
      smooth: false,
      length : 150,
      arrows: {
          to:     {enabled: true, scaleFactor:0.2, type:'arrow'},
          middle: {enabled: false, scaleFactor:1, type:'arrow'},
          from:   {enabled: false, scaleFactor:1, type:'arrow'}
        },
    },
    groups: {
        primary_uri: {
            color: {
              border:'#325D94',
              background: 'lightyellow',
              highlight: {
                  border:'#325D94',
                  background: '#FFF59D'
              },
              hover: {
                  border:'#325D94',
                  background: '#FFF59D'
              },
            },
            size: 60,
            font: {
                size: 18,
                color: 'black'
            },
        },          
        predicate_uri: {
          shape: 'box',
          color: {
            background: 'white',
            border: 'lightgreen',  // overridden above
            highlight: {
              background: 'white',
              border: 'red'
            },
            hover: {
              background: 'white',
              border: '#F44336'
            },
          },
        },
        ontology: {
          // not used
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf1db',
            size: 50,
            color: 'grey'
          }
        },
        organization: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf1ad',
            size: 50,
            color: '#667eb0'
          }
        },
        person: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf007',
            size: 80,
            color: '#57169a'
          }
        },
        concept: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf0eb',
            size: 50,
            color: '#19b3ee'
          }
        }, 
        concept_scheme: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf187',
            size: 80,
            color: '#2589b0'
          }
        },        
        article: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf1ea',
            size: 80,
            color: '#2589b0'
          }
        },
        journal: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf115',
            size: 80,
            color: '#2589b0'
          }
        },
        book: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf02d',
            size: 60,
            color: '#2589b0'
          }
        }, 
        grant: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf0c3',
            size: 40,
            color: 'darkblue'
          }
        },        
        conference: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf19d',
            size: 60,
            color: '#94190c'
          }
        },         
        metrics: {
          shape: 'icon',
          icon: {
            face: 'FontAwesome',
            code: '\uf080',
            size: 40,
            color: '#b09390'
          }
        }, 
        text: {
          shape: 'text',
          font: {
            color: '#343434',
          },
        }
    }
  }





    // NORMALLY this is how you render the viz (done via tabs click)
    //render_viz('mynetwork', nodeData, edgeData);
 
```



