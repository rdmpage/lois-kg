var template_person = `

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
	<%= get_literal(item.name) %>
	<%= get_literal(item['dc:title']) %>
</h1>

<!-- identifiers -->
<div>

		<span class="heading">Unique identifier</span>
		<a href="http://www.lsid.info/<%=item['@id']%>">
		<%= item['@id'] %>
		</a>
</div>

<!-- other names -->
<div>
	<% if (item['tp:alias']) {
		var other_names = [];
		for (var i in item['tp:alias']) {
			var parts = [];
			
			if (item['tp:alias'][i]['tp:forenames']) {
				parts.push(item['tp:alias'][i]['tp:forenames']);
			}
			if (item['tp:alias'][i]['tp:surname']) {
				parts.push(item['tp:alias'][i]['tp:surname']);
			} 
			
			var name = parts.join(' ');
			if (other_names.indexOf(name) === -1) {
				other_names.push(name);
			}
		} %>
		<%- other_names.join('; '); %>
		
	<% }%>

</div>

<!-- lifespan -->
<div>
	<span class="heading">Lifespan</span>
	<%= get_literal(item['tp:lifeSpan']) %>
</div>

<!-- subjectScope -->
<div>

<% if (item['tp:subjectScope']) {

	var keywords_string = '';
	
	if (typeof item.keywords === 'string') {
		keywords_string = item['tp:subjectScope'];
	}
	
	if (Array.isArray(item['tp:subjectScope'] )) {
		var keywords = [];
		for (var i in item['tp:subjectScope']) {
			keywords.push(item['tp:subjectScope'][i]);
		}
		keywords_string = keywords.join('; ');
	}
	%>
	
	<spanclass="heading">KEYWORDS:</span>
	<%- keywords_string %>
	<%  
} %>
</div>

<!-- record info -->
<div>
<% if (item['dcterms:modified']) {%>
	Record last updated <%= timer(item['dcterms:modified']) %>
<%}%>
</div>


`;