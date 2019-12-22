<?php

// export views from BioNames CouchDB

require_once (dirname(dirname(__FILE__)) . '/config.inc.php');

//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	echo $data;
	
	return $data;
}

//----------------------------------------------------------------------------------------


// Databases and views
$views = array(
	'oz-gbif' => array('export', 'gbif', 'ld', 'search'),
);

foreach ($views as $database => $views)
{
	// Folder for each database
	$db_dir = dirname(__FILE__);
	
	/*
	if (!file_exists($db_dir))
	{
		$oldumask = umask(0); 
		mkdir($db_dir, 0777);
		umask($oldumask);
	}
	*/

	// Get views
	foreach ($views as $view)
	{
		$url = $config['couchdb_options']['prefix'] . $config['couchdb_options']['host'] . ':' . $config['couchdb_options']['port'] . '/'  . $database . '/_design/' . $view;
		$resp = get($url);
	
		file_put_contents($db_dir . '/' . $view . '.js', $resp);
	}
}
		


?>