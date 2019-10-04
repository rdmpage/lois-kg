var template_work = `

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


if (item['@graph']) {
	item = item['@graph'][0];
}


%>

<!-- location -->
<div>

<!-- container -->
<% if (item.isPartOf) { %>
	<% if (item.isPartOf['@id']) { %>
		<a href="?uri=<%= item.isPartOf['@id'].replace('#', '%23') %>">
	<% } %>

	<%= get_literal(item.isPartOf.name) %>
	
	<% if (item.isPartOf['@id']) { %>
		</a>
	<% } %>
	
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
	<%- get_literal(item.name) %>
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
				
				var orcid = '';
				
			    if (item.creator[i].creator[0].identifier) {
			    	orcid = get_property_value(item.creator[i].creator[0].identifier, 'orcid');
			    }
			    
				if (orcid != '') {
					//string += '<a href="https://orcid.org/' + orcid + '"><img src="images/orcid_16x16.png"></a>&nbsp;';
					string += '<img src="images/orcid_16x16.png"></a>&nbsp;';
					string += '<a href="?uri=https://orcid.org/' + orcid + '">';
			    }
			   
				string += get_literal(item.creator[i].creator[0].name);
				
				if (orcid != '') {
					string += '</a>';
				}
				
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
		<span class="heading">DOI</span>
		<a href="https://doi.org/<%=id%>">
		<%= id %>
		</a>
	<% }
	
	// Handle
	id = get_property_value(item.identifier, 'handle');	  
	if (id != '') {  %>	
		<span class="heading">Handle</span>
		<a href="https://hdl.handle.net/<%=id%>">
		<%= id %>
		</a>
	<% }

	// JSTOR
	id = get_property_value(item.identifier, 'jstor');	  
	if (id != '') {  %>	
		<span class="heading">JSTOR</span>
		<a href="https://www.jstor.org/stable/<%=id%>">
		<%= id %>
		</a>
	<% }	
	
	// PMID
	id = get_property_value(item.identifier, 'pmid');	  
	if (id != '') {  %>	
		<span class="heading">PMID</span>
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