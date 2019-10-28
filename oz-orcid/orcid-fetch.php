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


// from CouchDB
if (1)
{
	$json = '{
    "rows": [
        {
            "key": "0000-0001-5300-2702",
            "value": 1
        },
        {
            "key": "0000-0001-5347-5403",
            "value": 1
        },
        {
            "key": "0000-0001-5474-277X",
            "value": 2
        },
        {
            "key": "0000-0001-5600-0091",
            "value": 1
        },
        {
            "key": "0000-0001-5604-9338",
            "value": 1
        },
        {
            "key": "0000-0001-5658-8411",
            "value": 1
        },
        {
            "key": "0000-0001-5739-7836",
            "value": 1
        },
        {
            "key": "0000-0001-5776-398X",
            "value": 1
        },
        {
            "key": "0000-0001-5780-4999",
            "value": 1
        },
        {
            "key": "0000-0001-5844-4518",
            "value": 1
        },
        {
            "key": "0000-0001-5904-4727",
            "value": 1
        },
        {
            "key": "0000-0001-5970-8343",
            "value": 1
        },
        {
            "key": "0000-0001-6167-3637",
            "value": 1
        },
        {
            "key": "0000-0001-6238-2743",
            "value": 5
        },
        {
            "key": "0000-0001-6250-4624",
            "value": 1
        },
        {
            "key": "0000-0001-6279-6700",
            "value": 2
        },
        {
            "key": "0000-0001-6280-4424",
            "value": 1
        },
        {
            "key": "0000-0001-6300-9916",
            "value": 1
        },
        {
            "key": "0000-0001-6325-2764",
            "value": 1
        },
        {
            "key": "0000-0001-6612-6755",
            "value": 1
        },
        {
            "key": "0000-0001-6613-9097",
            "value": 1
        },
        {
            "key": "0000-0001-6633-3092",
            "value": 1
        },
        {
            "key": "0000-0001-6725-5807",
            "value": 1
        },
        {
            "key": "0000-0001-6973-7877",
            "value": 1
        },
        {
            "key": "0000-0001-7091-9686",
            "value": 1
        },
        {
            "key": "0000-0001-7192-4612",
            "value": 1
        },
        {
            "key": "0000-0001-7304-1346",
            "value": 1
        },
        {
            "key": "0000-0001-7347-3464",
            "value": 3
        },
        {
            "key": "0000-0001-7348-143X",
            "value": 1
        },
        {
            "key": "0000-0001-7369-5411",
            "value": 1
        },
        {
            "key": "0000-0001-7398-9591",
            "value": 1
        },
        {
            "key": "0000-0001-7448-7526",
            "value": 2
        },
        {
            "key": "0000-0001-7482-7228",
            "value": 1
        },
        {
            "key": "0000-0001-7539-2377",
            "value": 1
        },
        {
            "key": "0000-0001-7662-6446",
            "value": 1
        },
        {
            "key": "0000-0001-7965-9008",
            "value": 1
        },
        {
            "key": "0000-0001-8026-6631",
            "value": 2
        },
        {
            "key": "0000-0001-8031-2925",
            "value": 2
        },
        {
            "key": "0000-0001-8169-2472",
            "value": 1
        },
        {
            "key": "0000-0001-8198-8898",
            "value": 1
        },
        {
            "key": "0000-0001-8199-6003",
            "value": 1
        },
        {
            "key": "0000-0001-8334-7816",
            "value": 1
        },
        {
            "key": "0000-0001-8451-6925",
            "value": 1
        },
        {
            "key": "0000-0001-8464-8688",
            "value": 1
        },
        {
            "key": "0000-0001-8581-2718",
            "value": 1
        },
        {
            "key": "0000-0001-8821-2267",
            "value": 1
        },
        {
            "key": "0000-0001-9060-0891",
            "value": 1
        },
        {
            "key": "0000-0001-9159-1800",
            "value": 1
        },
        {
            "key": "0000-0001-9197-9805",
            "value": 1
        },
        {
            "key": "0000-0001-9325-922X",
            "value": 1
        },
        {
            "key": "0000-0001-9473-0677",
            "value": 1
        },
        {
            "key": "0000-0001-9557-7723",
            "value": 1
        },
        {
            "key": "0000-0001-9579-0831",
            "value": 1
        },
        {
            "key": "0000-0001-9603-6793",
            "value": 1
        },
        {
            "key": "0000-0001-9650-3136",
            "value": 1
        },
        {
            "key": "0000-0001-9738-9494",
            "value": 1
        },
        {
            "key": "0000-0001-9798-5616",
            "value": 2
        },
        {
            "key": "0000-0001-9817-2203",
            "value": 1
        },
        {
            "key": "0000-0001-9839-1959",
            "value": 1
        },
        {
            "key": "0000-0002-0008-1061",
            "value": 1
        },
        {
            "key": "0000-0002-0056-3976",
            "value": 1
        },
        {
            "key": "0000-0002-0123-9596",
            "value": 1
        },
        {
            "key": "0000-0002-0134-4986",
            "value": 1
        },
        {
            "key": "0000-0002-0270-5560",
            "value": 1
        },
        {
            "key": "0000-0002-0311-8405",
            "value": 1
        },
        {
            "key": "0000-0002-0364-6638",
            "value": 1
        },
        {
            "key": "0000-0002-0366-043X",
            "value": 1
        },
        {
            "key": "0000-0002-0380-929X",
            "value": 1
        },
        {
            "key": "0000-0002-0437-3272",
            "value": 1
        },
        {
            "key": "0000-0002-0501-4271",
            "value": 1
        },
        {
            "key": "0000-0002-0585-5513",
            "value": 1
        },
        {
            "key": "0000-0002-0600-4323",
            "value": 1
        },
        {
            "key": "0000-0002-0601-6121",
            "value": 1
        },
        {
            "key": "0000-0002-0648-2034",
            "value": 1
        },
        {
            "key": "0000-0002-0653-3655",
            "value": 2
        },
        {
            "key": "0000-0002-0781-8548",
            "value": 1
        },
        {
            "key": "0000-0002-0849-9841",
            "value": 1
        },
        {
            "key": "0000-0002-0861-2752",
            "value": 1
        },
        {
            "key": "0000-0002-0910-9737",
            "value": 2
        },
        {
            "key": "0000-0002-1026-0944",
            "value": 1
        },
        {
            "key": "0000-0002-1370-044X",
            "value": 1
        },
        {
            "key": "0000-0002-1518-9470",
            "value": 1
        },
        {
            "key": "0000-0002-1934-1505",
            "value": 1
        },
        {
            "key": "0000-0002-1960-9508",
            "value": 1
        },
        {
            "key": "0000-0002-2025-349X",
            "value": 2
        },
        {
            "key": "0000-0002-2438-1528",
            "value": 2
        },
        {
            "key": "0000-0002-2967-5322",
            "value": 2
        },
        {
            "key": "0000-0002-3059-8109",
            "value": 1
        },
        {
            "key": "0000-0002-3130-7262",
            "value": 1
        },
        {
            "key": "0000-0002-3262-9570",
            "value": 1
        },
        {
            "key": "0000-0002-3294-5637",
            "value": 1
        },
        {
            "key": "0000-0002-3357-6271",
            "value": 1
        },
        {
            "key": "0000-0002-3469-5731",
            "value": 1
        },
        {
            "key": "0000-0002-3511-6586",
            "value": 1
        },
        {
            "key": "0000-0002-3570-3190",
            "value": 2
        },
        {
            "key": "0000-0002-3604-6532",
            "value": 1
        },
        {
            "key": "0000-0002-3839-3757",
            "value": 1
        },
        {
            "key": "0000-0002-3851-4969",
            "value": 2
        },
        {
            "key": "0000-0002-3941-7626",
            "value": 1
        },
        {
            "key": "0000-0002-4073-715X",
            "value": 1
        },
        {
            "key": "0000-0002-4115-4474",
            "value": 1
        },
        {
            "key": "0000-0002-4130-6685",
            "value": 1
        },
        {
            "key": "0000-0002-4187-5642",
            "value": 1
        },
        {
            "key": "0000-0002-4430-4538",
            "value": 1
        },
        {
            "key": "0000-0002-4439-5089",
            "value": 1
        },
        {
            "key": "0000-0002-4673-4347",
            "value": 1
        },
        {
            "key": "0000-0002-4905-040X",
            "value": 6
        },
        {
            "key": "0000-0002-4917-4736",
            "value": 1
        },
        {
            "key": "0000-0002-4982-6319",
            "value": 1
        },
        {
            "key": "0000-0002-5025-4266",
            "value": 1
        },
        {
            "key": "0000-0002-5346-3477",
            "value": 1
        },
        {
            "key": "0000-0002-5417-9208",
            "value": 2
        },
        {
            "key": "0000-0002-5514-9561",
            "value": 1
        },
        {
            "key": "0000-0002-5685-9338",
            "value": 1
        },
        {
            "key": "0000-0002-5703-1639",
            "value": 1
        },
        {
            "key": "0000-0002-5904-8020",
            "value": 1
        },
        {
            "key": "0000-0002-5988-9161",
            "value": 1
        },
        {
            "key": "0000-0002-5994-8117",
            "value": 1
        },
        {
            "key": "0000-0002-6052-6675",
            "value": 1
        },
        {
            "key": "0000-0002-6105-1923",
            "value": 1
        },
        {
            "key": "0000-0002-6168-3883",
            "value": 1
        },
        {
            "key": "0000-0002-6176-1669",
            "value": 1
        },
        {
            "key": "0000-0002-6393-6645",
            "value": 1
        },
        {
            "key": "0000-0002-6525-1295",
            "value": 1
        },
        {
            "key": "0000-0002-6559-7093",
            "value": 1
        },
        {
            "key": "0000-0002-6799-376X",
            "value": 1
        },
        {
            "key": "0000-0002-6925-7299",
            "value": 1
        },
        {
            "key": "0000-0002-6990-1419",
            "value": 2
        },
        {
            "key": "0000-0002-7017-2823",
            "value": 1
        },
        {
            "key": "0000-0002-7047-6720",
            "value": 1
        },
        {
            "key": "0000-0002-7061-7060",
            "value": 1
        },
        {
            "key": "0000-0002-7063-7299",
            "value": 1
        },
        {
            "key": "0000-0002-7066-0608",
            "value": 1
        },
        {
            "key": "0000-0002-7091-4997",
            "value": 1
        },
        {
            "key": "0000-0002-7148-639X",
            "value": 1
        },
        {
            "key": "0000-0002-7333-1674",
            "value": 2
        },
        {
            "key": "0000-0002-7339-0375",
            "value": 1
        },
        {
            "key": "0000-0002-7344-6666",
            "value": 1
        },
        {
            "key": "0000-0002-7429-6110",
            "value": 1
        },
        {
            "key": "0000-0002-7459-306X",
            "value": 1
        },
        {
            "key": "0000-0002-7643-2112",
            "value": 1
        },
        {
            "key": "0000-0002-7658-0844",
            "value": 1
        },
        {
            "key": "0000-0002-7714-4980",
            "value": 1
        },
        {
            "key": "0000-0002-7805-0046",
            "value": 1
        },
        {
            "key": "0000-0002-7886-7603",
            "value": 1
        },
        {
            "key": "0000-0002-7887-9954",
            "value": 1
        },
        {
            "key": "0000-0002-8000-4647",
            "value": 1
        },
        {
            "key": "0000-0002-8136-5233",
            "value": 1
        },
        {
            "key": "0000-0002-8181-5118",
            "value": 1
        },
        {
            "key": "0000-0002-8195-6738",
            "value": 2
        },
        {
            "key": "0000-0002-8287-3416",
            "value": 1
        },
        {
            "key": "0000-0002-8358-4915",
            "value": 2
        },
        {
            "key": "0000-0002-8509-6587",
            "value": 2
        },
        {
            "key": "0000-0002-8511-8213",
            "value": 2
        },
        {
            "key": "0000-0002-8584-1700",
            "value": 1
        },
        {
            "key": "0000-0002-8625-0224",
            "value": 1
        },
        {
            "key": "0000-0002-8712-3665",
            "value": 1
        },
        {
            "key": "0000-0002-8783-1362",
            "value": 1
        },
        {
            "key": "0000-0002-8888-0975",
            "value": 1
        },
        {
            "key": "0000-0002-8906-8567",
            "value": 1
        },
        {
            "key": "0000-0002-8950-6643",
            "value": 1
        },
        {
            "key": "0000-0002-9059-7255",
            "value": 1
        },
        {
            "key": "0000-0002-9127-057X",
            "value": 1
        },
        {
            "key": "0000-0002-9200-4222",
            "value": 1
        },
        {
            "key": "0000-0002-9252-7453",
            "value": 1
        },
        {
            "key": "0000-0002-9253-5381",
            "value": 1
        },
        {
            "key": "0000-0002-9283-2124",
            "value": 1
        },
        {
            "key": "0000-0002-9383-3946",
            "value": 1
        },
        {
            "key": "0000-0002-9404-3807",
            "value": 1
        },
        {
            "key": "0000-0002-9925-5171",
            "value": 2
        },
        {
            "key": "0000-0003-0089-5919",
            "value": 2
        },
        {
            "key": "0000-0003-0285-722X",
            "value": 2
        },
        {
            "key": "0000-0003-0317-8886",
            "value": 1
        },
        {
            "key": "0000-0003-0403-4470",
            "value": 1
        },
        {
            "key": "0000-0003-0568-6542",
            "value": 1
        },
        {
            "key": "0000-0003-0862-0135",
            "value": 1
        },
        {
            "key": "0000-0003-0922-5065",
            "value": 1
        },
        {
            "key": "0000-0003-1004-8289",
            "value": 1
        },
        {
            "key": "0000-0003-1149-1194",
            "value": 1
        },
        {
            "key": "0000-0003-1213-018X",
            "value": 1
        },
        {
            "key": "0000-0003-1540-0165",
            "value": 1
        },
        {
            "key": "0000-0003-1703-4058",
            "value": 1
        },
        {
            "key": "0000-0003-1805-7403",
            "value": 1
        },
        {
            "key": "0000-0003-2181-3441",
            "value": 1
        },
        {
            "key": "0000-0003-2220-826X",
            "value": 1
        },
        {
            "key": "0000-0003-2587-4153",
            "value": 1
        },
        {
            "key": "0000-0003-2673-665X",
            "value": 1
        },
        {
            "key": "0000-0003-2689-3022",
            "value": 1
        },
        {
            "key": "0000-0003-2707-0335",
            "value": 3
        },
        {
            "key": "0000-0003-2712-3433",
            "value": 1
        },
        {
            "key": "0000-0003-2786-0062",
            "value": 1
        },
        {
            "key": "0000-0003-2831-6384",
            "value": 1
        },
        {
            "key": "0000-0003-2910-0606",
            "value": 1
        },
        {
            "key": "0000-0003-2913-0522",
            "value": 1
        },
        {
            "key": "0000-0003-2985-9348",
            "value": 1
        },
        {
            "key": "0000-0003-3044-7471",
            "value": 1
        },
        {
            "key": "0000-0003-3144-3034",
            "value": 1
        },
        {
            "key": "0000-0003-3356-0306",
            "value": 1
        },
        {
            "key": "0000-0003-3455-5584",
            "value": 1
        },
        {
            "key": "0000-0003-3457-9690",
            "value": 1
        },
        {
            "key": "0000-0003-3461-9859",
            "value": 1
        },
        {
            "key": "0000-0003-3676-3264",
            "value": 1
        },
        {
            "key": "0000-0003-3754-1452",
            "value": 1
        },
        {
            "key": "0000-0003-3844-8081",
            "value": 1
        },
        {
            "key": "0000-0003-3853-0093",
            "value": 1
        },
        {
            "key": "0000-0003-4134-7345",
            "value": 2
        },
        {
            "key": "0000-0003-4342-6215",
            "value": 1
        },
        {
            "key": "0000-0003-4343-3124",
            "value": 2
        },
        {
            "key": "0000-0003-4365-6934",
            "value": 1
        },
        {
            "key": "0000-0003-4634-2922",
            "value": 1
        },
        {
            "key": "0000-0003-4640-0942",
            "value": 1
        },
        {
            "key": "0000-0003-4694-5651",
            "value": 3
        },
        {
            "key": "0000-0003-4807-6576",
            "value": 1
        },
        {
            "key": "0000-0003-4809-4379",
            "value": 1
        },
        {
            "key": "0000-0003-4875-8307",
            "value": 1
        },
        {
            "key": "0000-0003-4921-4950",
            "value": 1
        }
    ]
}';

	$obj = json_decode($json);
	
	foreach ($obj->rows as $row)
	{
		$orcids[] = $row->key;
	}

}

$doi_lookup = false;

$force = true;
$force = false;

$count = 1;

// from array
if (1)
{
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
}


?>
