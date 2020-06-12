var template_taxon_name = `

<%

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


//----------------------------------------------------------------------------------------

if (item['@graph']) {
	item = item['@graph'][0];
}


%>

<!-- title -->
<h1>
	<img src="images/noun_Tag_1015039.svg" height="48" align="center">

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
		
			// Index Fungorum, ZooBank, etc.
			if (has_type(item['tcom:publishedInCitation'][i], 'tpc:PublicationCitation')) {
				var parts = [];
									
				if (item['tcom:publishedInCitation'][i]['tpc:title']) {
					parts.push (item['tcom:publishedInCitation'][i]['tpc:title']);
				}

				if (item['tcom:publishedInCitation'][i]['tpc:parentPublicationString']) {
					parts.push (item['tcom:publishedInCitation'][i]['tpc:parentPublicationString']);
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
					
					var link = '';
					
					if (item['tcom:publishedInCitation'][i]['@id'].match(/^urn:lsid:zoobank.org:pub:/)) {
						link = item['tcom:publishedInCitation'][i]['@id'];
					}						
					
					publishedInCitation_html += '<li>';
					
					if (link != '') {					
						publishedInCitation_html += '<a href="?uri=' + item['tcom:publishedInCitation'][i]['@id'] + '">';
					}
					publishedInCitation_html += citation;
					
					if (link != '') {					
						publishedInCitation_html += '</a>';
					}
					publishedInCitation_html += '</li>';
					
					
				}	
						
			}
			
			//sameAs 
			if (item['tcom:publishedInCitation'][i]['sameAs']) {
				publishedInCitation_html += '<li>';
				publishedInCitation_html += '<a href="?uri=' + item['tcom:publishedInCitation'][i]['sameAs']['@id'] + '">';
				publishedInCitation_html += get_literal(item['tcom:publishedInCitation'][i]['sameAs']['name']);
				publishedInCitation_html += '</a>';
				publishedInCitation_html += '</li>';
			}
	
			/*
			// schema.org (will need to handle types as array)
			if (item['tcom:publishedInCitation'][i]['@type'] 
				&& (
					has_type(item['tcom:publishedInCitation'][i], 'ScholarlyArticle') 
					||  has_type(item['tcom:publishedInCitation'][i], 'CreativeWork')
					)
				) {
			
					if (item['tcom:publishedInCitation'][i]['name']) { 
						publishedInCitation_html += '<li>';
						publishedInCitation_html += '<a href="?uri=' + item['tcom:publishedInCitation'][i]['@id'] + '">';
						publishedInCitation_html += get_literal(item['tcom:publishedInCitation'][i]['name']);
						publishedInCitation_html += '</a>';
						

						//if (item['tcom:publishedInCitation'][i]['thumbnailUrl']) {
						//	publishedInCitation_html += '<img style="float:left;height:100px;border:1px solid rgb(224,224,224);background-color:white;object-fit:contain; display:block;margin:auto;" src="http://exeg5le.cloudimg.io/s/height/100/' + item['tcom:publishedInCitation'][i]['thumbnailUrl'] + '">';
						//}
						
						
						
						
						publishedInCitation_html += '</li>';
				
					}			
			}
			*/
		}	
		
		publishedInCitation_html += '</ul>';	
		%>		
		
		
		
		<%- publishedInCitation_html %>	
		
		
			
	<%	} %>
</div>

<div style="clear:both;"></div>

<!-- identifiers -->
<div>

		<span class="heading">Unique identifier</span>
		<a class="external" href="http://www.lsid.info/<%=item['@id']%>" target="_new">
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

<!-- annotations -->
<div>
	<% if (item['tn:hasAnnotation']) { %>
		<span class="heading">Annotation</span>
		<ul>
			<% for (var i in item['tn:hasAnnotation']) {
				switch (item['tn:hasAnnotation'][i]['tn:noteType']['@id']) {
				
						case 'tn:publicationStatus': %>
							<li><%= item['tn:hasAnnotation'][i]['tn:note'] %></li>							
					<% 		break;
					
						case 'tn:replacementNameFor': %>
							<li>Replacement for 
							<a href="?uri=<%= uri_to_clickable_url(item['tn:hasAnnotation'][i]['tn:objectTaxonName']['@id']) %>">
							<%= item['tn:hasAnnotation'][i]['tn:objectTaxonName']['dc:title'] %>
							</a>
							</li>	
					<% 		break;
					
							default:
							break;
					} %>
				</li>			
			<% } %>
		</ul>
	<% } %>
	
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

<!-- sameAs -->
<div>
	<% if (item['sameAs']) { %>
		<span class="heading">Same as</span>
			<% if (Array.isArray(item['sameAs'])) {	%>
				<ul>
				<% for (var i in item['sameAs']) { %>
					<li>
					<a href="?uri=<%= uri_to_clickable_url(item['sameAs'][i]) %>">
						<%= item['sameAs'][i] %>
					</a>
					</li>
				<% } %>
				</ul>
			<% }  else { %>
				<a href="?uri=<%= uri_to_clickable_url(item['sameAs']) %>">
					<%= item['sameAs'] %>
				</a>
			<% } %>
		</span>
	<% } %>	
</div>


`;
