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
//'0000-0003-4031-9721',
//'0000-0002-4430-4538',
//'0000-0002-4326-7210',
//'0000-0002-2168-0514',
//'0000-0002-7619-824X',
//'0000-0002-6076-4622',
//'0000-0003-4907-9197',
//'0000-0003-3891-0758',
//'0000-0002-9504-5441',
//'0000-0001-5171-4140',
//'0000-0001-6878-7514',
//'0000-0002-2819-0323',
//'0000-0002-5014-8500',
//'0000-0003-4629-2590',
//'0000-0002-2025-349X',
//'0000-0002-5740-3666',
//'0000-0002-7063-7299',
//'0000-0001-7698-3945',

/*
'0000-0002-3511-1478',
'0000-0001-5639-2558',
'0000-0003-1085-036X',
'0000-0003-2803-2656',
*/

//'0000-0002-8758-9326',

//'0000-0002-6956-3093',

//'0000-0001-7746-512X',

//'0000-0002-0550-3331',
//'0000-0003-0103-9575',
//'0000-0003-1790-4332',

//'0000-0001-9144-2848',

//'0000-0003-0626-0770',

//'0000-0002-4548-7268',
//'0000-0003-2857-3583',

//'0000-0002-2508-1535',

//'0000-0003-3270-7193',
//'0000-0002-6669-4813',
//'0000-0001-7172-9454',

//'0000-0002-0529-4162',
//'0000-0002-1511-5324',

//'0000-0001-5105-7152',

//'0000-0002-7248-4193',

'0000-0002-6927-1824',

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