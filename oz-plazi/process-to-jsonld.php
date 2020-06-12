<?php

require_once('utils.php');

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


//----------------------------------------------------------------------------------------
function rdf_to_triples($xml)
{	
	// Parse RDF into triples
	$parser = ARC2::getRDFParser();		
	$base = 'http://example.com/';
	$parser->parse($base, $xml);	
	
	$triples = $parser->getTriples();

	
	$nt = $parser->toNTriples($triples);
	
	unset($parser);
	
	// https://stackoverflow.com/a/2934602/9684
	$nt = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, $nt);
	
	return $nt;
	

}

//----------------------------------------------------------------------------------------


$uuid = '03E20672FFBEDB3652F4E779AFE1FDED';


$basedir = dirname(__FILE__) . '/rdf';
$uuid_path = create_path_from_sha1($uuid, $basedir);

$rdf_filename = $uuid_path . '/' . $uuid . '.rdf';

$xml = file_get_contents($rdf_filename);

echo $xml;

// fix

$xml = str_replace('xmlns:spm="http://rs.tdwg.org/ontology/voc/SpeciesProfileModel"', 'xmlns:spm="http://rs.tdwg.org/ontology/voc/SpeciesProfileModel#"', $xml);
$xml = preg_replace('/https?:\/\/(dx.)?doi.org\//', 'https://doi.org/', $xml);


$nt = rdf_to_triples($xml);

echo $nt;

//if ($format == 'jsonld')
if (1)
{

	$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

	// Context to set vocab to TaxonName
	$context = new stdclass;

	$context->{'@vocab'} = "http://plazi.org/vocab/treatment#";

	$context->spm = "http://rs.tdwg.org/ontology/voc/SpeciesProfileModel#";
	$context->fabio = "http://purl.org/spar/fabio/";
	$context->dwc = "http://rs.tdwg.org/dwc/terms/";			
	$context->cito = "http://purl.org/spar/cito/";

	$context->bibo = "http://purl.org/ontology/bibo/";
	$context->dc = "http://purl.org/dc/elements/1.1/";
	$context->dwcFP = "http://filteredpush.org/ontologies/oa/dwcFP#";

	$frame = (object)array(
		'@context' => $context,
		'@type' => 'http://plazi.org/vocab/treatment#Treatment'
	);


	$data = jsonld_frame($doc, $frame);
	
	echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo "\n";

}
else
{
	$data = $nt;
}





