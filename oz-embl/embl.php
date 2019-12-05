<?php

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(__FILE__) . '/nameparse.php');



//----------------------------------------------------------------------------------------
// Convert NCBI style date (e.g., "07-OCT-2015") to Y-m-d
function parse_ncbi_date($date_string)
{
	$date = '';
	
	if (false != strtotime($date_string))
	{
		// format without leading zeros
		$date = date("Y-m-d", strtotime($date_string));
	}	
	
	return $date;
}


//--------------------------------------------------------------------------------------------------
function get_value($line)
{
	$value = substr($line, 5);
	return $value;
}

//--------------------------------------------------------------------------------------------------
function get_feature(&$seq, $feature)
{
	switch ($seq['current_feature'])
	{
		case 'source':			
			if (preg_match('/^(?<key>.*)="(?<value>.*)"$/Uu', $feature, $mm))
			{
				switch ($mm['key'])
				{						
					case 'db_xref':
						if (preg_match('/taxon:(?<value>\d+)$/Uu', $mm['value'], $mmm))
						{
							$seq['source']['tax_id'] = (Integer)$mmm['value'];
						}
						break;
						
					case 'organism':
						$seq['organism'] = $mm['value'];
						break;
						
					default:
						$seq['source'][$mm['key']] = $mm['value'];
						break;						
				}
			}
			break;
			
		// genes as tags
		case 'gene':		
		case 'CDS':		
		case 'rRNA':
		case 'misc_RNA':
			if (preg_match('/^(?<key>.*)="(?<value>.*)"$/Uu', $feature, $mm))
			{
				switch ($mm['key'])
				{						
					case 'gene':
						$seq['gene'][] = $mm['value'];
						break;
					case 'product':
						$seq['product'][] = $mm['value'];
						break;
						
					default:
						break;						
				}
			}
			break;
			
			
		default:
			break;
	}
}

//--------------------------------------------------------------------------------------------------
function process_lat_lon(&$seq)
{
	if (!isset($seq['source']['lat_lon']))
	{
		return;
	}


	$matched = false;
	
	$lat_lon = $seq['source']['lat_lon'];

	if (preg_match ("/(N|S)[;|,] /", $lat_lon))
	{
		// it's a literal string description, not a pair of decimal coordinates.
		if (!$matched)
		{
			//  35deg12'07'' N; 83deg05'2'' W, e.g. DQ995039
			if (preg_match("/([0-9]{1,2})deg([0-9]{1,2})'(([0-9]{1,2})'')?\s*([S|N])[;|,]\s*([0-9]{1,3})deg([0-9]{1,2})'(([0-9]{1,2})'')?\s*([W|E])/", $lat_lon, $matches))
			{
				//print_r($matches);
			
				$degrees = $matches[1];
				$minutes = $matches[2];
				$seconds = $matches[4];
				$hemisphere = $matches[5];
				$lat = $degrees + ($minutes/60.0) + ($seconds/3600);
				if ($hemisphere == 'S') { $lat *= -1.0; };

				$seq['source']['latitude'] = $lat;

				$degrees = $matches[6];
				$minutes = $matches[7];
				$seconds = $matches[9];
				$hemisphere = $matches[10];
				$long = $degrees + ($minutes/60.0) + ($seconds/3600);
				if ($hemisphere == 'W') { $long *= -1.0; };
				
				$seq['source']['longitude'] = $long;
				
				$matched = true;
			}
		}
		if (!$matched)
		{
			
			list ($lat, $long) = explode ("; ", $lat_lon);

			list ($degrees, $rest) = explode (" ", $lat);
			list ($minutes, $rest) = explode ('.', $rest);

			list ($decimal_minutes, $hemisphere) = explode ("'", $rest);

			$lat = $degrees + ($minutes/60.0) + ($decimal_minutes/6000);
			if ($hemisphere == 'S') { $lat *= -1.0; };

			$seq['source']['latitude'] = $lat;

			list ($degrees, $rest) = explode (" ", $long);
			list ($minutes, $rest) = explode ('.', $rest);

			list ($decimal_minutes, $hemisphere) = explode ("'", $rest);

			$long = $degrees + ($minutes/60.0) + ($decimal_minutes/6000);
			if ($hemisphere == 'W') { $long *= -1.0; };
			
			$seq['source']['longitude'] = $long;
			
			$matched = true;
		}

	}
	
	if (!$matched)
	{			
		// N19.49048, W155.91167 [EF219364]
		if (preg_match ("/(?<lat_hemisphere>(N|S))(?<latitude>(\d+(\.\d+))), (?<long_hemisphere>(W|E))(?<longitude>(\d+(\.\d+)))/", $lat_lon, $matches))
		{
			$lat = $matches['latitude'];
			if ($matches['lat_hemisphere'] == 'S') { $lat *= -1.0; };
			
			$seq['source']['latitude'] = $lat;
			
			$long = $matches['longitude'];
			if ($matches['long_hemisphere'] == 'W') { $long *= -1.0; };
			
			$seq['source']['longitude'] = $long;
			
			$matched = true;

		}
	}
	
	if (!$matched)		
	{
		//13.2633 S 49.6033 E
		if (preg_match("/([0-9]+(\.[0-9]+)*) ([S|N]) ([0-9]+(\.[0-9]+)*) ([W|E])/", $lat_lon, $matches))
		{
			//print_r ($matches);
			
			$lat = $matches[1];
			if ($matches[3] == 'S') { $lat *= -1.0; };
			
			$seq['source']['latitude'] = $lat;

			$long = $matches[4];
			if ($matches[6] == 'W') { $long *= -1.0; };
			
			$seq['source']['longitude'] = $long;
			
			$matched = true;
		}
	}
	
	
	// AY249471 Palmer Archipelago 64deg51.0'S, 63deg34.0'W 
	if (!$matched)		
	{
		if (preg_match("/([0-9]{1,2})deg([0-9]{1,2}(\.\d+)?)'\s*([S|N]),\s*([0-9]{1,3})deg([0-9]{1,2}(\.\d+)?)'\s*([W|E])/", $lat_lon, $matches))
		{
			//print_r ($matches);
			
			$lat = $matches[1];
			if ($matches[3] == 'S') { $lat *= -1.0; };
			$seq['source']['latitude'] = $lat;

			$long = $matches[4];
			if ($matches[6] == 'W') { $long *= -1.0; };
			
			$seq['source']['longitude'] = $long;
			
			$matched = true;
		}
	}
	
	if (!$matched)
	{
		
		if (preg_match("/(?<latitude>\-?\d+(\.\d+)?),?\s*(?<longitude>\-?\d+(\.\d+)?)/", $lat_lon, $matches))
		{
			//print_r($matches);
			
			$seq['source']['latitude'] = $matches['latitude'];
			$seq['source']['longitude'] = $matches['longitude'];
		
			$matched = true;
		}
	}
	
	
}

//--------------------------------------------------------------------------------------------------
function process_dates(&$seq)
{
	if (isset($seq['created']))
	{
		if (false != strtotime($seq['created']))
		{
			$seq['created'] = date("Y-m-d", strtotime($seq['created']));
		}	
	
	}
	
	if (isset($seq['updated']))
	{
		if (false != strtotime($seq['updated']))
		{
			$seq['updated'] = date("Y-m-d", strtotime($seq['updated']));
		}	
	
	}	
}


//--------------------------------------------------------------------------------------------------
function process_locality(&$seq)
{
	$debug = false;
	
	
	if (isset($seq['source']['country']))
	{
		$country = $seq['source']['country'];

		$matches = array();	
		$parts = explode (":", $country);	
		$seq['source']['country'] = $parts[0];
		
		//echo $country . "\n";
					
		if (count($parts) > 1)
		{
			$seq['source']['locality'] = trim($parts[1]);
			// Clean up
			$seq['source']['locality'] = preg_replace('/\(?GPS/', '', $seq['source']['locality']);				
		}	
		
		if ($debug)
		{
			echo "Trying line " . __LINE__ . "\n";
		}

		// Handle AMNH stuff
		if (preg_match('/(?<latitude_degrees>[0-9]+)deg(?<latitude_minutes>[0-9]{1,2})\'\s*(?<latitude_hemisphere>[N|S])/i', $country, $matches))
		{
			if ($debug) { print_r($matches); }	


			$degrees = $matches['latitude_degrees'];
			$minutes = $matches['latitude_minutes'];
			$hemisphere = $matches['latitude_hemisphere'];
			$lat = $degrees + ($minutes/60.0);
			if ($hemisphere == 'S') { $lat *= -1.0; };

			$seq['source']['latitude']  = $lat;
		}

		if ($debug)
		{
			echo "Trying line " . __LINE__ . "\n";
		}
		if (preg_match('/(?<longitude_degrees>[0-9]+)deg(,\s*)?(?<longitude_minutes>[0-9]{1,2})\'\s*(?<longitude_hemisphere>[W|E])/i', $country, $matches))
		{
		
			if ($debug) { print_r($matches); }	
			
			$degrees = $matches['longitude_degrees'];
			$minutes = $matches['longitude_minutes'];
			$hemisphere = $matches['longitude_hemisphere'];
			$long = $degrees + ($minutes/60.0);
			if ($hemisphere == 'W') { $long *= -1.0; };
			
			$seq['source']['longitude']  = $long;
		}
	
		if ($debug)
		{
			echo "Trying line " . __LINE__ . "\n";
		}

		if (isset($seq['source']['locality']))
		{
			$matched = false;
		
			// AY249471 Palmer Archipelago 64deg51.0'S, 63deg34.0'W 
			if (preg_match("/(?<latitude_degrees>[0-9]{1,2})deg(?<latitude_minutes>[0-9]{1,2}(\.\d+)?)'\s*(?<latitude_hemisphere>[S|N]),\s*(?<longitude_degrees>[0-9]{1,3})deg(?<longitude_minutes>[0-9]{1,2}(\.\d+)?)'\s*(?<longitude_hemisphere>[W|E])/", $seq['source']['locality'], $matches))
			{	
			
				if ($debug) { print_r($matches); }	

				$degrees = $matches['latitude_degrees'];
				$minutes = $matches['latitude_minutes'];
				$hemisphere = $matches['latitude_hemisphere'];
				$lat = $degrees + ($minutes/60.0);
				if ($hemisphere == 'S') { $lat *= -1.0; };

				$seq['source']['latitude']  = $lat;

				$degrees = $matches['longitude_degrees'];
				$minutes = $matches['longitude_minutes'];
				$hemisphere = $matches['longitude_hemisphere'];
				$long = $degrees + ($minutes/60.0);
				if ($hemisphere == 'W') { $long *= -1.0; };
				
				$seq['source']['longitude']  = $long;
				
				$matched = true;
			}
			
			if (!$matched)
			{
				
				//26'11'24N 81'48'16W
				
				//echo $seq['source']['locality'] . "\n";
				
				if (preg_match("/
				(?<latitude_degrees>[0-9]{1,2})
				'
				(?<latitude_minutes>[0-9]{1,2})
				'
				((?<latitude_seconds>[0-9]{1,2})
				'?)?
				(?<latitude_hemisphere>[S|N])
				\s+
				(?<longitude_degrees>[0-9]{1,3})
				'
				(?<longitude_minutes>[0-9]{1,2})
				'
				((?<longtitude_seconds>[0-9]{1,2})
				'?)?
				(?<longitude_hemisphere>[W|E])
				/x", $seq['source']['locality'], $matches))
				{
					if ($debug) { print_r($matches); }	
	
					
					$degrees = $matches['latitude_degrees'];
					$minutes = $matches['latitude_minutes'];
					$seconds = $matches['latitude_seconds'];
					$hemisphere = $matches['latitude_hemisphere'];
					$lat = $degrees + ($minutes/60.0) + ($seconds/3600);
					if ($hemisphere == 'S') { $lat *= -1.0; };
	
					$seq['source']['latitude'] = $lat;
	
					$degrees = $matches['longitude_degrees'];
					$minutes = $matches['longitude_minutes'];
					$seconds = $matches['longtitude_seconds'];
					$hemisphere = $matches['longitude_hemisphere'];
					$long = $degrees + ($minutes/60.0) + ($seconds/3600);
					if ($hemisphere == 'W') { $long *= -1.0; };
					
					$seq['source']['longitude'] = $long;
					
					//print_r($seq);
					
					//exit();
					
					$matched = true;
				}
			}
			//exit();

			
		}
		
		if ($debug)
		{
			echo "Trying line " . __LINE__ . "\n";
		}
		
		
		//(GPS: 33 38' 07'', 146 33' 12'') e.g. AY281244
		if (preg_match("/\(GPS:\s*([0-9]{1,2})\s*([0-9]{1,2})'\s*([0-9]{1,2})'',\s*([0-9]{1,3})\s*([0-9]{1,2})'\s*([0-9]{1,2})''\)/", $country, $matches))
		{
			if ($debug) { print_r($matches); }	
			
			$lat = $matches[1] + $matches[2]/60 + $matches[3]/3600;
			
			// OMG
			if ($seq['source']['country'] == 'Australia')
			{
				$lat *= -1.0;
			}
			$long = $matches[4] + $matches[5]/60 + $matches[6]/3600;

			$seq['source']['latitude']  = $lat;
			$seq['source']['longitude']  = $long;
			
		}
		
		
	}
	
	if ($debug)
	{
		echo "Trying line " . __LINE__ . "\n";
	}

	
	// Some records have lat and lon in isolation_source, e.g. AY922971
	if (isset($seq['source']['isolation_source']))
	{
		$isolation_source = $seq['source']['isolation_source'];
		$matches = array();
		if (preg_match('/([0-9]+\.[0-9]+) (N|S), ([0-9]+\.[0-9]+) (W|E)/i', $isolation_source, $matches))
		{
			if ($debug) { print_r($matches); }	
			
			$seq['source']['latitude'] = $matches[1];
			if ($matches[2] == 'S')
			{
				$seq['source']['latitude'] *= -1;
			}
			$seq['source']['longitude'] = $matches[3];
			if ($matches[4] == 'W')
			{
				$seq['source']['longitude'] *= -1;
			}
			
			if  (!isset($seq['source']['locality']))
			{
				$seq['source']['locality'] = $seq['source']['isolation_source'];
			}
		}
	}
}	


//--------------------------------------------------------------------------------------------------
function post_process(&$seq)
{
	global $config;
	
	process_dates($seq);
	process_lat_lon($seq);
	process_locality($seq);
	
	if (isset($seq['source']['latitude']) && isset($seq['source']['longitude']))
	{
		$seq['source']['longitude'] = (float)$seq['source']['longitude'];
		$seq['source']['latitude'] = (float)$seq['source']['latitude'];
			
		$seq['source']['geometry'] = new stdclass;
		$seq['source']['geometry']->type = "Point";
		$seq['source']['geometry']->coordinates = array();
		$seq['source']['geometry']->coordinates[] = (float)$seq['source']['longitude'];
		$seq['source']['geometry']->coordinates[] = (float)$seq['source']['latitude'];
	}
	else
	{
		unset($seq['source']['latitude']);
		unset($seq['source']['longitude']);
	}
	
	$reference_id = '';
	
	$n = count($seq['references']);
	for($i=0;$i<$n;$i++)
	{
		$seq['references'][$i]['type'] = 'unknown';
		
		$seq['references'][$i]['unstructured'] = '';
		
		
		if ($reference_id == '')
		{
			if (isset($seq['references'][$i]['DOI']))
			{
				$reference_id = $seq['references'][$i]['DOI'];
			}
		}
		
		if ($reference_id == '')
		{
			if (isset($seq['references'][$i]['PMID']))
			{
				$reference_id = $seq['references'][$i]['PMID'];
			}
		}		
				
	
		$title = $seq['references'][$i]['title'];
		
		//echo "Title raw=$title|\n";
				
		$title = preg_replace('/^"/', '', $title);
		$title = preg_replace('/";$/', '', $title);
		
		//echo "Title post=$title|\n";
		
		if ($title == '')
		{
			unset($seq['references'][$i]['title']);
		}
		else
		{
			$seq['references'][$i]['title'] = $title;
			$seq['references'][$i]['unstructured'] = $title . ' ';
		}
		
		if (isset($seq['references'][$i]['citation']))
		{			
			if (preg_match('/(?<journal>.*)\s+(?<volume>\d+)(\((?<issue>.*)\))?:(?<spage>[A-Z]?\d+)-(?<epage>[A-Z]?\d+)\((?<year>[0-9]{4})\)/', $seq['references'][$i]['citation'], $m))
			{				
				$seq['references'][$i]['type'] = 'article-journal';
				
				$seq['references'][$i]['container-title'] = $m['journal'];
				
				// ISSN lookup
				$journal_to_issn = array(
					'Kew Bull.' 	=> '0075-5974',
					'Lindleyana'	=> '0889-258X',
					'Phytotaxa'		=> '1179-3155',
					'Taxon'			=> '0040-0262',
				);
				
				if (isset($journal_to_issn[$m['journal']]))
				{
					$seq['references'][$i]['ISSN'] = array($journal_to_issn[$m['journal']]);
				}
				
				
				$seq['references'][$i]['volume'] = $m['volume'];
				if ($m['issue'] != '')
				{
					$seq['references'][$i]['issue'] = $m['issue'];
				}
				$seq['references'][$i]['page-first'] = $m['spage'];
				$seq['references'][$i]['page'] = $m['spage'];
				if ($m['epage'] != '')
				{
					$seq['references'][$i]['page'] .= '-' . $m['epage'];
				}
				$seq['references'][$i]['issued'] = array();
				$seq['references'][$i]['issued']['date-parts'] = [];
				$seq['references'][$i]['issued']['date-parts'][0] = array((Integer)$m['year']);
			}
			
			$seq['references'][$i]['unstructured'] .= $seq['references'][$i]['citation'];
			unset($seq['references'][$i]['citation']);
		}
		
		$authorstring = $seq['references'][$i]['authorstring'];
		$authorstring = preg_replace('/;$/', '', $authorstring);
		$authorstring = preg_replace('/,\s+/', ',', $authorstring);
		if ($authorstring != '')
		{
				$authors = explode(",", $authorstring);
				
				$seq['references'][$i]['author'] = array();
				
				foreach ($authors as $a)
				{
					$pos = strpos($a, ' ');
					if ($pos === false)
					{
					}
					else
					{
						$a = substr($a, 0, $pos) . ',' . substr($a, $pos);
					}				
				
					$parts = parse_name($a);					
					$author = new stdClass();
					if (isset($parts['last']))
					{
						$author->family = $parts['last'];
					}
					if (isset($parts['first']))
					{
						$author->given = $parts['first'];
						$author->given = str_replace('.', '. ', $author->given);
						$author->given = trim($author->given);
						
						if (array_key_exists('middle', $parts))
						{
							$author->given .= ' ' . $parts['middle'];
						}
					}
					
					if (isset($author->given))
					{
						$author->literal = $author->given;
						if (isset($author->family))
						{
							$author->literal .= ' ' . $author->family;
						}
						
					}
					else
					{
						$author->literal = $a;
					}
					
					
					$seq['references'][$i]['author'][] = $author;					

				}
				
				
		}
		unset($seq['references'][$i]['authorstring']);
		
	}
	
	if ($reference_id == '')
	{
		$reference_id = md5($seq['references'][0]['unstructured']);
	}
	
	// Gene and product names
	if (isset($seq['gene']))
	{
		$seq['gene'] = array_unique($seq['gene']);
	}
	if (isset($seq['product']))
	{
		$seq['product'] = array_unique($seq['product']);
	}

	unset($seq['feature']);
	unset($seq['current_feature']);	
	
	// BOLD	
	if (isset($seq['comment']))
	{
		foreach ($seq['comment'] as $c)
		{
			if (preg_match('/Barcode Index Number\s+(::\s+)?(?<bin>.*)$/', $c, $m))
			{
				if (!isset($seq['bold']))
				{
					$seq['bold'] = array();
				}
				$seq['bold']['bin'] = trim($m['bin']);
			}
			if (preg_match('/Order Assignment\s+(::\s+)?(?<order>.*)$/', $c, $m))
			{
				if (!isset($seq['bold']))
				{
					$seq['bold'] = array();
				}
				$seq['bold']['order'] = trim($m['order']);
			}
			if (preg_match('/iBOL Working Group\s+(::\s+)?(?<ibol>.*)$/', $c, $m))
			{
				if (!isset($seq['bold']))
				{
					$seq['bold'] = array();
				}
				$seq['bold']['ibol'] = trim($m['ibol']);
			}
		}
	}
	
	// make comment a string
	if (isset($seq['comment']))
	{
		$comment = join("\n", $seq['comment']);
		unset($seq['comment']);
		$seq['comment'] = $comment;
	}
	
	
	// collection date
	if (isset($seq['source']['collection_date']))
	{
		$date = parse_ncbi_date($seq['source']['collection_date']);
		if ($date != '')
		{
		 	$seq['source']['collection_date'] = $date;
		}
	}
	
	
	
	// specimen code
	if (isset($seq['source']['specimen_voucher']))
	{
		// Try to interpret them
		$matched = false;
		
		/*
		// ABTC99324
		if (!$matched)
		{
			if (preg_match('/^(?<institutionCode>[A-Z]+)(?<catalogNumber>\d+)$/', $seq['source']['specimen_voucher'], $m))
			{
				$seq['source']['institutionCode'] 	=  $m['institutionCode'];
				$seq['source']['catalogNumber'] 	=  $m['catalogNumber'];
				$matched = true;
			}
		}
		

		// TM<ZAF>40766
		if (!$matched)
		{
			if (preg_match('/^(?<institutionCode>(?<prefix>[A-Z]+)\<[A-Z]+\>)(?<catalogNumber>\d+)$/', $seq['source']['specimen_voucher'], $m))
			{
				$seq['source']['institutionCode'] 	=  $m['institutionCode'];
				$seq['source']['catalogNumber'] 	=  $m['catalogNumber'];
				$matched = true;
			}
		}
									
		if (!$matched)
		{
			if (preg_match('/^(?<institutionCode>[A-Z]+)[:|\s](?<collectionCode>.*)[:|\s](?<catalogNumber>\d+)$/', $seq['source']['specimen_voucher'], $m))
			{
				$seq['source']['institutionCode'] 	=  $m['institutionCode'];
				$seq['source']['collectionCode'] 	=  $m['collectionCode'];
				$seq['source']['catalogNumber'] 	=  $m['catalogNumber'];
				$matched = true;
			}
		}
		
		// ZISP TX-00097
		if (!$matched)
		{
			if (preg_match('/^(?<institutionCode>[A-Z]+)[\s|:]?(?<catalogNumber>[A-Z]+[-]?\d+)$/', $seq['source']['specimen_voucher'], $m))
			{
				$seq['source']['institutionCode'] 	=  $m['institutionCode'];
				$seq['source']['catalogNumber'] 	=  $m['catalogNumber'];
				$matched = true;
			}
		}
		

		if (!$matched)
		{
			if (preg_match('/^(?<institutionCode>[A-Z]+)[\s|:]?(?<catalogNumber>\d+([a-z]|\.\d+)?)$/', $seq['source']['specimen_voucher'], $m))
			{
				$seq['source']['institutionCode'] 	=  $m['institutionCode'];
				$seq['source']['catalogNumber'] 	=  $m['catalogNumber'];
				$matched = true;
			}
		}
		*/
	}
	
	/*
	$occurrence_id = '';
	if ($occurrence_id == '')
	{
		if (isset($seq['source']['specimen_voucher']))
		{
			$occurrence_id = $reference_id . '-' . $seq['source']['specimen_voucher'];
		}
	}
	if ($occurrence_id == '')
	{
		if (isset($seq['source']['isolate']))
		{
			$occurrence_id = $reference_id . '-' . $seq['source']['isolate'];
		}
	}
	
	if ($occurrence_id != '')
	{
		$seq['source']['occurrence_id'] = $occurrence_id;
	}
	*/
	
}

//--------------------------------------------------------------------------------------------------
function init_seq(&$seq)
{
	$seq = array();
	$seq['_id'] = '';
	$seq['accession'] = '';
	$seq['version'] = 0;
	//$seq['gi'] = 0;
	$seq['definition'] = '';
	$seq['organism'] = '';
	$seq['references'] = array();
	$seq['lineage'] = array();
	$seq['barcode'] = false;
}



//--------------------------------------------------------------------------------------------------

/*
ENV:Environmental Samples   
FUN:Fungi                   
HUM:Human                   
INV:Invertebrates           
MAM:Other Mammals           
MUS:Mus musculus            
PHG:Bacteriophage           
PLN:Plants                  
PRO:Prokaryotes           
ROD:Rodents                 
SYN:Synthetic               
TGN:Transgenic              
UNC:Unclassified            
VRL:Viruses                 
VRT:Other Vertebrates       
*/


function main($bulk_override = false)
{
	global $config;
	global $couch;

	$filename = '';
	if ($_SERVER['argc'] < 2)
	{
		echo "Usage: embl.php <file>\n";
		exit(1);
	}
	else
	{
		$filename = $_SERVER['argv'][1];
	}
	
	$file_handle = fopen($filename, "r");
	
	// CouchDB
	$docs = new stdclass;
	$docs->docs = array();
	
	$bulk_size = 1000;
	$bulk_count = 0;	
	
	
	$seq = array();
	init_seq($seq);
	
	$in_sequence = false;
	
	while (!feof($file_handle)) 
	{
	   $line = fgets($file_handle);
	   //$line = trim($line);
	   
	   //echo $line . "\n";
	   
	   $field = substr($line, 0, 2);
	   
	   switch ($field)
	   {
			// ID
			case 'ID':
				if (preg_match('/ID\s+(.*);\s+SV (?<version>\d+);/Uu', $line, $m))
				{
					$seq['version'] = (Integer)$m['version'];
				}
				break;
		  
			// Accession
			case 'AC':
				$accession = get_value($line);
				
				$pos = strpos($accession, ';');
				if ($pos === false)
				{
				}
				else
				{
					$accession = substr($accession, 0, $pos);
				}
				
				$seq['accession'] = $accession;
				$seq['_id'] = $seq['accession'] . '.' . $seq['version'];
				break;
				
			// Description
			case 'DE':
				if ($seq['definition'] != '')
				{
					$seq['definition'] .= ' ';
				}
				$seq['definition'] .= preg_replace('/\n/', '', get_value($line));
				break;
				
			// Comment
			case 'CC':
				if (!isset($seq['comment']))
				{
					$seq['comment'] = array();
				}
				$seq['comment'][] = get_value($line);
				break;
				
				
			// Lineage
			case 'OC':
				$lineage = trim(get_value($line));
				$lineage = preg_replace('/\.$/', '', $lineage);
				$lineage = preg_replace('/; /', ';', $lineage);
				$parts = explode(';', $lineage);
				foreach ($parts as $part)
				{
					if ($part != '')
					{
						$seq['lineage'][] = trim($part);
					}
				}
				break;
				
			// Dates
			case 'DT': 
				// DT   19-SEP-2007 (Rel. 93, Created)
	
				if (preg_match('/DT\s+(?<date>[0-9]{2}-[A-Z]{3}-[0-9]{4})\s+\(Rel. \d+, Created\)/Uu', $line, $m))
				{
					$seq['created'] = $m['date'];
				}
				if (preg_match('/DT\s+(?<date>[0-9]{2}-[A-Z]{3}-[0-9]{4})\s+\(Rel. \d+, Last updated/Uu', $line, $m))
				{
					$seq['updated'] = $m['date'];
				}
				break;
				
			// Keyword
			case 'KW':
				if (get_value($line) == "BARCODE.")
				{
					$seq['barcode'] = true;
				}
				break;
	
			// Reference
			case 'RN':
				// Start of reference
				$seq['references'][] = array();
				break;
	   
			// Reference ids
			case 'RX':
				$ref = count($seq['references']) - 1;
				if (preg_match('/RX\s+DOI; (?<id>.*)\.$/Uu', $line, $m))
				{
					$doi = $m['id'];
					$doi = preg_replace('/\s*https?:\/\/(dx\.)?doi.org\//', '', $doi);
					$seq['references'][$ref]['DOI'] = $doi;
				}
				if (preg_match('/RX\s+PUBMED; (?<id>.*)\.$/Uu', $line, $m))
				{
					$seq['references'][$ref]['PMID'] = (Integer)$m['id'];
				}
				break;

			case 'RC':
				$ref = count($seq['references']) - 1;
				if (preg_match('/RC\s+DOI:(?<id>.*)/', $line, $m))
				{
					$doi = $m['id'];
					$seq['references'][$ref]['DOI'] = $doi;
				}
				break;
				
			// Reference authors
			case 'RA':
				$ref = count($seq['references']) - 1;
				
				if(!isset($seq['references'][$ref]['authorstring']))
				{
					$seq['references'][$ref]['authorstring'] = '';
				}					
				
				if ($seq['references'][$ref]['authorstring'] != '')
				{
					$seq['references'][$ref]['authorstring'] .= ' ';
				}
				$seq['references'][$ref]['authorstring'] .= get_value($line);
				break;
	  
			// Reference title
			case 'RT':
				$ref = count($seq['references']) - 1;
				
				if(!isset($seq['references'][$ref]['title']))
				{
					$seq['references'][$ref]['title'] = '';
				}			
				
				if ($seq['references'][$ref]['title'] != '')
				{
					$seq['references'][$ref]['title'] .= ' ';
				}
				$seq['references'][$ref]['title'] .= preg_replace('/\n/', '', get_value($line));
				break;
	 
			// Reference citation
			case 'RL':
				$ref = count($seq['references']) - 1;
				
				if(!isset($seq['references'][$ref]['citation']))
				{
					$seq['references'][$ref]['citation'] = '';
				}					
				
				if ($seq['references'][$ref]['citation'] != '')
				{
					$seq['references'][$ref]['citation'] .= ' ';
				}
				$seq['references'][$ref]['citation'] .= preg_replace('/\n/', '', get_value($line));
				break;
	 
				
			// Features (we only want source)
			case 'FT':
				if (preg_match('/^FT\s{3}(?<feature>[a-zA-Z0-9].*)\s+/Uu', $line, $m))
				{
					if (isset($seq['feature']) && ($seq['feature'] != ''))
					{
						get_feature($seq, $seq['feature']);
					}
							
					$seq['current_feature'] = $m['feature'];
					$seq['feature'] = '';
				}
				
				if (preg_match('/^FT\s{19}\/(?<value>.*)$/Uu', $line, $m))
				{
					if ($seq['feature'] != '')
					{
						get_feature($seq, $seq['feature']);
					}
				
					$seq['feature'] = $m['value'];
				}
				if (preg_match('/^FT\s{19}(?<value>[^\/].*)$/Uu', $line, $m))
				{
					$seq['feature'] .= ' ' . $m['value'];
				}
				break;
				
				
			case 'XX':
				// End of an object, such as reference or feature table
				
				// Handle case where feature table has only source, and so we will 
				// hit XX rather than another feature (which about code uses to ensure last
				// feature is added to feature list.
				// See FJ906628 for an example of this 
				if (isset($seq['feature']) &&  ($seq['feature']!= ''))
				{
					get_feature($seq, $seq['feature']);
				}
				break;
				
				
			// Sequence
			case 'SQ':
				$in_sequence = true;
				$seq['sequence'] = '';
				break;
				
			// End of record
			case '//';
				$in_sequence = false;
			
				post_process($seq);
				
				if ($bulk_override)
				{
					// make up a dummy revision flag
					$seq['_rev'] = '1-' . uniqid();
				}
				
				
				echo json_encode($seq, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

				$add = true;
				
				/*
				// filter out any sequences not relevant, e.g. no geo or specimen data
				$add = false;
				
				//$add = true; // add everything :O
				
				if (isset($seq['source']['country']))
				{
					$add = true;
				}
				if (isset($seq['source']['locality']))
				{
					$add = true;
				}
				if (isset($seq['source']['latitude']))
				{
					$add = true;
				}
				if (isset($seq['source']['specimen_voucher']))
				{
					$add = true;
				}
				if (isset($seq['source']['isolate']))
				{
					$add = true;
				}
				if (isset($seq['source']['isolation_source']))
				{
					$add = true;
				}
				if (isset($seq['source']['host']))
				{
					$add = true;
				}
				
				
				if ($add) {}
				
				*/
				
				if ($add)
				{
					$docs->docs [] = $seq;
				}
				//echo ".";
				
				if (count($docs->docs ) == $bulk_size)
				{
					if ($bulk_override)
					{
						$docs->new_edits = false;
					}
				
					echo "CouchDB...";
					$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . '/_bulk_docs', json_encode($docs));
					$bulk_count += $bulk_size;
					echo "\nUploaded... total=$bulk_count\n";
				
					$docs->docs  = array();
				}
	
				init_seq($seq);
				break;
				
			default:
				if ($in_sequence)
				{
					$line = preg_replace('/[\s|0-9]/', '', $line);
					$seq['sequence'] .= $line;
				}
				break;
		}
	   
	 }
	 // Make sure we load the last set of docs
	if (count($docs->docs ) != 0)
	{
		echo "CouchDB...\n";
		
		
		if ($bulk_override)
		{
			$docs->new_edits = false;
		}
		
		$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . '/_bulk_docs', json_encode($docs));		
		echo $resp;
		
		
		$bulk_count += count($docs->docs);
		echo "\nUploaded... total=$bulk_count\n";

	
		$docs->docs  = array();
	}

	 
}

// see http://wiki.apache.org/couchdb/HTTP_Document_API
$bulk_override = true; // true if we want to bulk upload and overwrite previous documents 

main($bulk_override);

?>