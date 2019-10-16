var template_taxon_name = `

<%

// Define functions in template, see https://stackoverflow.com/a/40968695/9684

//----------------------------------------------------------------------------------------
// https://stackoverflow.com/a/57963934/9684
function convertToAscii(string) {
    const unicodeToAsciiMap = {'Ⱥ':'A','Æ':'AE','Ꜻ':'AV','Ɓ':'B','Ƀ':'B','Ƃ':'B','Ƈ':'C','Ȼ':'C','Ɗ':'D','ǲ':'D','ǅ':'D','Đ':'D','Ƌ':'D','Ǆ':'DZ','Ɇ':'E','Ꝫ':'ET','Ƒ':'F','Ɠ':'G','Ǥ':'G','Ⱨ':'H','Ħ':'H','Ɨ':'I','Ꝺ':'D','Ꝼ':'F','Ᵹ':'G','Ꞃ':'R','Ꞅ':'S','Ꞇ':'T','Ꝭ':'IS','Ɉ':'J','Ⱪ':'K','Ꝃ':'K','Ƙ':'K','Ꝁ':'K','Ꝅ':'K','Ƚ':'L','Ⱡ':'L','Ꝉ':'L','Ŀ':'L','Ɫ':'L','ǈ':'L','Ł':'L','Ɱ':'M','Ɲ':'N','Ƞ':'N','ǋ':'N','Ꝋ':'O','Ꝍ':'O','Ɵ':'O','Ø':'O','Ƣ':'OI','Ɛ':'E','Ɔ':'O','Ȣ':'OU','Ꝓ':'P','Ƥ':'P','Ꝕ':'P','Ᵽ':'P','Ꝑ':'P','Ꝙ':'Q','Ꝗ':'Q','Ɍ':'R','Ɽ':'R','Ꜿ':'C','Ǝ':'E','Ⱦ':'T','Ƭ':'T','Ʈ':'T','Ŧ':'T','Ɐ':'A','Ꞁ':'L','Ɯ':'M','Ʌ':'V','Ꝟ':'V','Ʋ':'V','Ⱳ':'W','Ƴ':'Y','Ỿ':'Y','Ɏ':'Y','Ⱬ':'Z','Ȥ':'Z','Ƶ':'Z','Œ':'OE','ᴀ':'A','ᴁ':'AE','ʙ':'B','ᴃ':'B','ᴄ':'C','ᴅ':'D','ᴇ':'E','ꜰ':'F','ɢ':'G','ʛ':'G','ʜ':'H','ɪ':'I','ʁ':'R','ᴊ':'J','ᴋ':'K','ʟ':'L','ᴌ':'L','ᴍ':'M','ɴ':'N','ᴏ':'O','ɶ':'OE','ᴐ':'O','ᴕ':'OU','ᴘ':'P','ʀ':'R','ᴎ':'N','ᴙ':'R','ꜱ':'S','ᴛ':'T','ⱻ':'E','ᴚ':'R','ᴜ':'U','ᴠ':'V','ᴡ':'W','ʏ':'Y','ᴢ':'Z','ᶏ':'a','ẚ':'a','ⱥ':'a','æ':'ae','ꜻ':'av','ɓ':'b','ᵬ':'b','ᶀ':'b','ƀ':'b','ƃ':'b','ɵ':'o','ɕ':'c','ƈ':'c','ȼ':'c','ȡ':'d','ɗ':'d','ᶑ':'d','ᵭ':'d','ᶁ':'d','đ':'d','ɖ':'d','ƌ':'d','ı':'i','ȷ':'j','ɟ':'j','ʄ':'j','ǆ':'dz','ⱸ':'e','ᶒ':'e','ɇ':'e','ꝫ':'et','ƒ':'f','ᵮ':'f','ᶂ':'f','ɠ':'g','ᶃ':'g','ǥ':'g','ⱨ':'h','ɦ':'h','ħ':'h','ƕ':'hv','ᶖ':'i','ɨ':'i','ꝺ':'d','ꝼ':'f','ᵹ':'g','ꞃ':'r','ꞅ':'s','ꞇ':'t','ꝭ':'is','ʝ':'j','ɉ':'j','ⱪ':'k','ꝃ':'k','ƙ':'k','ᶄ':'k','ꝁ':'k','ꝅ':'k','ƚ':'l','ɬ':'l','ȴ':'l','ⱡ':'l','ꝉ':'l','ŀ':'l','ɫ':'l','ᶅ':'l','ɭ':'l','ł':'l','ſ':'s','ẜ':'s','ẝ':'s','ɱ':'m','ᵯ':'m','ᶆ':'m','ȵ':'n','ɲ':'n','ƞ':'n','ᵰ':'n','ᶇ':'n','ɳ':'n','ꝋ':'o','ꝍ':'o','ⱺ':'o','ø':'o','ƣ':'oi','ɛ':'e','ᶓ':'e','ɔ':'o','ᶗ':'o','ȣ':'ou','ꝓ':'p','ƥ':'p','ᵱ':'p','ᶈ':'p','ꝕ':'p','ᵽ':'p','ꝑ':'p','ꝙ':'q','ʠ':'q','ɋ':'q','ꝗ':'q','ɾ':'r','ᵳ':'r','ɼ':'r','ᵲ':'r','ᶉ':'r','ɍ':'r','ɽ':'r','ↄ':'c','ꜿ':'c','ɘ':'e','ɿ':'r','ʂ':'s','ᵴ':'s','ᶊ':'s','ȿ':'s','ɡ':'g','ᴑ':'o','ᴓ':'o','ᴝ':'u','ȶ':'t','ⱦ':'t','ƭ':'t','ᵵ':'t','ƫ':'t','ʈ':'t','ŧ':'t','ᵺ':'th','ɐ':'a','ᴂ':'ae','ǝ':'e','ᵷ':'g','ɥ':'h','ʮ':'h','ʯ':'h','ᴉ':'i','ʞ':'k','ꞁ':'l','ɯ':'m','ɰ':'m','ᴔ':'oe','ɹ':'r','ɻ':'r','ɺ':'r','ⱹ':'r','ʇ':'t','ʌ':'v','ʍ':'w','ʎ':'y','ᶙ':'u','ᵫ':'ue','ꝸ':'um','ⱴ':'v','ꝟ':'v','ʋ':'v','ᶌ':'v','ⱱ':'v','ⱳ':'w','ᶍ':'x','ƴ':'y','ỿ':'y','ɏ':'y','ʑ':'z','ⱬ':'z','ȥ':'z','ᵶ':'z','ᶎ':'z','ʐ':'z','ƶ':'z','ɀ':'z','œ':'oe','ₓ':'x'};
    const stringWithoutAccents = string.normalize("NFD").replace(/[\u0300-\u036f]/g, '');
    return stringWithoutAccents.replace(/[^\u0000-\u007E]/g, character => unicodeToAsciiMap[character] || '');
}

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
					
			var unique = [];			
			var strings = [];
			for (var i in key) {
				if (typeof key[i] === 'object') {
					strings.push(key[i]['@value']);
				} else {
				
					var u = convertToAscii(key[i]);
					u = u.toLowerCase();
					
					if (unique.indexOf(u) == -1) {
						strings.push(key[i]);
						unique.push(u);
					}
				}
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

	if (Array.isArray(datestring)) {
		datestring = datestring[0];
	}

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




if (item['@graph']) {
	item = item['@graph'][0];
}


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
		<span class="heading">Published in</span>
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
		<span class="heading">Published in</span>
		<% 
		var publishedInCitation_html = '<ul>';
		for (var i in item['tcom:publishedInCitation']) {	
		
			// IF
			if (item['tcom:publishedInCitation'][i]['@type'] 
				&& item['tcom:publishedInCitation'][i]['@type'] == 'tpc:PublicationCitation') {
					var parts = [];
										
					if (item['tcom:publishedInCitation'][i]['tpc:title']) {
						parts.push (item['tcom:publishedInCitation'][i]['tpc:title']);
					}

					if (item['tcom:publishedInCitation'][i]['tpc:volume']) {
						parts.push (item['tcom:publishedInCitation'][i]['tpc:volume']);
					}

					if (item['tcom:publishedInCitation'][i]['tpc:number']) {
						parts.push (item['tcom:publishedInCitation'][i]['tpc:number']);
					}

					if (item['tcom:publishedInCitation'][i]['tpc:pages']) {
						parts.push (item['tcom:publishedInCitation'][i]['tpc:pages']);
					}

					if (item['tcom:publishedInCitation'][i]['tpc:year']) {
						parts.push (item['tcom:publishedInCitation'][i]['tpc:year']);
					}

					if (parts.length > 0) {
						var citation = parts.join(' ');
						publishedInCitation_html += '<li>' + citation + '</li>';
					}	
						
			}
	
			// schema.org (will need to handle types as array)
			if (item['tcom:publishedInCitation'][i]['@type'] 
				&& item['tcom:publishedInCitation'][i]['@type'] == 'ScholarlyArticle') {
			
					if (item['tcom:publishedInCitation'][i]['name']) { 
						publishedInCitation_html += '<li>';
						publishedInCitation_html += '<a href="?uri=' + item['tcom:publishedInCitation'][i]['@id'] + '">';
						publishedInCitation_html += get_literal(item['tcom:publishedInCitation'][i]['name']);
						publishedInCitation_html += '</a>';
						publishedInCitation_html += '</li>';
				
					}			
			}
		}	
		
		publishedInCitation_html += '</ul>';	
		%>		
		
		
		
		<%- publishedInCitation_html %>	
		
		
			
	<%	} %>
</div>

<!-- identifiers -->
<div>

		<span class="heading">Unique identifier</span>
		<a href="http://www.lsid.info/<%=item['@id']%>">
		<%= item['@id'] %>
		</a>
	
	
</div>

<!-- code -->
<div>

	<% if (item['dwc:nomenclaturalCode']) { %>
		<span class="heading">Nomenclatural code</span>
		<%= get_literal(item['dwc:nomenclaturalCode']) %>
	<% } %>

	<% if (item['tn:nomenclaturalCode']) { 
		var code = item['tn:nomenclaturalCode']['@id'];
		code = code.replace('tn:', ''); %>
		<span class="heading">Nomenclatural code</span>
		<%= code %>
	<% } %>
	
	
</div>

<!-- basionym -->
<div>
		<% if (item['tn:hasBasionym']) { %>
			<span class="heading">Original combination</span>
			<a href="?uri=<%= item['tn:hasBasionym']['@id'] %>">
			<%= item['tn:hasBasionym']['dc:title'] %>
			</a>
		<%} %>
</div>			

</div>

<!-- types -->
<div>

		<% if (item['tn:typifiedBy']) { %>
			<span class="heading">Types</span>
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
	<span class="heading">More information</span>
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
