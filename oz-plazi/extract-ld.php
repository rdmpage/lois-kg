<?php


//error_reporting(0); // there is an unexplained error in json-ld php


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

//----------------------------------------------------------------------------------------function rdf_to_triples($xml)


$basedir = dirname(__FILE__) . '/rdf';

$count = 0;

$files1 = scandir($basedir);

$skip = array(
'039987AEFFD3FFD94CEE54CFFC57FD0B.rdf', // breaks JSON-LD
'03F787A5BA10C802FF262036FBCBFD4B.rdf', // too big?
'03E487E3FFF8A6141B897185E9F3FA2F.rdf', //
'03CB6450FFA9F97F76A8CABDFD0B4A9A.rdf', 
'0390046EFFA5D664E5B1F924FD10FD83.rdf',
);


unset($files1[0]);
unset($files1[1]);
unset($files1[2]);
unset($files1[3]);
unset($files1[4]);
unset($files1[5]);
unset($files1[6]);
unset($files1[7]);
unset($files1[8]);
unset($files1[9]);
unset($files1[10]);
unset($files1[11]);


//print_r($files1);
//exit();

//$files1 = array('AA');

//$files1 = array('FE');
//$files1 = array('FA');
//$files1 = array('84');

//$files1 = array('CB');

//$files1 = array('07');

//$files1 = array('04');
//$files1 = array('05');

//$files1 = array('06');

$files1 = array('0C');
$files1 = array('0D');



foreach ($files1 as $directory1)
{
	if (preg_match('/^[a-z0-9]{2}$/i', $directory1))
	{
		//$files = scandir($basedir . '/' . $directory1);
		// foreach ($files as $filename)
		
		file_put_contents($directory1 . '.sql', '');
		
		if ($handle = opendir($basedir . '/' . $directory1)) 
		{
       		while (false !== ($filename = readdir($handle))) 
       		{
            	if ($filename != "." && $filename != ".." && !in_array($filename, $skip)) 
            	{ 
					if (preg_match('/\.rdf/', $filename))
					{
						echo $filename . ' ' . filesize($basedir . '/' . $directory1 .'/' . $filename) . "\n";
				
				
				
						$xml = file_get_contents($basedir . '/' . $directory1 .'/' . $filename);
				
						$xml = str_replace('xmlns:spm="http://rs.tdwg.org/ontology/voc/SpeciesProfileModel"', 'xmlns:spm="http://rs.tdwg.org/ontology/voc/SpeciesProfileModel#"', $xml);
						$xml = preg_replace('/https?:\/\/(dx.)?doi.org\//', 'https://doi.org/', $xml);

						$nt = rdf_to_triples($xml);
				
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
				
						// arrays
						$hasMaterialCitation = new stdclass;
						$hasMaterialCitation->{'@id'} = "hasMaterialCitation";
						$hasMaterialCitation->{'@container'} = "@set";
			
						$context->{'hasMaterialCitation'} = $hasMaterialCitation;
				

						$frame = (object)array(
							'@context' => $context,
							'@type' => 'http://plazi.org/vocab/treatment#Treatment'
						);

						$data = jsonld_frame($doc, $frame);

						//echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
						//echo "\n";
				
						$output = new stdclass;
						$output->materialCitation = array();
								
						foreach ($data->{'@graph'}[0] as $k => $v)
						{
							//echo  $k . "\n";
					
					
							switch ($k)
							{
								case "publishedIn":
									//echo $v->{'@id'} . "\n";
							
									$output->publishedIn = $v->{'@id'};
									break;
							
								case "definesTaxonConcept":
						
									if (isset($v->{'dwc:lsidName'}))
									{
										//echo $v->{'dwc:lsidName'} . "\n";
									}
							
									if (isset($v->{'dwc:kingdom'}))
									{
										$output->kingdom = $v->{'dwc:kingdom'};
									}
									if (isset($v->{'dwc:phylum'}))
									{
										$output->phylum = $v->{'dwc:phylum'};
									}
									if (isset($v->{'dwc:class'}))
									{
										$output->class = $v->{'dwc:class'};
									}
									if (isset($v->{'dwc:order'}))
									{
										$output->order = $v->{'dwc:order'};
									}
									if (isset($v->{'dwc:family'}))
									{
										$output->family = $v->{'dwc:family'};
									}
									if (isset($v->{'dwc:genus'}))
									{
										$output->genus = $v->{'dwc:genus'};
									}
									if (isset($v->{'dwc:species'}))
									{
										$output->species = $v->{'dwc:species'};
									}
							
						
									break;
						
								case 'hasMaterialCitation':
									foreach ($v as $materialCitation)
									{
										if (isset($materialCitation->{'cito:hasCitedEntity'}))
										{
											$material_id = $materialCitation->{'cito:hasCitedEntity'}->{'@id'};
									
											$output->materialCitation[$material_id] = new stdclass;
								
											$terms = array();
								
											if (isset($materialCitation->{'cito:hasCitedEntity'}->{'dwc:collectionCode'}))
											{
												//echo $materialCitation->{'cito:hasCitedEntity'}->{'dwc:collectionCode'} . "\n";
												$output->materialCitation[$material_id]->collectionCode = $materialCitation->{'cito:hasCitedEntity'}->{'dwc:collectionCode'};
											}
																
											if (isset($materialCitation->{'cito:hasCitedEntity'}->{'dwc:specimenCode'}))
											{
												//echo $materialCitation->{'cito:hasCitedEntity'}->{'dwc:specimenCode'} . "\n";
												$output->materialCitation[$material_id]->specimenCode = $materialCitation->{'cito:hasCitedEntity'}->{'dwc:specimenCode'};
											}
				
											if (isset($materialCitation->{'cito:hasCitedEntity'}->{'dwc:typeStatus'}))
											{
												//echo $materialCitation->{'cito:hasCitedEntity'}->{'dwc:specimenCode'} . "\n";
												$output->materialCitation[$material_id]->typeStatus = $materialCitation->{'cito:hasCitedEntity'}->{'dwc:typeStatus'};
											}
									
									
									
										}
							
									}
						
									/*
									if (is_array($k))
									{
							
									}
									else
									{
										echo "Not array\n";
										exit();
									}
									*/
							
									break;
					
								default:
									break;
							}
						}
				
						if (count($output->materialCitation) > 0)
						{				
							//$output->materialCitation = array_unique($output->materialCitation);
					
							//print_r($output);
					
					
							//$keys[] 
					
							foreach ($output->materialCitation as $k => $v)					
							{
								$keys = array();
								$values = array();
						
								$keys[] = 'id';
								$values[] = '"' . $k . '"'; 
						
								// publication
								if (isset($output->publishedIn))
								{
									$keys[] = 'publishedIn';
									$values[] = '"' . addcslashes($output->publishedIn, '"'). '"'; 	
							
									if (preg_match('/https?:\/\/(dx.)?doi.org\/(?<doi>.*)/', $output->publishedIn, $m))					
									{
										$keys[] = 'doi';
										$values[] = '"' . addcslashes($m['doi'], '"'). '"'; 	
							
									}
								}
						
								// material
								if (isset($v->collectionCode))
								{
									$keys[] = 'collectionCode';
									$values[] = '"' . addcslashes($v->collectionCode, '"'). '"'; 						
								}

								if (isset($v->specimenCode))
								{
									$keys[] = 'specimenCode';
									$values[] = '"' . addcslashes($v->specimenCode, '"'). '"'; 						
								}

								if (isset($v->typeStatus))
								{
									$keys[] = 'typeStatus';
									$values[] = '"' . addcslashes($v->typeStatus, '"'). '"'; 						
								}
												
								// taxon
								$dwc = array(
									'kingdom',
									'phylum', 
									'class',	
									'order',	
									'family',	
									'genus',	
									'species',
									);
							
								foreach ($dwc as $dwc_key)
								{
									if (isset($output->{$dwc_key}))
									{
										$keys[] = "`" . $dwc_key . "`";
										$values[] = '"' . addcslashes($output->{$dwc_key}, '"'). '"'; 						
							
									}
								}

					
					
								//print_r($keys);
								//print_r($values);
						
								$sql = 'REPLACE INTO material(' . join(',', $keys) . ') VALUES(' . join(',', $values) . ');' . "\n";
								
								//echo $sql ;
								file_put_contents($directory1 . '.sql', $sql, FILE_APPEND);
							
							}
					
					
					
						}
				
					}
				}

				
			}
		}
	}
}






