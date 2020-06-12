<?php

error_reporting(E_ALL);

require_once('couchsimple.php');


//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
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
	
	return $data;
}

//----------------------------------------------------------------------------------------


$ids = array(
'https://doi.org/10.11646/zootaxa.4402.2.3',
'urn:lsid:zoobank.org:author:B95E5A4D-2E3D-4760-8452-7D5A7884F53A',
'https://doi.org/10.11646/zootaxa.3721.6.4#creator-1',
'https://doi.org/10.6165/tai.2003.48(2).87',

'urn:lsid:ipni.org:authors:36928-1',

'https://doi.org/10.6165/tai.2015.60.169#creator-1',
'urn:lsid:ipni.org:authors:35562-1',

'https://doi.org/10.6165/tai.2015.60.169',

'urn:lsid:ipni.org:names:77152027-1',

'urn:lsid:ipni.org:names:77152027-1#3',

// sameAs
'https://doi.org/10.11646/zootaxa.3721.6.4%23creator-2',
'urn:lsid:zoobank.org:author:A3D8D082-8394-48F9-8926-CC2B3CDF56B1',
'https://orcid.org/0000-0003-2857-3583',

"urn:lsid:zoobank.org:author:CED1B53E-6D79-4796-80A4-79A6740AEBF1",
"urn:lsid:zoobank.org:author:07A305E3-F9F0-483E-9E33-88E2CB9CBFAD",

// SICI
'https://doi.org/10.11646/phytotaxa.312.1.11#ref31',
'https://doi.org/10.11646/phytotaxa.312.1.16#ref10',
'https://doi.org/10.6165/tai.2014.59.1',

'https://doi.org/10.1111/njb.00664#creator-2',
'https://doi.org/10.1111/njb.00664',
'urn:lsid:ipni.org:authors:38863-1',
'https://doi.org/10.1111/njb.01249',
'urn:lsid:ipni.org:authors:14590-1',
'https://orcid.org/0000-0001-5171-4140',
'https://doi.org/10.11646/phytotaxa.312.2.3',

// name and type specimen
'urn:lsid:ipni.org:names:77127392-1',
'https://www.gbif.org/occurrence/1024567444',

);


$force = false;

foreach ($ids as $id)
{
	$exists = $couch->exists($id);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$url = 'http://localhost/~rpage/lois-kg/www/construct.php?uri=' . urlencode($id);
	
		$json = get($url);
	
		echo $json;
		
		$doc = json_decode($json);
		if ($doc)
		{
			$doc->_id = $id;		
		
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
		}		
		
	}


}

?>

