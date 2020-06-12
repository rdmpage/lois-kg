<?php

error_reporting(E_ALL ^ E_DEPRECATED);

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


//--------------------------------------------------------------------------------------------------
function get_match($key)
{
	$obj = null;
	
	$url = 'http://127.0.0.1:5984/zenodo/_design/lookup/_view/triple'
		. '?key=' . urlencode(json_encode($key));
		
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
	}
	
	// print_r($obj);
	
	return $obj;
}

//----------------------------------------------------------------------------------------
function get_from_doi($doi)
{
	$obj = null;
	
	$url = 'http://127.0.0.1:5984/zenodo/_design/identifier/_view/doi'
		. '?key=' . urlencode('"' . $doi . '"');
		
	//echo $url . "\n";

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
	}
	
	// print_r($obj);
	
	return $obj;
}

//----------------------------------------------------------------------------------------
// Get part for a Zenodo record
function get_part($id)
{
	$obj = null;
	
	$url = 'http://127.0.0.1:5984/zenodo/_design/parts/_view/whole-part'
		. '?key=' . urlencode('"' . $id . '"');
		
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
	}
	
	// print_r($obj);
	
	return $obj;
}

//----------------------------------------------------------------------------------------
function fetch_zenodo_json($id, &$jsonld)
{	
	$url = "https://zenodo.org/api/records/" . $id;

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
		
		// image URL
		if (isset($obj->files[0]->links->self))
		{
			$jsonld->contentUrl = $obj->files[0]->links->self;
		}
		
		// image thumbnail
		if (isset($obj->links->thumb250))
		{
			$jsonld->thumbnailUrl = $obj->links->thumb250;
		}
		
	}
}

//----------------------------------------------------------------------------------------
// Call API asking for JSON-LD which we convert to triples 
// Note that we make a second call to get the details of the image itself (sigh)
function fetch_zenodo($id)
{	
	$url = "https://zenodo.org/api/records/" . $id;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER => array("Accept: application/ld+json")
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	if ($data != '')
	{
		// triples
		$jsonld = json_decode($data);
		
		// second call 
		fetch_zenodo_json($id, $jsonld);
					
		
		if (0)
		{			
			// JSON-LD for debugging
			echo json_encode($jsonld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			echo "\n";			
		}
		else
		{
			// Triples for export
			$triples = jsonld_to_rdf($jsonld, array('format' => 'application/nquads'));			
			echo $triples;
		}
					 
	}
}


$dois = array(
//'10.11646/phytotaxa.319.3.2',
//'10.11646/phytotaxa.238.1.1',
//'10.3897/phytokeys.114.29367',
//'10.3897/phytokeys.99.22287'
//'10.5852/ejt.2017.281',
//'10.3897/phytokeys.97.20975',
//'10.3897/phytokeys.42.8210',
//'10.3897/phytokeys.56.5306',
/*
'10.3897/phytokeys.39.7691',
'10.3897/phytokeys.83.13442',
'10.3897/phytokeys.91.21363',
'10.3897/phytokeys.58.5847',
*/
//'10.3897/phytokeys.111.28595', // not found
//'10.3897/phytokeys.47.9076',
//'10.3897/phytokeys.1.658',
//'10.1016/j.sajb.2017.06.021', 
//'10.3897/zookeys.156.2168',
//'10.3897/zookeys.278.4765',
'10.11646/zootaxa.3964.4.5',
'10.11646/zootaxa.3721.6.4',
'10.1163/187525410x12578602960263',
'10.3897/zookeys.156.2168',
'10.3897/zookeys.741.21814',
'10.11646/zootaxa.4402.2.3',
'10.3897/zookeys.278.4765',
);

foreach ($dois as $doi)
{
	$obj = get_from_doi($doi);
	
	//print_r($obj);
	
	// If we have a Zenodo record for this DOI, fetch parts (figures)
	if (count($obj->rows) == 1)
	{
		echo "\n# $doi\n";
	
		$obj_parts = get_part($doi);
		//print_r($obj);
		
		
		if (count($obj_parts->rows) > 0)
		{
			$parts = array();
			foreach ($obj_parts->rows as $row)
			{
				$parts[] = $row->value;
			}
			
			//print_r($parts);
			
			// get images
			
			foreach ($parts as $part)
			{
				fetch_zenodo($part);			
			}
			
			
		}

	
	
	}
	

}

?>
