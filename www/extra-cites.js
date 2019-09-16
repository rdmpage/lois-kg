<!-- literature cited -->
<div>
<% if (item.citation) {%>
	<h2>References</h2>
	<ol>
	<% for (var i in item.citation) { %>
		<li>
			<%= item.citation[i].name %>
			
			<%
			if (item.citation[i].identifier) {
				var doi = get_property_value(item.citation[i].identifier, 'doi');
				if (doi != '') { %>
					
					doi:<%=doi%>					
				<%}
			}%>
		</li>
	<% } %>
	</ol>
<% } 
%>
</div>
