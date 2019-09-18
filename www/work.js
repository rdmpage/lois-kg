var template_work = `

<%

// Define functions in template, see https://stackoverflow.com/a/40968695/9684

//----------------------------------------------------------------------------------------
// Get a single string from a literal that may have multilingual values
get_literal = function(key) {
	var literal = '';
	
	console.log("get_literal key = " + JSON.stringify(key));
	
	// literal is a simple string
	if (typeof key === 'string') {
		literal = key;
	}
	
	// name is an object so we have one language stored in @value
	if (typeof key === 'object' && !Array.isArray(key)) {	
		literal = key['@value'];
	} else {
		// name is an array of objects and/or strings (need to think about this)
		if (Array.isArray(key)) {
		
		    // just take first
			// literal = key[0]['@value'];
			
			var strings = [];
			for (var i in key) {
				strings.push(key[i]['@value']);
			}
			literal = strings.join(' / ');			
		}
	}
	
	return literal;
}

//----------------------------------------------------------------------------------------
// Convert ISO data to a human-readable string (PubMed-style)
// My databases use -00 to indicate no month or no day, and this confuses Javascript
// Date so we need to set the options appropriately
isodate_to_string = function (datestring) {

	// By default assume datestring is a year only
	var options = {};
	options.year = 'numeric';
	
	// Test for valid month, then day (because we use -00 to indicate no data)
	var m = null;
	
	if (!m) {	
		m = datestring.match(/^([0-9]{4})$/);
		if (m) {
			// year only
			datestring = m[1]; 
		}
	}
	
	if (!m) {		
		m = datestring.match(/^([0-9]{4})-([0-9]{2})-00/);
		if (m) {
			
			if (m[2] == '00') {
				// Javascript can't handle -00-00 date string so set to January 1st 
				// which won't be output as we're only outputting the year
				datestring = m[1] + '-01-01';
			} else {
				// We have a month but no day
				datestring = m[1] + '-' + m[2] + '-01';
				options.month = 'short';
			}		
		}
	}
	
	if (!m) {	
		m = datestring.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2})/);
		if (m) {
			// we have yea, month, and day
			options.month = 'short';
			options.day = 'numeric';
		}
	}
	
	var d = new Date(datestring);
	datestring = d.toLocaleString('en-gb', options);
	
	return datestring;		
}

//----------------------------------------------------------------------------------------

get_property_value = function(key, propertyID) {
	var value = '';
	
	if (typeof key === 'object' && !Array.isArray(key)) {
		if (key.propertyID === propertyID) {	
			value = key.value;
		}
	} else {
		if (Array.isArray(key)) {
			for (var i in key) {
				if (key[i].propertyID === propertyID) {
					value = key[i].value;
				}
			}
		}	
	}
	
	return value;
}


if (data['@graph']) {
	data = data['@graph'][0];
}

var item = null;

item = data;


%>

<!-- location -->
<div>

<!-- container -->
<% if (item.isPartOf) {%>
	<%= get_literal(item.isPartOf.name) %>
<% } %>

<!-- date -->
<% if (item.datePublished) {%>
<%= isodate_to_string(item.datePublished) %>
<% } %>

<!-- volume -->
<% if (item.volumeNumber) {%>
<%= get_literal(item.volumeNumber) %>
<% } %>

<!-- issue -->
<% if (item.issueNumber) {%>
(<%= get_literal(item.issueNumber) %>)
<% } %>

<!-- pages -->
<% if (item.pagination) {%>
: <%= get_literal(item.pagination) %>
<% } %>

<% if (item.pageStart) {%>
: <%=get_literal(item.pageStart)%>
<% } %>

<% if (item.pageEnd) {%>
- <%=get_literal(item.pageEnd) %>
<% } %>

</div>

<!-- title -->
<h1>
	<%= get_literal(item.name) %>
</h1>

<!-- authors -->
<div>

<% if (item.creator) {
	var authors = [];
		for (var i in item.creator) {
		
			console.log(i + ' ' + item.creator[i]['@type']);
		
			console.log("creator=" + JSON.stringify(item.creator[i]));
		
			// role
			if (item.creator[i]['@type'] == 'Role') { 
			
				var string ='';
				
			    if (item.creator[i].creator[0].identifier) {
			    	var orcid = get_property_value(item.creator[i].creator[0].identifier, 'orcid');
			    	if (orcid != '') {
			    		string += '<a href="https://orcid.org/' + orcid + '"><img src="images/orcid_16x16.png"></a>&nbsp;';
			    	}
			    }
			    		
				string += get_literal(item.creator[i].creator[0].name);
				authors.push(string);
			}

			// person
			if (item.creator[i]['@type'] == 'Person') { 
			    var string = get_literal(item.creator[i].name);
				authors.push(string);
			}
		
		}
		var author_string = authors.join('; ');
		%>
		<%- author_string %>
		<%  
		
		
} %>
</div>

<!-- keywords -->
<div>

<% if (item.keywords) {

	var keywords_string = '';
	
	if (typeof item.keywords === 'string') {
		keywords_string = item.keywords ;
	}
	
	if (Array.isArray(item.keywords )) {
		var keywords = [];
		for (var i in item.keywords) {
			keywords.push(item.keywords[i]);
		}
		keywords_string = keywords.join('; ');
	}
	%>
	
	<span class="heading">KEYWORDS:</span>
	<%- keywords_string %>
	<%  
} %>
</div>


<!-- identifiers -->
<div>
<% if (item.identifier) {
	 var id = '';
	 
	// DOI
	id = get_property_value(item.identifier, 'doi');	  
	if (id != '') {  %>	
		DOI:
		<a href="https://doi.org/<%=id%>">
		<%= id %>
		</a>
	<% }
	
	// Handle
	id = get_property_value(item.identifier, 'handle');	  
	if (id != '') {  %>	
		Handle:
		<a href="https://hdl.handle.net/<%=id%>">
		<%= id %>
		</a>
	<% }
	
	
	// PMID
	id = get_property_value(item.identifier, 'pmid');	  
	if (id != '') {  %>	
		PMID:
		<a href="https://www.ncbi.nlm.nih.gov/pubmed/<%=id%>">
		<%= id %>
		</a>
	<% }
	
	}
 %>	
 </div>	
 
<!-- abstract -->
<div>

<% if (item.description) { %>	
	<span class="heading">Abstract</span>
	<%- get_literal(item.description) %>
<%} %>
</div> 




`;