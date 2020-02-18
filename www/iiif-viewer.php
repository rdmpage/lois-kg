<?php

$uri = '';

$locator = '';


if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
}

if (isset($_REQUEST['locator']))
{
	$locator = $_REQUEST['locator'];
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>

body {
	padding:0px;
	margin:0px;
	overflow: hidden;
}

.flex_container {
  display: flex;
  width: 100%;
}
.flex_item_left {
  flex: 1;
  background: white; /*#e5e5e5;*/
 
}
.flex_item_right {
  
  width: 360px;
  background: white;
  
  padding:10px;
  border-left:1px solid #aaa;
}

</style>

<script src="js/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
window.addEventListener("message", receiveMessage, false);

function receiveMessage(event)
{
	console.log("receiveMessage" + JSON.stringify(event.data));
	console.log("receiveMessage" + JSON.stringify(event));
	if (typeof event.data === "number") {
	   //alert(event.data);
	   $("#page_change").html("#page=" + event.data);
	} else {
	
		// BHL page 
	 	//$("#page_change").html(event.data);
	 	
	 	show_body(event.data);
	}
}  


function show_body(target_uri) {

			$.getJSON("api_target_body.php?uri=" + target_uri + "&callback=?",
				function(data){
				
					//$("#page_change").html(JSON.stringify(data, null, 2));
					
					
					var html = '';
					
					html += '<ul>';
					
					for (var i in data) {
						html += '<li>';
						
						html += data[i].name;
						
						html += ' <a href="./?uri=' + data[i].body + '">' +  data[i].body + '</a>';
						
						
						html += '</li>';
					}
					
					html += '</ul>';
					
					$("#page_change").html(html);
					
				 
					
			});
		}


  </script>
  
<script>
	/* http://stackoverflow.com/questions/6762564/setting-div-width-according-to-the-screen-size-of-user */
	$(window).resize(function() { 
		/* Only resize document window if we have a document cloud viewer */
		var windowHeight =$(window).height();	
		console.log(windowHeight);
		$("#viewer").css({"height": windowHeight });
	});	
</script>    

</head>
<body onload="$(window).resize();">


<div class="flex_container">
  <div class="flex_item_left">
    
    <!-- use # to move viewer to page we want to view (hack) -->
    <!-- <iframe id="viewer" frameBorder="0"  width="100%" height="200px" src="iiif.html#13739404" ></iframe> -->
    <iframe id="viewer" frameBorder="0"  width="100%" height="200px" src="iiif.php?uri=<?php echo $uri; ?>#<?php echo $locator; ?>" ></iframe>
    
  </div>
  <div class="flex_item_right">
   <h3 id="title">Untitled</h3>
   <div><a id="related" href="">Related</a></div>
   <h4>Annotations</h4>
   <div id="page_change"></div>
  </div>
  <div>

</body>
</html>

