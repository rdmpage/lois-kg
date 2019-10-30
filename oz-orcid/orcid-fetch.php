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

$orcids=array(
'0000-0003-0131-0302',
'0000-0002-8104-7761',
'0000-0002-1093-0370',
'0000-0003-0403-4470',
'0000-0002-8509-6587',
);

$orcids=array(
'0000-0003-2823-0337',
'0000-0002-5856-9561'
);

$orcids=array(
'0000-0003-3558-9794',
'0000-0001-5810-5145',
'0000-0002-9986-9640',
'0000-0001-8305-3236',
'0000-0003-2659-3396',
'0000-0001-5473-2942',
'0000-0003-1055-9070',
'0000-0003-3500-2957',
'0000-0003-0813-6650',
'0000-0001-9892-0355',
);

$orcids=array(
'0000-0001-5028-0686',
'0000-0001-5138-2115',
'0000-0001-5152-2931',
'0000-0001-5347-5403',
'0000-0001-5538-1371',
'0000-0001-5577-8782',
'0000-0001-5600-0091',
'0000-0001-5626-0491',
'0000-0001-5658-8411',
'0000-0001-5745-7077',
'0000-0001-5844-3945',
'0000-0001-5874-5097',
'0000-0001-6069-1702',
'0000-0001-6306-9353',
'0000-0001-6489-1103',
'0000-0001-6551-6823',
'0000-0001-6614-4216',
'0000-0001-6692-6509',
'0000-0001-6727-1831',
'0000-0001-6791-2731',
'0000-0001-6889-714X',
'0000-0001-7062-7982',
'0000-0001-7265-065X',
'0000-0001-7436-0939',
'0000-0001-7579-6646',
'0000-0001-7603-3608',
'0000-0001-7698-3945',
'0000-0001-7768-5747',
'0000-0001-7965-9008',
'0000-0001-7980-5888',
'0000-0001-8060-2842',
'0000-0001-8163-8206',
'0000-0001-8179-2270',
'0000-0001-8334-6311',
'0000-0001-8443-4276',
'0000-0001-8643-7112',
'0000-0001-8837-4872',
'0000-0001-8889-7892',
'0000-0001-8932-739X',
'0000-0001-8956-9874',
'0000-0001-8997-0270',
'0000-0001-9008-273X',
'0000-0001-9036-0912',
'0000-0001-9056-7382',
'0000-0001-9127-4549',
'0000-0001-9139-5025',
'0000-0001-9144-2848',
'0000-0001-9341-5827',
'0000-0001-9603-6793',
'0000-0001-9714-8893',
'0000-0001-9728-465X',
'0000-0001-9832-3809',
'0000-0001-9892-0355',
'0000-0001-9949-8280',
'0000-0001-9968-7620',
'0000-0001-9972-1040',
'0000-0002-0235-9506',
'0000-0002-0244-601X',
'0000-0002-0301-5334',
'0000-0002-0437-3272',
'0000-0002-0478-2332',
'0000-0002-0729-166X',
'0000-0002-0849-9841',
'0000-0002-0850-5578',
'0000-0002-0864-0489',
'0000-0002-0874-320X',
'0000-0002-0876-3286',
'0000-0002-0948-5038',
'0000-0002-0961-9817',
'0000-0002-1085-3344',
'0000-0002-1093-0370',
'0000-0002-1177-1682',
'0000-0002-1261-9076',
'0000-0002-1440-4810',
'0000-0002-1932-0026',
'0000-0002-1952-6059',
'0000-0002-2002-5809',
'0000-0002-2115-0257',
'0000-0002-2203-2076',
'0000-0002-2218-896X',
'0000-0002-2505-8116',
'0000-0002-2541-5427',
'0000-0002-2580-9776',
'0000-0002-2909-5459',
'0000-0002-3056-4945',
'0000-0002-3060-7271',
'0000-0002-3179-4005',
'0000-0002-3415-0862',
'0000-0002-3448-9735',
'0000-0002-3524-5273',
'0000-0002-3570-3190',
'0000-0002-3820-125X',
'0000-0002-3823-4369',
'0000-0002-3830-4603',
'0000-0002-4047-4169',
'0000-0002-4087-6336',
'0000-0002-4124-2175',
'0000-0002-4632-8939',
'0000-0002-4802-0057',
'0000-0002-5346-3477',
'0000-0002-5371-6411',
'0000-0002-5551-213X',
'0000-0002-5578-2260',
'0000-0002-5596-6895',
'0000-0002-5659-7550',
'0000-0002-5663-8025',
'0000-0002-5835-0324',
'0000-0002-5919-3340',
'0000-0002-6052-6675',
'0000-0002-6164-3515',
'0000-0002-6173-6754',
'0000-0002-6195-1972',
'0000-0002-6444-7608',
'0000-0002-6573-6049',
'0000-0002-6672-8075',
'0000-0002-6926-1722',
'0000-0002-7028-1114',
'0000-0002-7078-4336',
'0000-0002-7197-5409',
'0000-0002-7204-0744',
'0000-0002-7248-4193',
'0000-0002-7358-6685',
'0000-0002-7395-6738',
'0000-0002-7402-8941',
'0000-0002-7423-899X',
'0000-0002-7507-4560',
'0000-0002-7530-9024',
'0000-0002-7619-840X',
'0000-0002-7634-6927',
'0000-0002-7643-2112',
'0000-0002-7658-0844',
'0000-0002-7805-0046',
'0000-0002-7875-4510',
'0000-0002-7943-4164',
'0000-0002-8104-7761',
'0000-0002-8245-3024',
'0000-0002-8255-6349',
'0000-0002-8351-4028',
'0000-0002-8440-6467',
'0000-0002-8458-3420',
'0000-0002-8493-0736',
'0000-0002-8511-8213',
'0000-0002-8655-9555',
'0000-0002-8726-3561',
'0000-0002-8758-9326',
'0000-0002-8950-6643',
'0000-0002-8958-7033',
'0000-0002-9066-8869',
'0000-0002-9161-5881',
'0000-0002-9253-5381',
'0000-0002-9266-2188',
'0000-0002-9295-2644',
'0000-0002-9373-6302',
'0000-0002-9416-983X',
'0000-0002-9559-2795',
'0000-0002-9562-9287',
'0000-0002-9674-2767',
'0000-0002-9741-6533',
'0000-0002-9789-1414',
'0000-0002-9853-3328',
'0000-0002-9986-9640',
'0000-0003-0061-8647',
'0000-0003-0089-5919',
'0000-0003-0090-0730',
'0000-0003-0131-0302',
'0000-0003-0285-722X',
'0000-0003-0317-8886',
'0000-0003-0360-8321',
'0000-0003-0403-4470',
'0000-0003-0533-6817',
'0000-0003-0566-372X',
'0000-0003-0626-0770',
'0000-0003-0681-012X',
'0000-0003-0705-8902',
'0000-0003-0813-6650',
'0000-0003-0955-5339',
'0000-0003-1132-5698',
'0000-0003-1181-9065',
'0000-0003-1305-4115',
'0000-0003-1376-0028',
'0000-0003-1918-2433',
'0000-0003-1929-5534',
'0000-0003-1987-1976',
'0000-0003-1998-417X',
'0000-0003-2081-9120',
'0000-0003-2220-826X',
'0000-0003-2251-4056',
'0000-0003-2284-6851',
'0000-0003-2397-2438',
'0000-0003-2438-5681',
'0000-0003-2541-6494',
'0000-0003-2542-2747',
'0000-0003-2659-3396',
'0000-0003-2739-9077',
'0000-0003-2752-7788',
'0000-0003-2811-3317',
'0000-0003-2836-6496',
'0000-0003-2876-0610',
'0000-0003-2982-7792',
'0000-0003-3076-4098',
'0000-0003-3254-2691',
'0000-0003-3457-0151',
'0000-0003-3544-857X',
'0000-0003-3546-4836',
'0000-0003-3605-0707',
'0000-0003-3849-9057',
'0000-0003-3897-2908',
'0000-0003-3937-1768',
'0000-0003-3942-2086',
'0000-0003-4129-6381',
'0000-0003-4178-2419',
'0000-0003-4184-398X',
'0000-0003-4211-544X',
'0000-0003-4276-6664',
'0000-0003-4365-6934',
'0000-0003-4390-3878',
'0000-0003-4490-3490',
'0000-0003-4551-2432',
'0000-0003-4577-6307',
'0000-0003-4624-1910',
'0000-0003-4640-0942',
'0000-0003-4719-3711',
'0000-0003-4755-8493',
'0000-0003-4760-9588',
'0000-0003-4783-3125',
'0000-0003-4911-9574',
'0000-0003-4960-0587',
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
if (0)
{
	$json = '{
    "rows": [
        {
            "key": "0000-0001-5241-4115",
            "value": 1
        },
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
            "value": 3
        },
        {
            "key": "0000-0001-5600-0091",
            "value": 1
        },
        {
            "key": "0000-0001-5604-9338",
            "value": 2
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
            "value": 2
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
            "value": 2
        },
        {
            "key": "0000-0001-6279-6700",
            "value": 4
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
            "key": "0000-0001-7303-4488",
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
            "value": 2
        },
        {
            "key": "0000-0001-7611-5291",
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
            "value": 5
        },
        {
            "key": "0000-0001-8031-2925",
            "value": 5
        },
        {
            "key": "0000-0001-8169-2472",
            "value": 2
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
            "key": "0000-0001-8208-5535",
            "value": 1
        },
        {
            "key": "0000-0001-8234-9502",
            "value": 1
        },
        {
            "key": "0000-0001-8334-7816",
            "value": 2
        },
        {
            "key": "0000-0001-8451-6925",
            "value": 1
        },
        {
            "key": "0000-0001-8464-8688",
            "value": 3
        },
        {
            "key": "0000-0001-8491-3939",
            "value": 1
        },
        {
            "key": "0000-0001-8562-8849",
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
            "value": 2
        },
        {
            "key": "0000-0001-9109-8602",
            "value": 1
        },
        {
            "key": "0000-0001-9159-1800",
            "value": 1
        },
        {
            "key": "0000-0001-9197-9805",
            "value": 2
        },
        {
            "key": "0000-0001-9325-922X",
            "value": 1
        },
        {
            "key": "0000-0001-9473-0677",
            "value": 2
        },
        {
            "key": "0000-0001-9557-7723",
            "value": 2
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
            "value": 2
        },
        {
            "key": "0000-0001-9852-7393",
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
            "key": "0000-0002-0496-1875",
            "value": 1
        },
        {
            "key": "0000-0002-0501-4271",
            "value": 1
        },
        {
            "key": "0000-0002-0585-5513",
            "value": 3
        },
        {
            "key": "0000-0002-0600-4323",
            "value": 2
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
            "value": 4
        },
        {
            "key": "0000-0002-1026-0944",
            "value": 2
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
            "key": "0000-0002-1602-8468",
            "value": 1
        },
        {
            "key": "0000-0002-1832-3436",
            "value": 1
        },
        {
            "key": "0000-0002-1934-1505",
            "value": 2
        },
        {
            "key": "0000-0002-1960-9508",
            "value": 2
        },
        {
            "key": "0000-0002-2025-349X",
            "value": 3
        },
        {
            "key": "0000-0002-2139-0891",
            "value": 1
        },
        {
            "key": "0000-0002-2406-2666",
            "value": 1
        },
        {
            "key": "0000-0002-2438-1528",
            "value": 2
        },
        {
            "key": "0000-0002-2486-5098",
            "value": 1
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
            "value": 2
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
            "key": "0000-0002-4515-4316",
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
            "value": 2
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
            "value": 2
        },
        {
            "key": "0000-0002-6052-6675",
            "value": 1
        },
        {
            "key": "0000-0002-6086-253X",
            "value": 1
        },
        {
            "key": "0000-0002-6105-1923",
            "value": 3
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
            "key": "0000-0002-6331-3456",
            "value": 1
        },
        {
            "key": "0000-0002-6393-6645",
            "value": 2
        },
        {
            "key": "0000-0002-6511-6659",
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
            "key": "0000-0002-6773-0580",
            "value": 1
        },
        {
            "key": "0000-0002-6799-376X",
            "value": 1
        },
        {
            "key": "0000-0002-6884-6091",
            "value": 1
        },
        {
            "key": "0000-0002-6925-7299",
            "value": 2
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
            "value": 4
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
            "value": 2
        },
        {
            "key": "0000-0002-7643-2112",
            "value": 1
        },
        {
            "key": "0000-0002-7658-0844",
            "value": 2
        },
        {
            "key": "0000-0002-7714-4980",
            "value": 2
        },
        {
            "key": "0000-0002-7763-2431",
            "value": 1
        },
        {
            "key": "0000-0002-7805-0046",
            "value": 1
        },
        {
            "key": "0000-0002-7886-7603",
            "value": 2
        },
        {
            "key": "0000-0002-7887-9954",
            "value": 1
        },
        {
            "key": "0000-0002-8000-4647",
            "value": 2
        },
        {
            "key": "0000-0002-8136-5233",
            "value": 1
        },
        {
            "key": "0000-0002-8162-4338",
            "value": 1
        },
        {
            "key": "0000-0002-8181-5118",
            "value": 1
        },
        {
            "key": "0000-0002-8195-6738",
            "value": 3
        },
        {
            "key": "0000-0002-8287-3416",
            "value": 1
        },
        {
            "key": "0000-0002-8337-3253",
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
            "value": 2
        },
        {
            "key": "0000-0002-8888-0975",
            "value": 2
        },
        {
            "key": "0000-0002-8906-8567",
            "value": 1
        },
        {
            "key": "0000-0002-8950-6643",
            "value": 2
        },
        {
            "key": "0000-0002-9059-7255",
            "value": 3
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
            "value": 2
        },
        {
            "key": "0000-0002-9404-3807",
            "value": 1
        },
        {
            "key": "0000-0002-9925-5171",
            "value": 3
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
            "value": 2
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
            "value": 3
        },
        {
            "key": "0000-0003-1451-7855",
            "value": 1
        },
        {
            "key": "0000-0003-1540-0165",
            "value": 2
        },
        {
            "key": "0000-0003-1563-9977",
            "value": 1
        },
        {
            "key": "0000-0003-1703-4058",
            "value": 2
        },
        {
            "key": "0000-0003-1740-9999",
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
            "value": 2
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
            "key": "0000-0003-3138-6148",
            "value": 1
        },
        {
            "key": "0000-0003-3144-3034",
            "value": 2
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
            "value": 2
        },
        {
            "key": "0000-0003-3754-1452",
            "value": 2
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
            "key": "0000-0003-4490-3490",
            "value": 2
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
            "value": 2
        },
        {
            "key": "0000-0003-4875-8307",
            "value": 1
        },
        {
            "key": "0000-0003-4921-4950",
            "value": 2
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
