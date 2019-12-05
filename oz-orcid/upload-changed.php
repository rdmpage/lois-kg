<?php

// Process changed records and add to triple store

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(dirname(__FILE__)) . '/www/config.inc.php');

for ($mode = 0; $mode < 2; $mode++)
{
	$limit = 2000;

	$url = '_changes?limit=' . $limit . '&descending=true';

	if ($mode == 0)
	{
		$url .= '&filter=' . urlencode('filters/people');
		$filename = 'people-changed.nt';	
	}
	else
	{
		$url .= '&filter=' . urlencode('filters/works');
		$filename = 'works-changed.nt';
	}

	echo $url . "\n";

	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);
	$obj = json_decode($resp);

	//print_r($obj);

	// Create empty file to write triples to

	file_put_contents ($filename, '');

	foreach ($obj->results as $result)
	{
		$id = $result->id;
	
		echo $id . "\n";
	
		if ($mode == 0)
		{
			$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/people/_list/triples/nt?key=" . urlencode('"' . $id . '"'));	
		}
		else
		{	
			$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/work/_list/triples/nt?key=" . urlencode('"' . $id . '"'));
		}
	
		//echo $resp;
	
		// append triples to file
		file_put_contents ($filename, $resp, FILE_APPEND);

	}

	// Send to triple store
	$namespace = 'https://orcid.org';

	// curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@cslx.nt'  --progress-bar | tee /dev/null

	$command = "curl " . $config['sparql_endpoint'] . "?context-uri=$namespace -H 'Content-Type: text/rdf+n3' --data-binary '@" . $filename . "' --progress-bar | tee /dev/null";
	echo $command . "\n";

	system($command);
}
	
?>