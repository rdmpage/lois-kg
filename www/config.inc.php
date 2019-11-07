<?php

/**
 * @file config.inc.php
 *
 * Global configuration variables (may be added to by other modules).
 *
 */

global $config;

// Date timezone
date_default_timezone_set('UTC');

$site = 'local';

switch ($site)
{

	case 'local':
	default:
		// Server-------------------------------------------------------------------------
		$config['web_server']	= 'http://localhost'; 
		$config['site_name']	= 'LOIS';

		// Files--------------------------------------------------------------------------
		$config['web_dir']		= dirname(__FILE__);
		$config['web_root']		= '/~rpage/lois-kg/www/';
		break;
}


$config['sparql_endpoint'] 	= '';
$config['triplestore'] 		= 'blazegraph-digitalocean';
//$config['triplestore'] 		= 'blazegraph-local';

if ($config['triplestore'] == 'blazegraph-windows10')
{
	$config['blazegraph-url'] 	= 'http://130.209.46.63';	
	$config['sparql_endpoint']	= $config['blazegraph-url'] . '/blazegraph/sparql'; 
}

if ($config['triplestore'] == 'blazegraph-digitalocean')
{
	$config['blazegraph-url'] 	= 'http://167.71.255.145:9999';	
	$config['blazegraph-url'] 	= 'http://167.99.58.120:9999';	
	$config['sparql_endpoint']	= $config['blazegraph-url'] . '/blazegraph/sparql'; 
}

if ($config['triplestore'] == 'blazegraph-local')
{
	$config['blazegraph-url'] 	= 'http://localhost:32773';	
	$config['sparql_endpoint']	= $config['blazegraph-url'] . '/blazegraph/sparql'; 
}




?>
