<?php

// Get triples in JSON-LD to help with debugging

error_reporting(E_ALL);

require_once('couchsimple.php');

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


// A JSTOR reference
$guid = 'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=http://www.jstor.org/stable/24529694';

// DOI with ORCID
$guid = 'https://doi.org/10.1080/0028825X.2017.1383276';

$url = '_design/work/_list/triples/nt';	

$url .= '?key=' . urlencode('"' . $guid . '"');	

//echo $url . "\n";

//print_r($config['couchdb_options']);

$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

$nt = $resp;

$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

//print_r($doc);

// Context to set vocab to TaxonName
$context = new stdclass;

$context->{'@vocab'} = "http://schema.org/";

$frame = (object)array(
	'@context' => $context,
	'@type' => 'http://schema.org/ScholarlyArticle'
);


$data = jsonld_frame($doc, $frame);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo "\n";


?>
