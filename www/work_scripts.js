
//----------------------------------------------------------------------------------------
// Taxon name

function name_basionym(id) {
	$.getJSON('api_name_basionym.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_basionym");
			
		}
	);
}


//----------------------------------------------------------------------------------------
// Person

function person_works(id) {
	$.getJSON('api_person_works.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_works");
			
		}
	);
}

function person_names(id) {
	$.getJSON('api_person_names.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_names");
			
		}
	);
}

//----------------------------------------------------------------------------------------
// Container

function container_works(id) {
	$.getJSON('api_container_works.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_decade_feed, { item: data }, "feed_works");
			
		}
	);
}

//----------------------------------------------------------------------------------------
// Works

function work_names(id) {
	$.getJSON('api_work_names.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_names");
			
		}
	);
}


function work_cites(id) {
	$.getJSON('api_work_cites.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_cites");
			
		}
	);
}

function work_cited_by(id) {
	$.getJSON('api_work_cited_by.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_cited_by");
			
		}
	);
}

function work_related(id) {
	$.getJSON('api_work_related.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_datafeed, { item: data }, "feed_related");
			
		}
	);
}
