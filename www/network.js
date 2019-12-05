


function short(uri) {
	var label = '';
	
	var m = null;
	m = uri.match(/(http:\/\/schema.org\/)(.*)/);
	if (m) {
		label = 'schema:' + m[2];
	}
	
	m = uri.match(/(http:\/\/rs.tdwg.org\/ontology\/voc\/TaxonName#)(.*)/);
	if (m) {
		label = 'tn:' + m[2];
	}	

	return label;
}

function show_network (container_id, uri) {

	$.getJSON('network.php?uri=' + encodeURIComponent(uri) + "&callback=?",
		function(data){


	var nodeData = [];
	var edgeData = [];


	var nodeList = {};
	var edgeList = {};

	nodeData.push( { id: data.results.bindings[0].source_id.value, label: data.results.bindings[0].source_name.value, group: "primary_uri"});


	for (var i in data.results.bindings) {
		edgeData.push( { from: data.results.bindings[i].source_id.value, to: short(data.results.bindings[i].edge_label.value)});
	
		edgeData.push( { from: short(data.results.bindings[i].edge_label.value), to: data.results.bindings[i].target_id.value});
		
		if (!nodeList[short(data.results.bindings[i].edge_label.value)]) {		
			nodeData.push( 
			{ 
				id: short(data.results.bindings[i].edge_label.value), 
				label: short(data.results.bindings[i].edge_label.value),
				
				group: "predicate_uri"
				});
			
			nodeList[short(data.results.bindings[i].edge_label.value)] = true;
		}
		
		
		if (!nodeList[data.results.bindings[i].target_id.value]) {		
			nodeData.push( { id: data.results.bindings[i].target_id.value, 
			label: data.results.bindings[i].target_name.value});
			
			nodeList[data.results.bindings[i].target_id.value] = true;
		}

	} 


  var nodes = new vis.DataSet(nodeData);
  var edges = new vis.DataSet(edgeData);
  
  
  var d = {
    nodes: nodes,
    edges: edges
  };  
  
  var options = {
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
            size: 30,
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
    }     
  
  
  };
  
  var container = document.getElementById(container_id);
  
  var network = new vis.Network(container, d, options);
  
}
);	
}

