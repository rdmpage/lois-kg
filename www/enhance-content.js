
/* AJAX calls to add info to item pages */

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

function name_annotation(id) {
	$.getJSON('api_body_iiif.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// Move this to a template at some point
			
			if (data[0]) {
				var html = '';
				
				html += '<div class="text_container">';
				html += '<h3>View annotation</h3>';				
				html += '<a href="iiif-viewer.php?uri=' + data[0].manifest + '&locator=' + data[0].target + '">';
				
				html += data[0].target;
				
				html += '</a>';
				html += '</div>';
				
				document.getElementById('feed_works').innerHTML = html;
				
				
			
			}
			
			//render(template_datafeed, { item: data }, "feed_works");
			
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
			render(template_tagfeed, { item: data }, "feed_names");
			
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

function work_images(id) {
	$.getJSON('api_work_images.php?uri=' + encodeURIComponent(id) + '&callback=?',
		function(data){
		
			// JSON-LD
			render(template_imagefeed, { item: data }, "feed_images");
			
		}
	);
}

