<?php

// Get IIIF

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://archive.org/details/mobot31753002251079/manifest.json';
$uri = '';

if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

$json = sparql_iiif_construct($config['sparql_endpoint'], $uri);

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Viewer</title>
	
<style>

body {
	/* overflow: hidden; */
	margin: 0px;
	padding: 0px;
}

/* DIV enclosing the viewer */
#output {
}

/* IIIF viewer */
#viewport {
    background: #e5e5e5; /* Google Books */
    position:relative;
	overflow:hidden;
    padding:0px;
}

#surface {
	/* background: #e5e5e5; */
}

#allpages {
	position: absolute; 
}

#overflow-scrolling {
	position: relative; 
	overflow-x: auto; 
	overflow-y: scroll; 

	/* hand cursor */
  	cursor : -webkit-grab;
  	cursor : -moz-grab;
  	cursor : -o-grab;
  	cursor : grab;
}

.page {
	position: absolute; 
	left: 0px; 
}

.image {
	position: absolute; 
	left: 0px; 
	top: 0px; 
 	-webkit-user-select: none;
 	background:white;	
 	
 	border: 1px solid rgb(193,193,193);  /* Google books */
 
 	/* Try and render image as b&w image */
 	/*
 	-webkit-filter: grayscale(100%) contrast(130%); 
 	*/
}

.lazy {
 /* -webkit-user-select: none; */ /* do we need this? */
}

</style>	
	
	<!-- Load the polyfill first. -->
	<!-- https://github.com/w3c/IntersectionObserver/tree/master/polyfill -->
	<script src="js/intersection-observer.js"></script>

	<!-- ejs for templating -->
	<script src="js/ejs.js"></script>
	
	<!-- jquery -->
    <script src="js/jquery-1.11.2.min.js" type="text/javascript"></script>
 
	<script>
	<?php echo "var data=" . $json . ";"?>
	</script>
	
<script>


//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/25715455
function isObject (item) {
  return (typeof item === "object" && !Array.isArray(item) && item !== null);
}

//----------------------------------------------------------------------------------------

</script>	

</head>
<body>

	<!-- <div id="info" style="float:right;width:200px;">info</div> -->

	<!-- this has the viewer -->
	<div id="output" style="width:500px;height:500px;">
	</div>

	<script>
	
	var v = null;

	
	
	//------------------------------------------------------------------------------------

	/*
	
	Notes: 
	
	Lazy loading of images based on https://github.com/samdutton/simpl/tree/gh-pages/lazy
	
	For touch drag in browser see https://medium.com/creative-technology-concepts-code/native-browser-touch-drag-using-overflow-scroll-492dc92ac737
	
	For handling Chrome changing the cursor to text while dragging, see https://stackoverflow.com/a/47295954/9684
	
	For counting items in an object see http://stackoverflow.com/a/25715455
	
	Need a diagram of how the viewer works
	
	*/

	//------------------------------------------------------------------------------------
	// Initialise object to create viewer from IIIF manifest
	function viewer_data(manifest, element_id) {
	
		this.manifest = manifest;
		
		if (this.manifest['@graph']) {
			this.manifest = this.manifest['@graph'][0];
		}
		
		this.name = this.manifest.label;
		
		if (this.manifest.related) {
			this.related = this.manifest.related;
		}
		
	
		this.images = [];
		this.total_size = 0;
		this.page_space = 14; // 10 = Google Books
		this.page_width = 0;
		this.page_margin = 0;
				
		this.view_width = 0;
		this.view_height = 0;
				
		this.canvas_to_page_map = {};
		
		// annotations
		this.annotations = [];
		
		// Click and drag scrolling
		this.startx;
		this.starty;
		this.diffx;
		this.diffy;
		this.drag;
		
		// Store id of DOM element that encloses the viewer
		this.element_id = element_id;
		
		// Intersection observers
		this.io;
		this.io_info;
		
		// EJS template to render viewer
		this.template = `
		<div id="viewport" style="width: <%- data.view_width %>px;">
			<div id="overflow-scrolling" style="width: <%- data.view_width %>px; height: <%- data.view_height %>px;">
				<div id="surface" style="height: <%- data.total_size %>px;">
					<div id="allpages" style="left: <%- data.page_margin %>px;width: <%- data.page_width %>px; height: <%- data.total_size %>px;">
					
						<% for (var i in data.images)  { %>
							<div class="page" style="top: <%- data.images[i].scaled_top %>px;">
							
								<% if (data.images[i].related) { %> <a name="<%- encodeURI(data.images[i].related) %>"></a> <% } %>
							
								<div id="<%- data.images[i].id %>" title="<%- data.images[i].name %>" class="image" style="width: <%- data.page_width %>px;height: <%- data.images[i].scaled_height %>px;"
								<% if (data.images[i].related) { %> data-related="<%- data.images[i].related %>" <% } %>								
								>
									<img  class="lazy" width="<%- data.page_width %>" data-src="<%- data.images[i].url %>">
								</div> <!-- image -->
															
							</div> <!-- page -->
						
						<% } %>
						
					</div>	<!-- allpages -->			
				</div> <!-- surface -->
			</div> <!-- overflow-scrolling -->	
						
		</div> <!-- viewport -->				
	`;
		
	}
	
	//------------------------------------------------------------------------------------
	// get image size info, and generate a standard id for each image - page00000
	viewer_data.prototype.prepare = function() {
	
		for (var i in this.manifest.sequences[0].canvases) {

			var image = {};
		
			image.id = "page" + String("00000" + i).slice(-5);
		
			image.name   = this.manifest.sequences[0].canvases[i].label;		
			image.height = this.manifest.sequences[0].canvases[i].images[0].resource.height;
			image.width  = this.manifest.sequences[0].canvases[i].images[0].resource.width;
			image.url 	 = this.manifest.sequences[0].canvases[i].images[0].resource['@id'];
			
			image.canvas_scale = this.manifest.sequences[0].canvases[i].images[0].resource.width / this.manifest.sequences[0].canvases[i];
			
			if (this.manifest.sequences[0].canvases[i].related) {
				image.related = this.manifest.sequences[0].canvases[i].related;
			}
		
			this.images.push(image);
			
			// Map canvas id to index of page in viewer
			this.canvas_to_page_map[this.manifest.sequences[0].canvases[i]['@id']] = i;
		}
		
		console.log("canvas_to_page_map=" + JSON.stringify(this.canvas_to_page_map, null, 2));
	}
		
	//------------------------------------------------------------------------------------
	// calculate image sizes based on user-supplied viewer dimensions
	viewer_data.prototype.calculate = function(options) {

		// dimensions of viewer
		this.view_width = options.view_width;
		this.view_height = options.view_height;
		
		
		// page displayed between left margin and tick bar, occupy 80% of that space
		//this.page_width = this.tick_left * 0.8;
		
		this.page_width = 685; // for now this is a constant so we don't recalculate layout of annotations
		this.page_width = 600;
		
		//this.page_width = this.view_width * 0.8;
				
		// get left margin of page image
		this.page_margin = (this.view_width - this.page_width)/2.0;
		
		// keep track of top of each page
		var page_top = 0;
		
		this.total_size = 0;
		
		for (var i in this.images) {
			this.images[i].scale = this.page_width / this.images[i].width;
			
			this.images[i].scaled_width = this.images[i].width * this.images[i].scale;
			this.images[i].scaled_height = this.images[i].height * this.images[i].scale;
		
			this.total_size += this.images[i].scaled_height;
			
			this.images[i].scaled_top = page_top;
			
			page_top += this.images[i].scaled_height + this.page_space;			
		}
		
		this.total_size += (this.images.length - 1) * this.page_space;
	}

	//------------------------------------------------------------------------------------
	viewer_data.prototype.actions = function() {
		if (!this.scroller) {
			this.scroller = document.querySelector('#overflow-scrolling');
		}
		
		// add event listeners
		this.scroller.addEventListener('mousedown', this.onMouseDown.bind(this));
		this.scroller.addEventListener('mousemove', this.onMouseMove.bind(this));
		this.scroller.addEventListener('mouseup', this.onMouseUp.bind(this));

		window.addEventListener('resize', this.resize.bind(this));
		
		// intersection observers for lazy loading of images and tracking which page is being displayed
		
		const images = document.querySelectorAll('img.lazy');
		
		const images_info = document.querySelectorAll('div.image');
				
		var options_lazyload = {
  			root: document.getElementById('overflow-scrolling'),
			  // rootMargin: top, right, bottom, left margins
			  // added to the bounding box of the root element (viewport if not defined)
			  // see https://developer.mozilla.org/en-US/docs/Web/API/IntersectionObserver  				
  			rootMargin: '1000px 0px 1000px 0px',
  			
  			// threshold: how much of the target visible for the callback to be invoked
  			// includes padding, 1.0 means 100%  			
  			threshold: 0
  		};

		var options_info= {
  			root: document.getElementById('overflow-scrolling'),
  			 // match the page dimensions
  			rootMargin: '0px 0px 0px 0px',
  			
			// we want a big chunk of the page to be visible so we don't trigger events if just a bit appears  		  			
  			threshold: 0.5 
  		};
		
		if (window.IntersectionObserver) {
		
			// lazy loading of images
  			this.io = new IntersectionObserver(
				(function callback(entries) {
				  for (const entry of entries) {
					if (entry.isIntersecting) {
					  let lazyImage = entry.target;
					  
					  if (!lazyImage.src && lazyImage.hasAttribute('data-src')) {
					  
					  	
						lazyImage.src = lazyImage.dataset.src;
					
						// presence of "width" is a flag that we will use an image CDN
						if (lazyImage.hasAttribute('width')) {
							lazyImage.src = 'http://exeg5le.cloudimg.io/s/width/' + this.page_width + '/' + lazyImage.src;
						}
						
					  }
					}
				  }
				}).bind(this) // bind(this) gives us access to "this"
				, 
				options_lazyload
			);
  			
  			// page information
   			this.io_info = new IntersectionObserver(
				function callback(entries) {
				  for (const entry of entries) {
					if (entry.isIntersecting) {
					  let item = entry.target;
					  
					  if (item.hasAttribute('data-related')) {
					  	 window.parent.postMessage(item.dataset.related, "*");
					  } else {
					  	 window.parent.postMessage(item.id, "*");
					  }
				  
					}
				  }
				}
				, 
				options_info
			);
  		}
		
		for (const image of images) {
		  if (window.IntersectionObserver) {
			this.io.observe(image);
		  } else {
			console.log('Intersection Observer not supported');
			image.src = image.getAttribute('data-src');
		  }
		}  		

		for (const image of images_info) {
		  if (window.IntersectionObserver) {
			this.io_info.observe(image);
			} 
		} 				
		
	}
	
	//------------------------------------------------------------------------------------
	viewer_data.prototype.onMouseDown = function(e) {
		if (!e) { e = window.event; }
		if (e.target && e.target.nodeName === 'IMG') {
			e.preventDefault();
		} else if (e.srcElement && e.srcElement.nodeName === 'IMG') {
			e.returnValue = false;
		}
		this.startx = e.clientX + this.scroller.scrollLeft;
		this.starty = e.clientY + this.scroller.scrollTop;
		this.diffx = 0;
		this.diffy = 0;
		this.drag = true;
	}

	//------------------------------------------------------------------------------------
	viewer_data.prototype.onMouseMove = function(e) {
		if (this.drag === true) {
		
			// https://stackoverflow.com/a/47295954/9684
			// ensure dragging cursor is not text cursor
			document.onselectstart = function(){ return false; }
	
			if (!e) { e = window.event; }
			this.diffy = (this.starty - (e.clientY + this.scroller.scrollTop));
			this.scroller.scrollTop += this.diffy;
		}
    }

	//------------------------------------------------------------------------------------
	viewer_data.prototype.onMouseUp = function(e) {
		if (!e) { e = window.event; }
		this.drag = false;
	
		// https://stackoverflow.com/a/47295954/9684
		document.onselectstart = function(){ return true; }  
		
		// make sure these variables will be in scope for the function animate()
		var el =  this.scroller;
		var diffy = this.diffy;
	
		var start = 1,
			animate = function () {
				var step = Math.sin(start);
				if (step <= 0) {
					window.cancelAnimationFrame(animate);
				} else {
					el.scrollTop += diffy * step;
					start -= 0.02;
					window.requestAnimationFrame(animate);
				}
			};
		animate();
    }

	//------------------------------------------------------------------------------------
	// resize viewer to match size of containing element
	viewer_data.prototype.resize = function() {
		
		//var width = document.getElementById(this.element_id).clientWidth;
		//var height = document.getElementById(this.element_id).clientHeight;
		
		var width = window.innerWidth;
		var height = window.innerHeight;		
		
		this.calculate({ view_width: width, view_height: height });
		
		var d = document.getElementById('viewport');		
		d.style.width= this.view_width + 'px';
		d.style.height= this.view_height + 'px';

		var s = document.getElementById('overflow-scrolling');		
		s.style.width= this.view_width + 'px';
		s.style.height= this.view_height + 'px';
		
		var a = document.getElementById('allpages');
		a.style.left= this.page_margin + 'px';	
	}
	
	
	//------------------------------------------------------------------------------------
	viewer_data.prototype.render = function() {
	
		// Render template 	
		var html = ejs.render(this.template, { data: this });
		
		document.getElementById(this.element_id).innerHTML = html;	
	}
	
	//------------------------------------------------------------------------------------
	
	
	// Create and setup the viewer
	
	v = new viewer_data(data, 'output');
	
	v.prepare();
	
	// Get window coordinates	
	var width = window.innerWidth;
	var height = window.innerHeight;
	
	v.calculate({ view_width: width, view_height: height });
		
	v.render();
	
	// add event handlers and observers
	v.actions();
	
	parent.document.getElementById('title').innerHTML = v.name;
	
	if (v.related) {
		parent.document.getElementById('related').setAttribute('href', v.related);
	}

	
	</script>
	


</body>
</html>


