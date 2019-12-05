<?php

// Process specific records

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(dirname(__FILE__)) . '/www/config.inc.php');


$ids = array(
//'https://doi.org/10.2307/25065960',
//'https://doi.org/10.1093/aob/mcp090',
//'https://doi.org/10.1080/00275514.2018.1477004',
//'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=http://revista.sco.org.co/index.php/orquideologia/article/view/57'
'https://doi.org/10.1126/science.aaf7115',
);

$ids=array(	
	'https://doi.org/10.3897/mycokeys.12.7553',
	'https://doi.org/10.1111/mec.12481',
	'https://doi.org/10.15156/BIO/587447',
	'https://doi.org/10.1371/journal.pone.0066213',
	);
	
$ids=array(	
	'https://doi.org/10.1128/microbiolspec.FUNK-0053-2016',
);


// Create empty file to write triples to
$filename = 'call.nt';
file_put_contents ($filename, '');

foreach ($ids as $id)
{
	echo $id . "\n";
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/work/_list/triples/nt?key=" . urlencode('"' . $id . '"'));
	
	//echo $resp;
	
	// append triples to file
	file_put_contents ($filename, $resp, FILE_APPEND);

}

// Send to triple store
$namespace = 'https://crossref.org';

// curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@cslx.nt'  --progress-bar | tee /dev/null

$command = "curl " . $config['sparql_endpoint'] . "?context-uri=$namespace -H 'Content-Type: text/rdf+n3' --data-binary '@" . $filename . "' --progress-bar | tee /dev/null";
echo $command . "\n";

system($command);

	
?>