<?php

// Take RDF from Persee

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);

	if ($content_type != '')
	{
		$opts[CURLOPT_HTTPHEADER] = array(
			"Accept: " . $content_type, 
			"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" 
		);
	}	
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}


//----------------------------------------------------------------------------------------
function rdf_to_triples($xml, $url = '')
{	
	// Parse RDF into triples
	$parser = ARC2::getRDFParser();		
	$base = 'http://example.com/';
	$parser->parse($base, $xml);	
	
	$triples = $parser->getTriples();
	
	$bnodes = array();
	$bnode_counter = 1;
	
	
		
	// clean up
	
	$cleaned_triples = array();
	foreach ($triples as $triple)
	{
		$add = true;

		if ($triple['s'] == 'http://example.com/')
		{
			$add = false;
		}
		
		// fix bnodes
		if ($url != '')
		{
			if (preg_match('/^_:(?<bnode>.*)$/', $triple['s'], $m))
			{
				if (!isset($bnodes[$triple['s']]))
				{
					$bnodes[$triple['s']] = $bnode_counter;
					$bnode_counter++;
				}
			
				$triple['s'] = $url . '#' . $bnodes[$triple['s']];	
				$triple['s_type'] = 'uri';				
			}
			
			if (preg_match('/^_:(?<bnode>.*)$/', $triple['o'], $m))
			{
				if (!isset($bnodes[$triple['o']]))
				{
					$bnodes[$triple['o']] = $bnode_counter;
					$bnode_counter++;
				}
			
			
				$triple['o'] = $url . '#' . $bnodes[$triple['o']];							
				$triple['o_type'] = 'uri';				
			}
			
		}
		
		if ($add)
		{
			$cleaned_triples[] = $triple;
		}
	}
	
	if (0)
	{
		echo "bnodes\n";
		print_r($bnodes);
		print_r($cleaned_triples);
		
	}
	
	$nt = $parser->toNTriples($cleaned_triples);
	
	unset($parser);
	
	//echo $nt ;
	
	// https://stackoverflow.com/a/2934602/9684
	$nt = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, $nt);
	
	return $nt;
}


$url = 'http://data.persee.fr/doc/jatba_0370-5412_1951_num_31_339_6746#Web';

$url = 'http://data.persee.fr/authority/243889#Person';

$urls = array(
'http://data.persee.fr/authority/243889#Person',
'http://data.persee.fr/authority/240431#Person',
'http://data.persee.fr/authority/255736#Person',
'http://data.persee.fr/authority/233775#Person',
'http://data.persee.fr/authority/243758#Person',
'http://data.persee.fr/authority/155565#Person',
'http://data.persee.fr/authority/256234#Person',
'http://data.persee.fr/authority/238327#Person',
'http://data.persee.fr/authority/262162#Person',
'http://data.persee.fr/authority/232826#Person',
'http://data.persee.fr/authority/255665#Person',

);

foreach ($urls as $url)
{

	$xml = get($url);

	//echo $xml;

	// convert
	$nt = rdf_to_triples($xml, $url);

	$format = 'nt';

	if ($format == 'jsonld')
	{

		
		$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

		// Context 
		$context = new stdclass;

		$context->{'@vocab'} 	= "http://purl.org/dc/terms/";
	

		$context->rdfs			= "http://www.w3.org/2000/01/rdf-schema#";
		$context->foaf			= "http://xmlns.com/foaf/0.1/";
		$context->bibo			= "http://purl.org/ontology/bibo/";
		$context->xs			= "http://www.w3.org/2001/XMLSchema#";
		$context->skos			= "http://www.w3.org/2004/02/skos/core#";
	
		$context->schema			= "http://schema.org/";
	
		// .be
		$context->owl			= "http://www.w3.org/2002/07/owl/";
		// .rbge 
		$context->owl			= "http://www.w3.org/2002/07/owl#";
	
		$context->rdau			= "http://rdaregistry.info/Elements/u/";
		$context->persee		= "http://data.persee.fr/ontology/persee-ontology/";
		$context->marcrel		= "http://id.loc.gov/vocabulary/relators/";
		$context->rdam			= "http://rdaregistry.info/Elements/m/";
		$context->rda			= "http://rdaregistry.info/Elements/c/";
		$context->rda			= "http://rdaregistry.info/Elements/c/";


		$data = jsonld_compact($doc, $context);
	
		echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		echo "\n";

	}
	else
	{
		echo $nt . "\n";

	}

}




?>