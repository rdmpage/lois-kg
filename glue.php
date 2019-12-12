<?php

// IPNI glue

require_once(dirname(__FILE__) . '/adodb5/adodb.inc.php');


//--------------------------------------------------------------------------------------------------
$db = NewADOConnection('mysqli');
$db->Connect("localhost", 
	'root', '', 'ipni'
	);

// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 

$page = 1000;
$offset = 0;

$done = false;

while (!$done)
{
	// DOI
	$sql = "SELECT 
	CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <urn:lsid:ipni.org:names:', Id, '#publishedInCitation> .\n<urn:lsid:ipni.org:names:', Id, '#publishedInCitation> <http://schema.org/sameAs> \"https://doi.org/', LOWER(REPLACE(REPLACE (doi, '<', '%3C'), '>', '%3E')), '\" . ') 
AS rdf
FROM names 
WHERE doi IS NOT NULL";

	// JSTOR
$sql = "SELECT 
	CONCAT('<urn:lsid:ipni.org:names:', Id, '> <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <urn:lsid:ipni.org:names:', Id, '#publishedInCitation> .\n<urn:lsid:ipni.org:names:', Id, '#publishedInCitation> <http://schema.org/sameAs> \"https://www.jstor.org/stable/', jstor, '\" . ') 
AS rdf
FROM names 
WHERE jstor IS NOT NULL AND doi IS NULL";


	
	$sql .= ' LIMIT ' . $page . ' OFFSET ' . $offset;

	$result = $db->Execute($sql);
	if ($result == false) die("failed [" . __FILE__ . ":" . __LINE__ . "]: " . $sql);

	while (!$result->EOF) 
	{
		$rdf = $result->fields['rdf'];
		
		echo $rdf . "\n";

		$result->MoveNext();

	}
	
	if ($result->NumRows() < $page)
	{
		$done = true;
	}
	else
	{
		$offset += $page;
		
		// If we want to bale out and check it worked
		//if ($offset > 1000) { $done = true; }
	}
	

}

?>

