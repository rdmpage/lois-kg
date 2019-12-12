<?php

// Process changed records and add to triple store

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(dirname(__FILE__)) . '/www/config.inc.php');

$orcids=array(
'0000-0001-9250-377X',
'0000-0002-0334-7750',
'0000-0002-0970-2872',
'0000-0002-1635-1249',
'0000-0002-5422-2384',
'0000-0002-6977-7550',
'0000-0003-1362-9038',
'0000-0003-1479-5548',
);

$orcids=array(
'0000-0002-4673-4347',
'0000-0002-8509-6587'
);

$orcids=array(
"0000-0001-5632-0044",
"0000-0001-7517-4345",
"0000-0001-9936-2367",
"0000-0002-5743-5035",
"0000-0002-7290-0986",
);

$orcids=array(
//'0000-0002-9925-5171',
'0000-0003-0104-7524',
);

$orcids=array(
'0000-0003-4031-9721'
);

for ($mode = 0; $mode < 2; $mode++)
{
	if ($mode == 0)
	{
		$filename = 'people-call.nt';	
	}
	else
	{
		$filename = 'works-call.nt';
	}

	// Create empty file to write triples to

	file_put_contents ($filename, '');

	foreach ($orcids as $id)
	{

		echo $id . "\n";

		if ($mode == 0)
		{
			$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/people/_list/triples/nt?key=" . urlencode('"' . $id . '"'));	
		}
		else
		{	
			$startkey = $id . '/work/';
			$endkey = $id . '/workz';
			$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/work/_list/triples/nt?startkey=" . urlencode('"' . $startkey . '"') . '&endkey=' . urlencode('"' . $endkey . '"'));
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