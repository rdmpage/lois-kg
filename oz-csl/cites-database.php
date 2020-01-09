<?php

// Citations only that I am harvesting locally, assumes article metadata
// will be added separately, e.g. from CrossRef

error_reporting(E_ALL);

require_once('couchsimple.php');

$force = true;

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
// My microcitation API for citations
function get_work($url)
{
	global $couch;

	$data = null;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;			
			$data->_id = $url;
			
			// https://github.com/CrossRef/rest-api-doc#result-types
			$data->{'message-format'} = 'application/vnd.crossref-api-message+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
						
			$data->message = $obj;
			
		}
	}
	
	return $data;
}


//----------------------------------------------------------------------------------------
function citation_fetch($guid)
{
	global $couch;
	global $force;
	
	// CouchDB document has URL as _id	
	$url = 'http://localhost/~rpage/microcitation/www/citations?guid=' . $guid;
	
	$exists = $couch->exists($url);

	$go = true;
	if ($exists && !$force)
	{
		echo "Have already\n";
		$go = false;
	}

	if ($go)
	{
		$doc = get_work($url);
		
		print_r($doc);		

		if ($doc)
		{
		
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


// test cases
if (1)
{

	$guids=array(
	"10.11646/phytotaxa.186.4.4"
	);
	
	$guids=array(
	'10.11646/phytotaxa.369.3.1',
	'10.11646/phytotaxa.349.3.1',
	'10.1186/s12862-017-0974-3'
	);	
	
	$guids=array(
//	'10.11646/phytotaxa.26.1.2',
	'10.11646/phytotaxa.159.3.2',
	);
	
	$guids=array(
	'10.11646/phytotaxa.405.3.3'
	);
	
	$guids=array(
	'10.1600/036364412x616639'
	);
	
	$guids=array('10.11646/phytotaxa.376.6.2');
	
	$guids=array(
	//'10.11646/phytotaxa.137.1.7',
	//'10.11646/phytotaxa.161.4.1',
	//'10.1600/036364416x690732',
	//'10.11646/phytotaxa.174.3.5',
//	'10.11646/phytotaxa.305.1.9',
	//'10.11646/phytotaxa.195.1.9',
'10.11646/phytotaxa.208.2.4',
'10.11646/phytotaxa.205.4.7',
'10.11646/phytotaxa.195.1.2',
'10.11646/phytotaxa.193.1.1',
'10.11646/phytotaxa.159.1.4',
'10.11646/phytotaxa.164.1.1',
'10.11646/phytotaxa.158.3.8',
'10.11646/phytotaxa.191.1.13',
'10.11646/phytotaxa.188.4.5',
'10.11646/phytotaxa.184.1.6',
'10.11646/phytotaxa.184.2.1',
'10.11646/phytotaxa.183.4.7',
'10.11646/phytotaxa.164.2.3',
'10.11646/phytotaxa.207.3.4',
'10.11646/phytotaxa.207.3.7',
'10.11646/phytotaxa.203.2.10',
'10.11646/phytotaxa.202.1.9',
'10.11646/phytotaxa.202.3.4',
'10.11646/phytotaxa.192.3.3',
'10.11646/phytotaxa.161.1.3',
'10.11646/phytotaxa.192.2.5',
'10.11646/phytotaxa.129.1.7',
'10.11646/phytotaxa.161.4.2',
'10.11646/phytotaxa.166.2.5',
'10.11646/phytotaxa.163.1.5',
'10.11646/phytotaxa.178.1.1',
'10.11646/phytotaxa.175.2.4',
'10.11646/phytotaxa.176.1.29',
'10.11646/phytotaxa.178.3.4',
'10.11646/phytotaxa.178.3.9',
'10.11646/phytotaxa.167.3.3',
'10.11646/phytotaxa.170.4.6',
'10.11646/phytotaxa.173.3.5',
'10.11646/phytotaxa.174.4.5',
'10.11646/phytotaxa.174.3.5',
'10.11646/phytotaxa.175.5.3',
'10.11646/phytotaxa.175.5.5',
'10.11646/phytotaxa.175.5.8',
'10.11646/phytotaxa.175.1.6',
'10.11646/phytotaxa.175.3.8',
'10.11646/phytotaxa.170.2.1',
'10.11646/phytotaxa.217.1.1',
'10.11646/phytotaxa.217.3.5',
'10.11646/phytotaxa.217.3.6',
'10.11646/phytotaxa.217.2.1',
'10.11646/phytotaxa.213.2.4',
'10.11646/phytotaxa.205.3.4',
'10.11646/phytotaxa.212.3.2',
'10.11646/phytotaxa.212.3.7',
'10.11646/phytotaxa.219.3.9',
'10.11646/phytotaxa.230.2.2',
'10.11646/phytotaxa.226.1.4',
'10.11646/phytotaxa.231.2.4',
'10.11646/phytotaxa.231.2.9',
'10.11646/phytotaxa.231.3.9',
'10.11646/phytotaxa.60.1.5',
'10.11646/phytotaxa.54.1.4',
'10.11646/phytotaxa.227.3.6',
'10.11646/phytotaxa.222.4.7',
'10.11646/phytotaxa.221.1.4',
'10.11646/phytotaxa.233.3.7',
'10.11646/phytotaxa.70.1.1',
'10.11646/phytotaxa.224.1.6',
'10.11646/phytotaxa.237.1.1',
'10.11646/phytotaxa.234.1.7',
'10.11646/phytotaxa.64.1.1',
'10.11646/phytotaxa.238.3.4',
'10.11646/phytotaxa.238.3.7',
'10.11646/phytotaxa.39.1.4',
'10.11646/phytotaxa.38.1.2',
'10.11646/phytotaxa.236.2.4',
'10.11646/phytotaxa.234.2.3',
'10.11646/phytotaxa.239.1.5',
'10.11646/phytotaxa.245.2.1',
'10.11646/phytotaxa.246.1.5',
'10.11646/phytotaxa.247.1.7',
'10.11646/phytotaxa.247.2.6',
'10.11646/phytotaxa.247.2.7',
'10.11646/phytotaxa.247.4.4',
'10.11646/phytotaxa.270.1.8',
'10.11646/phytotaxa.270.2.6',
'10.11646/phytotaxa.273.1.9',
'10.11646/phytotaxa.277.3.2',
'10.11646/phytotaxa.278.3.6',
'10.11646/phytotaxa.275.2.11',
'10.11646/phytotaxa.278.1.5',
'10.11646/phytotaxa.282.3.5',
'10.11646/phytotaxa.282.1.6',
'10.11646/phytotaxa.282.1.5',
'10.11646/phytotaxa.263.1.4',
'10.11646/phytotaxa.263.1.11',
'10.11646/phytotaxa.296.1.3',
'10.11646/phytotaxa.296.2.6',
'10.11646/phytotaxa.297.1.9',
'10.11646/phytotaxa.299.2.13',
'10.11646/phytotaxa.302.2.10',
'10.11646/phytotaxa.308.2.9',
'10.11646/phytotaxa.303.1.8',
'10.11646/phytotaxa.308.1.14',
'10.11646/phytotaxa.306.2.7',
'10.11646/phytotaxa.305.1.8',
'10.11646/phytotaxa.307.1.9',
'10.11646/phytotaxa.307.3.4',
'10.11646/phytotaxa.305.1.9',
'10.11646/phytotaxa.302.3.4',
'10.11646/phytotaxa.302.3.5',
'10.11646/phytotaxa.166.2.1',
'10.11646/phytotaxa.117.1.2',
'10.11646/phytotaxa.233.1.6',
'10.11646/phytotaxa.238.2.2',
'10.11646/phytotaxa.254.1.1',
'10.11646/phytotaxa.257.3.6',
'10.11646/phytotaxa.261.2.4',
'10.11646/phytotaxa.265.2.8',
'10.11646/phytotaxa.265.2.12',
'10.11646/phytotaxa.266.1.5',
'10.11646/phytotaxa.266.3.5',
'10.11646/phytotaxa.268.4.1',
'10.11646/phytotaxa.269.3.1',
'10.11646/phytotaxa.276.1.1',
'10.11646/phytotaxa.277.2.1',
'10.11646/phytotaxa.280.3.4',
'10.11646/phytotaxa.292.2.5',
'10.11646/phytotaxa.292.3.1',
'10.11646/phytotaxa.298.2.5',
'10.11646/phytotaxa.298.3.2',
'10.11646/phytotaxa.299.1.2',
'10.11646/phytotaxa.299.2.10',
'10.11646/phytotaxa.306.1.4',
'10.11646/phytotaxa.306.3.4',
'10.11646/phytotaxa.309.3.6',
'10.11646/phytotaxa.309.3.7',
'10.11646/phytotaxa.309.3.11',
'10.11646/phytotaxa.312.1.11',
'10.11646/phytotaxa.312.1.16',
'10.11646/phytotaxa.312.2.3',
'10.11646/phytotaxa.313.2.6',
'10.11646/phytotaxa.313.2.10',
'10.11646/phytotaxa.314.2.10',
'10.11646/phytotaxa.316.2.3',
'10.11646/phytotaxa.316.3.7',
'10.11646/phytotaxa.317.1.4',
'10.11646/phytotaxa.317.3.5',
'10.11646/phytotaxa.317.3.7',
'10.11646/phytotaxa.319.3.2',
'10.11646/phytotaxa.321.2.7',
'10.11646/phytotaxa.323.2.6',
'10.11646/phytotaxa.323.2.7',
'10.11646/phytotaxa.324.3.3',
'10.11646/phytotaxa.324.3.6',
'10.11646/phytotaxa.326.1.1',
'10.11646/phytotaxa.329.3.3',
'10.11646/phytotaxa.332.1.8',
'10.11646/phytotaxa.333.1.14',
'10.11646/phytotaxa.333.2.14',
'10.11646/phytotaxa.334.1.9',
'10.11646/phytotaxa.338.1.13',
'10.11646/phytotaxa.338.3.3',
'10.11646/phytotaxa.347.2.6',
'10.11646/phytotaxa.349.1.14',
'10.11646/phytotaxa.349.2.7',
'10.11646/phytotaxa.349.2.11',
'10.11646/phytotaxa.349.3.9',
'10.11646/phytotaxa.350.3.2',
'10.11646/phytotaxa.356.1.3',
'10.11646/phytotaxa.357.2.5',
'10.11646/phytotaxa.360.2.6',
'10.11646/phytotaxa.360.3.11',
'10.11646/phytotaxa.362.1.5',
'10.11646/phytotaxa.364.1.1',
'10.11646/phytotaxa.364.1.2',
'10.11646/phytotaxa.364.3.2',
'10.11646/phytotaxa.365.3.7',
'10.11646/phytotaxa.367.1.9',
'10.11646/phytotaxa.369.1.1',
'10.11646/phytotaxa.369.2.6',
'10.11646/phytotaxa.371.1.3',
'10.11646/phytotaxa.371.4.3',
'10.11646/phytotaxa.374.2.12',
'10.11646/phytotaxa.374.2.13',
'10.11646/phytotaxa.376.2.1',
'10.11646/phytotaxa.379.1.10',
'10.11646/phytotaxa.379.2.4',
'10.11646/phytotaxa.63.1.2',	
	);

$guids=array(
//'10.11646/phytotaxa.266.2.9',
//'10.11646/phytotaxa.220.1.6',
//'10.11646/phytotaxa.334.3.4'
'10.1016/j.sajb.2017.06.021'
);

	$force = true;
	//$force = false;
	
	foreach ($guids as $guid)
	{
		citation_fetch($guid);
	}
}



?>
