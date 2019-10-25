<?php

error_reporting(E_ALL);

require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(__FILE__) . '/fingerprint.php');

require_once (dirname(__FILE__) . '/compare.php');


$force = true;

//----------------------------------------------------------------------------------------
function get($url, $accept = 'application/json')
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER => array('Accept: ' . $accept) 
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------
function find_doi($string)
{
	$doi = '';
	
	$url = 'https://mesquite-tongue.glitch.me/search?q=' . urlencode($string);
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	if ($data != '')
	{
		$obj = json_decode($data);
		
		//print_r($obj);
		
		if (count($obj) == 1)
		{
			if ($obj[0]->match)
			{
				$doi = $obj[0]->id;
			}
		}
		
	}
	
	return $doi;
			
}	

//----------------------------------------------------------------------------------------
// Fetch an individual work from an ORCID profile
function orcid_work_fetch($orcid_work, $lookup_works = false)
{
	$data = null;
		
	$url = 'https://pub.orcid.org/v2.1/' . $orcid_work;	
	$json = get($url, 'application/vnd.citationstyles.csl+json');
			
	if ($json != '')
	{
		$data = json_decode($json);		
	}
	
	return $data;
}

//----------------------------------------------------------------------------------------
function orcid_fetch($orcid, $lookup_works = false)
{
	global $force;
	global $couch;
	
	$data = null;
		
	$url = 'https://pub.orcid.org/v2.1/' . $orcid;	
	$json = get($url, 'application/orcid+json');
	
	// file_put_contents($orcid . '.json', $json);
		
	if ($json != '')
	{
		$data = json_decode($json);		
		
		
		// get author details
		$person = new stdclass;
		
		$person->orcid = $orcid;
		
		$parts = array();
		
		if (isset($data->person))
		{
			if (isset($data->person->name->{'given-names'}))
			{
				$person->given = $data->person->name->{'given-names'}->value;
				
				$parts[] = $person->given;
			}
			if (isset($data->person->name->{'family-name'}))
			{
				$person->family = $data->person->name->{'family-name'}->value;
				$parts[] =  $person->family;
			}
			
		}
		
		$person->literal = join(' ', $parts);
		//print_r($parts);
		
		print_r($person);
		//exit();
		
		
		
		
		// API 2.1 has API to access individual works via "putcode"
		if (isset($data->{'activities-summary'}))
		{
			if (isset($data->{'activities-summary'}->{'works'}))
			{
				foreach ($data->{'activities-summary'}->{'works'}->{'group'} as $work)
				{
					
					foreach ($work->{'work-summary'} as $summary)
					{
						
						$doi = '';
						
						if (isset($work->{'external-ids'}))
						{
							if (isset($work->{'external-ids'}->{'external-id'}))
							{
								foreach ($work->{'external-ids'}->{'external-id'} as $external_id)
								{
									if ($external_id->{'external-id-type'} == 'doi')
									{
										$doi = $external_id->{'external-id-value'};
									}
								}
							}
						}
						
						
						// fetch individual works
						
						$work = orcid_work_fetch($orcid . '/work/' . $summary->{'put-code'});

						
						
						// cleaning...
						
						if (isset($work->title))
						{
							//$work->title = preg_replace('/\\\less/', '', $work->title);
							//$work->title = preg_replace('/\\\greater/', '', $work->title);
							
							/*
							$work->title = str_replace('$i$', '<i>', $work->title);
							$work->title = str_replace('$/i$', '</i>', $work->title);
							$work->title = str_replace('$em$', '<em>', $work->title);
							$work->title = str_replace('$/em$', '</em>', $work->title);
							$work->title = str_replace('$strong$', '<strong>', $work->title);
							$work->title = str_replace('$/strong$', '</strong>', $work->title);
							*/
							
							$work->title = str_replace('\less', '<', $work->title);
							$work->title = str_replace('\greater', '>', $work->title);
							
							$work->title = str_replace('{\&}amp\mathsemicolon', '&', $work->title);
							$work->title = str_replace('{HeadingRunIn}', '"HeadingRunIn"', $work->title);
							
							
							$work->title = str_replace('$', '', $work->title);
							
						}
						
						
						// do we need to look for a DOI?						
						if (!isset($work->DOI) && $lookup_works)
						{
							$terms = array();
						
							if (isset($work->author))
							{
								foreach ($work->author as $author)
								{
									if (isset($author->family))
									{
										$terms[] = $author->family;
									}							
								}					
							}
					
							if (isset($work->issued))
							{
								if (isset($work->issued->{'date-parts'}))
								{
									$terms[] = $work->issued->{'date-parts'}[0][0];
								}
							}
											
							if (isset($work->title))
							{
								$terms[] = strip_tags($work->title);
							}
							if (isset($work->{'container-title'}))
							{
								$terms[] = $work->{'container-title'};
							}
							if (isset($work->volume))
							{
								$terms[] = $work->volume;
							}
							if (isset($work->page))
							{
								$terms[] = $work->page;
							}
												
							// echo join(' ', $terms);
							
							$doi = find_doi(join(' ', $terms));
							if ($doi != '')
							{
								$work->DOI = strtolower($doi);
							}							
							
						}
						
						// figure out which author gets ORCID
						if (isset($work->author))
						{
							$n = count($work->author);
							
							if ($n == 1)
							{
								// assume there's only one author of the paper,
								//although this need not be the case :(. Some people,
								// e.g. 0000-0003-4851-015X Mark Newman just list themselves
								// and no co-authors in tier ORCID record(!)
								$work->author[0]->ORCID = $orcid;
							}
							else
							{
								$min_d = 1;
								$hit = -1;
								
								for ($i = 0; $i < $n; $i++)
								{
									$parts = array();
									
									if (isset($work->author[$i]->given))
									{
										$parts[] = $work->author[$i]->given;
									}
									if (isset($work->author[$i]->family))
									{
										$parts[] = $work->author[$i]->family;
									}
									
									$work->author[$i]->literal = join(' ', $parts);
									
									$d = compare_as_array($person->literal, $work->author[$i]->literal);
									
									if ($d->jaccard < $min_d)
									{
										$min_d = $d->jaccard ;
										$hit = $i;								
									}
									
									/*
									$d = levenshtein(finger_print($person->literal), finger_print($work->author[$i]->literal));

									if ($d < $min_d)
									{
										$min_d = $d;
										$hit = $i;
									}
									*/
								}
								
								if ($hit != -1)
								{
									$work->author[$hit]->ORCID = $orcid;
								}
										
							}
						
						
						}
						
						
						//print_r($work);
						
						// Store work as a message
						$work_doc = new stdclass;						
						$work_doc->_id = $orcid . '/work/' . $summary->{'put-code'};
						$work_doc->{'message-format'} = 'application/vnd.citationstyles.csl+json';
						$work_doc->message = $work;
						
						// store work in CouchDB
						$exists = $couch->exists($work_doc->_id);

						// add to database
						if (!$exists)
						{
							$couch->add_update_or_delete_document($work_doc, $work_doc->_id, 'add');	
						}
						else
						{
							if ($force)
							{
								$couch->add_update_or_delete_document($work_doc, $work_doc->_id, 'update');
							}
						}
						
						// generate triples work -> author -> name + position so we can start to merge authors...
		
					}
				}
			}
		
		}
	}
	
	return $data;
}


$orcids = array('0000-0003-4851-015X'); // Mark Newman

$orcids = array('0000-0002-9404-3807'); // José Melo

$orcids = array('0000-0001-6530-7140');

$orcids = array(
'0000-0002-8758-9326', // Alessandro Rapini
'0000-0001-9036-0912', // Tatiana Konno
);

// ORCIDs for big fungal paper 10.1007/s13225-017-0386-0
$orcids = array(
'0000-0003-3288-3971',
'0000-0002-9638-0532',
'0000-0002-6424-0834',
'0000-0001-7047-412X',
'0000-0001-6367-9784',
'0000-0002-1717-9532',
'0000-0001-9127-0783',
'0000-0001-9793-2303',
'0000-0002-3176-8675',
);

$orcids = array(
'0000-0002-3604-6532', // Per Magnus Jørgensen 
);

$orcids = array(
'0000-0003-3926-0478', // Manuel Rodrigues
);

$orcids = array(
'0000-0002-8758-9326', // Alessandro Rapini
'0000-0001-9036-0912', // Tatiana Konno
);

$orcids = array(
'0000-0002-1370-044X',
'0000-0002-7887-9954',
);

$orcids = array(
'0000-0003-4783-3125',
'0000-0003-4783-3125',
'0000-0002-7423-899X',
'0000-0003-4783-3125',
'0000-0003-4783-3125',
'0000-0002-1440-4810',
'0000-0002-7423-899X',
'0000-0002-3179-4005',
'0000-0003-4783-3125',
'0000-0002-7204-0744',
'0000-0002-8726-3561',
);

$orcids = array(
//'0000-0003-1540-0165',
//'0000-0001-5028-0686',
'0000-0003-3891-9904',
'0000-0002-7570-2811'
);

$orcids = array(
'0000-0002-6173-6754', // Salvador Talavera Lozano (459 works, many duplicates)
);

$orcids = array(
//'0000-0003-2707-0335', // Sigrid Liede-Schumann
'0000-0003-2831-6384', // Gildas Gâteblé
);

$orcids = array(
'0000-0001-9127-0783',
'0000-0003-3076-4709',
'0000-0002-0573-4340',
);

$orcids = array(
'0000-0001-8111-8075'
);

$orcids = array(
'0000-0003-2909-6205'
);

$orcids=array(
/*"0000-0003-1559-4449",
"0000-0002-2640-4818",
"0000-0003-4783-3125",
"0000-0003-0104-7524",
"0000-0002-8408-8841",
"0000-0001-5491-7568",
"0000-0002-2501-456X",
"0000-0002-7887-9954",*/
"0000-0002-1614-4821",
"0000-0001-8199-6003",
"0000-0002-5963-349X",
"0000-0003-0691-0323",
"0000-0002-1363-9781",
"0000-0002-0849-9841",
"0000-0001-6238-2743",
"0000-0002-1518-9470",
"0000-0001-5105-7152",
"0000-0002-8181-5118",
"0000-0003-3844-8081",
"0000-0002-7066-0608",
);

$orcids=array(
//'0000-0001-8031-2925'
'0000-0002-5917-3243',
'0000-0001-5171-4140',
'0000-0002-1223-0175',
);

$orcids=array(
'0000-0002-9059-7255'
);

// from file
if (0)
{
	$orcids = array();
	$filename = 'orcid-from-doi.txt';
	
	$file_handle = fopen($filename, "r");
	while (!feof($file_handle)) 
	{
		$orcids[] = trim(fgets($file_handle));
	}
}

$doi_lookup = false;

$force = true;
$force = true;

$count = 1;

foreach ($orcids as $orcid)
{

	$exists = $couch->exists($orcid);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have $orcid already\n";
		$go = false;
	}

	if ($go)
	{
		$data = orcid_fetch($orcid);
		
		$doc = new stdclass;
		$doc->_id = $orcid;		
		$doc->{'message-format'} = 'application/orcid+json';
		$doc->message = $data;		

		// add to database
		if (!$exists)
		{
			$couch->add_update_or_delete_document($doc, $doc->_id, 'add');	
		}
		else
		{
			if ($force)
			{
				$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
			}
		}
	
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo '...sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
		}
	}	
}

?>
