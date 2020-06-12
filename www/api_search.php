<?php

// Elastic search

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/search.php');

$q = "Taiwan";
$q = "Tillich";

if (isset($_REQUEST['q']))
{
	$q = $_REQUEST['q'];
}

$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
}

if ($callback != '')
{
	echo $callback . '(';
}

echo json_encode(do_search($q), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if ($callback != '')
{
	echo ')';
}


?>
