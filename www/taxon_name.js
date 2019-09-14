var template_taxon_name = `

<%

// Define functions in template, see https://stackoverflow.com/a/40968695/9684

//----------------------------------------------------------------------------------------
// Get a single string from a literal that may have multilingual values
get_literal = function(key) {
	var literal = '';
	
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

//----------------------------------------------------------------------------------------
// https://codepen.io/goker/pen/yBEGD

    var templates = {
        prefix: "",
        suffix: " ago",
        seconds: "less than a minute",
        minute: "about a minute",
        minutes: "%d minutes",
        hour: "about an hour",
        hours: "about %d hours",
        day: "a day",
        days: "%d days",
        month: "about a month",
        months: "%d months",
        year: "about a year",
        years: "%d years"
    };
    var template = function (t, n) {
        return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
    };

    var timer = function (time) {
        if (!time) return;
               
        time = time.replace(/\\.\\d+/, ""); // remove milliseconds
        time = time.replace(/-/, "/").replace(/-/, "/");
        time = time.replace(/T/, " ").replace(/Z/, " UTC");
        time = time.replace(/([\\+\\-]\\d\\d)\\:?(\\d\\d)/, " $1$2"); // -04:00 -> -0400        
        
        time = new Date(time * 1000 || time);
        
        var now = new Date();
        var seconds = ((now.getTime() - time) * .001) >> 0;
        var minutes = seconds / 60;
        var hours = minutes / 60;
        var days = hours / 24;
        var years = days / 365;

        return templates.prefix + (
        seconds < 45 && template('seconds', seconds) || seconds < 90 && template('minute', 1) || minutes < 45 && template('minutes', minutes) || minutes < 90 && template('hour', 1) || hours < 24 && template('hours', hours) || hours < 42 && template('day', 1) || days < 30 && template('days', days) || days < 45 && template('month', 1) || days < 365 && template('months', days / 30) || years < 1.5 && template('year', 1) || template('years', years)) + templates.suffix;
    };




if (data['@graph']) {
	data = data['@graph'][0];
}

var item = null;

item = data;



%>

<!-- title -->
<h1>
	<%= get_literal(item['dc:title']) %>
</h1>


<!-- parsed taxonomic name -->
<div style="margin-top:0.5em;">
<% if (item['tn:uninomial']) {%>
	<%if (item['tn:rankString'] == 'gen.' || item['dwc:taxonRank'] == 'genus') {%>
		<span class="genusPart"><%=item['tn:uninomial']%></span>
	<%} else {%>	
		<%=item['tn:uninomial']%>
	<%}%>
<%	} else {
		if (item['tn:genusPart']) {%>
			<span class="genusPart"><%=item['tn:genusPart']%></span>
		<%}
		if (item['tn:infragenericEpithet']) {%>
		
			<%if (item['tn:rankString'] == 'sect.') {%>
				<span> sect. </span>
			<%}%>
		
			<span class="infragenericEpithet"><%=item['tn:infragenericEpithet']%></span>
		<%}
		if (item['tn:specificEpithet']) {%>
			<span class="specificEpithet"><%=item['tn:specificEpithet']%></span>
		<%}
		if (item['tn:infraspecificEpithet']) {%>
			<span class="infraspecificEpithet"><%=item['tn:infraspecificEpithet']%></span>
		<%}		
	}%>
	
<% if (item['tn:authorship']) {%>	
	<%=item['tn:authorship']%>
<%}%>
	
</div>

<!-- publishedIn -->
<div>
	<% if (item['tcom:publishedIn']) {  %>
		Published in:
		<%= get_literal(item['tcom:publishedIn']) %>

		<% if (item['tcom:microreference']) { %>
		(see page <%= get_literal(item['tcom:microreference']) %> )
		<% } %>


		<%- get_literal(item['dwc:namePublishedIn']) %>
	
		<% if (item['nmbe:publishedOnPage']) { %>
		(see page <%= get_literal(item['nmbe:publishedOnPage']) %> )
		<% } %>
	<% } %>
</div>

<!-- publishedInCitation -->
<!-- need to handle native IF and my augmented versions -->
<div>
	<% if (item['tcom:publishedInCitation']) {  %>
		Published in:
		<%
		// Index Fungorum
		if (item['tcom:publishedInCitation']['@type'] 
			&& item['tcom:publishedInCitation']['@type'] == 'tpc:PublicationCitation') {
				var parts = [];
				
				if (item['tcom:publishedInCitation']['tpc:title']) {
					parts.push (item['tcom:publishedInCitation']['tpc:title']);
				}

				if (item['tcom:publishedInCitation']['tpc:volume']) {
					parts.push (item['tcom:publishedInCitation']['tpc:volume']);
				}

				if (item['tcom:publishedInCitation']['tpc:number']) {
					parts.push (item['tcom:publishedInCitation']['tpc:number']);
				}

				if (item['tcom:publishedInCitation']['tpc:pages']) {
					parts.push (item['tcom:publishedInCitation']['tpc:pages']);
				}

				if (item['tcom:publishedInCitation']['tpc:year']) {
					parts.push (item['tcom:publishedInCitation']['tpc:year']);
				}
				
				if (parts.length > 0) {
					var citation = parts.join(' '); %>
					<%= citation %>
				<% }			
		}
	} %>
</div>

<!-- identifiers -->
<div>

		LSID:
		<a href="http://www.lsid.info/<%=item['@id']%>">
		<%= item['@id'] %>
		</a>
	
	
</div>

<!-- code -->
<div>

	<% if (item['dwc:nomenclaturalCode']) { %>
		Code:
		<%= get_literal(item['dwc:nomenclaturalCode']) %>
	<% } %>

	<% if (item['tn:nomenclaturalCode']) { 
		var code = item['tn:nomenclaturalCode']['@id'];
		code = code.replace('tn:', ''); %>
		Code:
		<%= code %>
	<% } %>
	
	
</div>

<!-- basionym -->
<div>
		<% if (item['tn:hasBasionym']) { %>
			Basionym:
			<a href="?uri=<%= item['tn:hasBasionym']['@id'] %>">
			<%= item['tn:hasBasionym']['dc:title'] %>
			</a>
		<%} %>
</div>			

</div>

<!-- types -->
<div>

		<% if (item['tn:typifiedBy']) { %>
			Type(s):
			<ul>
			<% for (var i in item['tn:typifiedBy']) { %>
			
				<!-- typeName is id of name (e.g., species) that is type of higher taxon -->
				<% if (item['tn:typifiedBy'][i]['tn:typeName']) { %>
					<a href="?uri=<%= item['tn:typifiedBy'][i]['tn:typeName']['@id'] %>">
				<% } %>
			
				<li><%= item['tn:typifiedBy'][i]['dc:title'] %></li>
				
				<% if (item['tn:typifiedBy'][i]['tn:typeName']) { %>
					</a>
				<% } %>
				
			<% } %>
			</ul>
		<%} %>
</div>



<!-- source -->
<div>

	<% if (item['tc:hasInformation']) { %>
	More information:
	<a href="<%= item['tc:hasInformation']['@id'] %>">
	<%= item['tc:hasInformation']['@id'] %>
	</a>
	<% } %>
</div>

<!-- record info -->
<div>
<% if (item['dcterms:modified']) {%>
	Record last updated <%= timer(item['dcterms:modified']) %>
<%}%>
</div>


`;
