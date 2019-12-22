<?php

// Construct IIIF manifest in JSON-LD

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');


$uri = 'https://archive.org/details/mobot31753002251079/manifest.json';

if (isset($_REQUEST['uri']))
{
	$uri = $_REQUEST['uri'];
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
echo sparql_iiif_construct($config['sparql_endpoint'], $uri);
if ($callback != '')
{
	echo ')';
}


?>
