<?php


/*

curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://wikidata.org -H 'Content-Type: text/rdf+n3' --data-binary '@wikidata.nt'  --progress-bar | tee /dev/null


*/


require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


$ipni_ids = array();


$headings = array();

$row_count = 0;

$filename = "Metastelmatinae.tsv";
//$filename = "Asclepiadeae.tsv";

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;		
		}
		else
		{
			$obj = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '')
				{
					$obj->{$headings[$k]} = $v;
				}
			}
		
			//print_r($obj);	
			
			// ignore taxa that aren't accepted (Wikidata may have synonyms as taxa)
			if ($obj->status == 'accepted')
			{
			
				// RDF
			
				$triples = array();
			
				$subject_id = 'http://www.wikidata.org/entity/' . $obj->id;
			
				$triple = '<' . $subject_id . '> <http://schema.org/name> "' . addcslashes($obj->name, '"') . '" .';
				$triples[] = $triple;
			
			
				$triple = '<' . $subject_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/Taxon> . ';
				$triples[] = $triple;
			
				if (isset($obj->parent))
				{
					$triple = '<' . $subject_id . '> <http://schema.org/parentTaxon> <http://www.wikidata.org/entity/' . $obj->parent . '> . ';
					$triples[] = $triple;			
				}

				if (isset($obj->rank))
				{
					$triple = '<' . $subject_id . '> <http://schema.org/taxonRank> "' . addcslashes($obj->rank, '"') . '" .';
					$triples[] = $triple;			
				}
			
				// images
				if (isset($obj->image))
				{
					$triple = '<' . $subject_id . '> <http://schema.org/image> "' . addcslashes($obj->image, '"') . '" .';
					$triples[] = $triple;			
				}

				// identifiers
				
				if (isset($obj->gbif))
				{			
					$identifier_id = $subject_id . '#gbif';
		
					$triple = '<' . $subject_id . '> <http://schema.org/identifier> <' . $identifier_id . '> . ';
					$triples[] = $triple;

					$triple = '<' . $identifier_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/PropertyValue> . ';		
					$triples[] = $triple;

					$triple = '<' . $identifier_id . '> <http://schema.org/value> "' . $obj->gbif . '" . ';
					$triples[] = $triple;

					$triple = '<' . $identifier_id . '> <http://schema.org/name> "GBIF" . ';
					$triples[] = $triple;

					$triple = '<' . $identifier_id . '> <http://schema.org/propertyID> "https://www.wikidata.org/wiki/Property:P846" . ';
					$triples[] = $triple;
					
					// https://www.gbif.org/species/
					$triple = '<' . $subject_id . '> <http://schema.org/sameAs> "https://www.gbif.org/species/' . $obj->gbif . '" . ';
					$triples[] = $triple;					
					
				}
			
			
				// scientific name (name database)
				if (isset($obj->ipni))
				{
					$name_id = 'urn:lsid:ipni.org:names:' . $obj->ipni;
				
					$ipni_ids[] = $name_id;
				
					$triple = '<' . $subject_id . '> <http://schema.org/scientificName> <' . $name_id . '> . ';
					$triples[] = $triple;			
				}

			
				$nt = join("\n", $triples);
		
				
		
				$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));
		
				//print_r($doc);

				// Context 
				$context = new stdclass;
				$context->{'@vocab'} = "http://schema.org/";
				$context->rdf 		= "http://www.w3.org/1999/02/22-rdf-syntax-ns#";
				$context->tc = "http://rs.tdwg.org/ontology/voc/TaxonConcept#";
				$context->dwc = 'http://rs.tdwg.org/dwc/terms/';
				$context->tn = 'http://rs.tdwg.org/ontology/voc/TaxonName#';
			
				$context->WD = 'http://www.wikidata.org/entity/';

				$frame = (object)array(
					'@context' => $context,
			
					// Root on article
					'@type' => 'http://schema.org/Taxon',
			
				);	
		
				$data = jsonld_frame($doc, $frame);		
		
				if (1)
				{
					echo $nt . "\n";
				}
				else
				{
					echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
					echo "\n";		
				}
			
			}			
			
			
		}
	}	
	$row_count++;	
	
}	

/*
echo '$urls = array(' . "\n";
foreach ($ipni_ids as $id)
{
	echo '"' . $id . '",' . "\n";
}
echo ');' . "\n";
*/

?>
