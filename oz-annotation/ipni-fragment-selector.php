<?php

// Get page fragment identifiers for IPNI

require_once(dirname(dirname(__FILE__)) . '/adodb5/adodb.inc.php');


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
function get_metadata($guid)
{
	$pages = array();
	
	$url = 'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=' . $guid;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			
			if (isset($obj->page))
			{
				$pages = explode('-', $obj->page);
			}
		}
	}

	return $pages;
}

//----------------------------------------------------------------------------------------
// Parse collation to get page on which name appears
function get_microreference($collation)
{
	$page = null;
	
	$matched = false;
	
	// 43(2): 316 (1998):
	if (!$matched)
	{
		if (preg_match('/\d+\(\d+\):\s+(?<page>\d+)/', $collation, $m))
		{
			$page = $m['page'];
		}	
	}

	return $page;
}


//----------------------------------------------------------------------------------------
$db = NewADOConnection('mysqli');
$db->Connect("localhost", 
	'root', '' , 'ipni');
	
// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 

$result = $db->Execute('SET max_heap_table_size = 1024 * 1024 * 1024');
$result = $db->Execute('SET tmp_table_size = 1024 * 1024 * 1024');


$url = 'http://www.repository.naturalis.nl/record/525090';

$pages = get_metadata($url);

$sql = 'SELECT * FROM `names`';	
$sql .= ' WHERE url = "' . $url . '"';	


$result = $db->Execute($sql);
if ($result == false) die("failed [" . __FILE__ . ":" . __LINE__ . "]: " . $sql);

while (!$result->EOF && ($result->NumRows() > 0)) 
{	
	$collation = $result->fields['Collation'];
	$pdf = $result->fields['pdf'];
	$url = $result->fields['url'];
	
	
	
	echo $collation . "\n";
	
	// parse collation
	$page = get_microreference($collation);
	
	if ($page)
	{
		echo $page . "\n";
		
		$delta = $page - $pages[0] + 1;
		
		echo $delta . "\n";
		
	}
	else
	{
		echo "*** Failed to parse \"$collation\"\n";
	}
	
	
	//print_r($pages);

	$result->MoveNext();
}




?>