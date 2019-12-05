<?php

// Get list of sequence accessions for a journal

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


$journal = 'Lindleyana';

$accessions = array();


// get GIs for sequences linked to this journal
$parameters = array(
	'db' => 'nucleotide',
	'term' => '"' . $journal . '"[Journal]  AND ddbj embl genbank with limits[filt] NOT transcriptome[All Fields] NOT mRNA[filt] NOT TSA[All Fields] NOT scaffold[All Fields]',
	'retmode' => 'json',
	'retmax' => 10000,
	);
	
$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?' . http_build_query($parameters);

echo $url . "\n";

$json = get($url);

if ($json != '')
{
	$obj = json_decode($json);
	
	// list of GIs
	print_r($obj);
	
	// divide into smaller chunks
	$chunks = array_chunk($obj->esearchresult->idlist, 100);
	
	print_r($chunks);
	
	// convert to accession numbers
	foreach ($chunks as $chunk)
	{
		$parameters = array(
			'db' => 'nucleotide',
			'id' => join(',', $chunk),
			'retmode' => 'json',
			'rettype' => 'acc',
			);
		
		$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?' . http_build_query($parameters);
		
		$response = get($url);
		
		if ($response != '')
		{
			$acc = explode("\n", trim($response));			
			$accessions = array_merge($accessions, $acc);
		}
		
		$rand = rand(100000, 300000);
	    usleep($rand);
		
	
	}

}


print_r($accessions);


?>
