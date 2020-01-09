<?php

// Do a big SPARQL query

$queries = array();

$queries['missing_ipni'] = 
'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

select distinct ?ipni_member ?title
WHERE
{ 
  	?ipni_team_member tm:member ?ipni_member .
    MINUS {
      ?ipni_member dc:title ?title .
    }
}';



//----------------------------------------------------------------------------------------
// get
function get($url, $format = "application/json")
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	curl_close($ch);
	
	return $response;
}
//----------------------------------------------------------------------------------------

$heading = array();
$first = true;

$page = 100;
$offset = 0;

$done = false;

while (!$done)
{
	$sparql = $queries['missing_ipni'];
		
	$sparql .= "\nLIMIT $page";
	$sparql .= "\nOFFSET $offset";

	//echo $sparql . "\n";


	$url = 'http://167.99.58.120:9999/blazegraph/sparql?query=' . urlencode($sparql);

	//echo $url;

	$json = get($url);
	
	//echo $json;

	$obj = json_decode($json);
	
	//print_r($obj);

	foreach ($obj->results as $results)
	{
		foreach ($results as $binding)
		{
			//print_r($binding);
			
			// dump results 
			
			$row = array();
			
			foreach ($binding as $k => $v)
			{
				if (!isset($heading[$k]))
				{
					$heading[] = $k;
				}
				
				$row[] = $v->value;					
				
			
			}
			
			if ($first)
			{
				echo join("\t", $heading) . "\n";
				$first = false;
			}
			echo join("\t", $row) . "\n";
	
					
		}
	}

	if (count($obj->results->bindings) < $page)
	{
		$done = true;
	}
	else
	{
		$offset += $page;
	}
	
	
}

?>

