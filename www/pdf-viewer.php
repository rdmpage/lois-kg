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
  background: #e5e5e5;
}
.flex_item_right {
  
  width: 360px;
  background: white;
  
  padding:10px;
}


</style>

<script src="js/jquery-1.11.2.min.js"></script>

<script type = "text/javascript">

	var pdf_url = '<? echo $uri; ?>';



  window.addEventListener("message", receiveMessage, false);

function receiveMessage(event) {
  console.log("receiveMessage" + JSON.stringify(event.data));
  if (typeof event.data === "number") {
  
    $("#page_change").html("#page=" + event.data);
    
    show_body(event.data);
    
    
  }
} 

function show_body(page) {
			$.getJSON("api_target_body.php?uri=" + pdf_url + '%23page=' +  page + "&callback=?",
				function(data){
				
					//$("#page_change").html(JSON.stringify(data, null, 2));
					
					
					var html = '';
					
					html += '<ul>';
					
					for (var i in data) {
						html += '<li>';
						
						html += data[i].name;
						
						html += ' <a href="./?uri=' + data[i].taxonomic_name + '">' +  data[i].taxonomic_name + '</a>';
						
						
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
	  var windowHeight = $(window).height();
	  console.log(windowHeight);
	  $("#pdf").css({
	    "height": windowHeight
	  });
	});
</script>    

</head>
<body onload="$(window).resize();">


<div class="flex_container">
  <div class="flex_item_left">
    <iframe id="pdf" frameBorder="0"  width="100%" height="200px" src="js/pdfjs/web/viewer.html?file=<?php echo urlencode('../../../pdf_proxy.php?url=' . urlencode($uri)) . '#page=' . $locator; ?>" ></iframe>
  </div>
  <div class="flex_item_right">
   <span id="page_change"></span>

	<!-- <button onclick="document.getElementById('pdf').contentWindow.PDFViewerApplication.page=11">go to 11</button> -->

  </div>
  <div>

</body>
</html>

