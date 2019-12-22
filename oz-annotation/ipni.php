<?php

// Export IPNI name to BHL links as W3C annotations

require_once(dirname(dirname(__FILE__)) . '/adodb5/adodb.inc.php');

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


//--------------------------------------------------------------------------------------------------
$db = NewADOConnection('mysqli');
$db->Connect("localhost", 
	'root', '' , 'ipni');
	
// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 

$result = $db->Execute('SET max_heap_table_size = 1024 * 1024 * 1024');
$result = $db->Execute('SET tmp_table_size = 1024 * 1024 * 1024');


// Get data
$page = 1;
$offset = 0;

$done = false;


while (!$done)
{
	$sql = 'SELECT * FROM `names`';	
	$sql .= ' WHERE bhl IS NOT NULL';	
	
	// limit in some way
	$sql .= ' AND biostor=146700';
	
	$sql .= ' LIMIT ' . $page . ' OFFSET ' . $offset;

	$result = $db->Execute($sql);
	if ($result == false) die("failed [" . __FILE__ . ":" . __LINE__ . "]: " . $sql);

	while (!$result->EOF && ($result->NumRows() > 0)) 
	{	
		$ipni = 'urn:lsid:ipni.org:names:' . $result->fields['Id'];
		
		$bhl = $result->fields['bhl'];
		
		
		//echo $id . ' ' . $bhl . "\n";
		
		$triples = array();
		
		// Need an annotation id that we can regenerate easily
		$annotation_id = $ipni . '#bhl';
		
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> . ';
		
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/ns/oa#motivatedBy> <http://www.w3.org/ns/oa#tagging> . ';

		// body is IPNI id
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/ns/oa#hasBody> <' . $ipni . '> . ';
		
		// target is BHL page
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/ns/oa#hasTarget> <https://www.biodiversitylibrary.org/page/' . $bhl . '> . ';		
		
		$nt = join("\n", $triples);
	
		if (1)
		{
			// triples
			echo $nt . "\n";
		}
		else
		{
	
			// JSON-LD
	
		
	
			$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

			// Context 
			$context = new stdclass;
			$context->oa = 'http://www.w3.org/ns/oa#';
			$context->rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';

			$context->body = new stdclass;
			$context->body->{'@type'} = '@id';
			$context->body->{'@id'} = 'oa:hasBody';

			$context->target = new stdclass;
			$context->target->{'@type'} = '@id';
			$context->target->{'@id'} = 'oa:hasTarget';

			$context->motivation = new stdclass;
			$context->motivation->{'@type'} = '@id';
			$context->motivation->{'@id'} = 'oa:motivatedBy';
			
			

			/*
			$frame = (object)array(
				'@context' => $context,	
				'@type' => 'http://iiif.io/api/presentation/2#manifest'
			);	
			$data = jsonld_frame($doc, $frame);
			*/
			
			$data = jsonld_compact($doc, $context);
	
			echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		}		

		
		
		$result->MoveNext();
	}
	
	//echo "-------\n";
	
	if ($result->NumRows() < $page)
	{
		$done = true;
	}
	else
	{
		$offset += $page;
		
		if ($offset >= 10) { $done = true; }
	}
	
	
}
	



?>