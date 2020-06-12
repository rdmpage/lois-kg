<?php

// $Id: //

/**
 * @file config.php
 *
 * Global configuration variables (may be added to by other modules).
 *
 */

global $config;

// Date timezone
date_default_timezone_set('UTC');


// Database-----------------------------------------------------------------------------------------


// Proxy settings for connecting to the web--------------------------------------------------------- 
// Set these if you access the web through a proxy server. 
$config['proxy_name'] 	= '';
$config['proxy_port'] 	= '';

//$config['proxy_name'] 	= 'wwwcache.gla.ac.uk';
//$config['proxy_port'] 	= '8080';

// CouchDB--------------------------------------------------------------------------------
		
if (1)
{
		// local
		$config['couchdb_options'] = array(
				'database' => 'oz-elastic',
				'host' => 'localhost',
				'port' => 32769,
				'prefix' => 'http://'
				);		
}

// HTTP proxy
if ($config['proxy_name'] != '')
{
	if ($config['couchdb_options']['host'] != 'localhost')
	{
		$config['couchdb_options']['proxy'] = $config['proxy_name'] . ':' . $config['proxy_port'];
	}
}

$config['stale'] = false;

// Elastic--------------------------------------------------------------------------------

$config['use_elastic'] = true;


if (file_exists(dirname(__FILE__) . '/env.php'))
{
	include 'env.php';
}


$config['elastic_options'] = array(
		'index' 	=> 'elasticsearch/lois',
		'protocol' 	=> 'http',
		'host' 		=> '35.204.73.93',
		'port' 		=> 80,
		'user' 		=> getenv('ELASTIC_USERNAME'),
		'password' 	=> getenv('ELASTIC_PASSWORD'),
		);



	
?>