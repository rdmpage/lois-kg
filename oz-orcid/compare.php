<?php

// functions to compare people's names (which it turns out is really hard)


//----------------------------------------------------------------------------------------
// Get number of shared strings 
function compare_as_array($query, $target)
{
	$d = new stdclass;

	$query_array = explode(' ', mb_strtolower($query));
	$target_array = explode(' ', mb_strtolower($target));
				
	$common = array_intersect($target_array, $query_array);

	$union = $target_array;
	$union = array_merge($union, $query_array);
	$union = array_unique($union);
	
	if (0)
	{
		print_r($common);
		print_r($union);
	}
	
	$d->jaccard = 1 - count($common) / count($union);
	
	return $d;
}

//----------------------------------------------------------------------------------------
// tests

if (0)
{

	$json = '{
	  "_id": "0000-0002-9404-3807/work/52539921",
	  "_rev": "1-72c2cc685ccd95c25cc47c836e69d1e6",
	  "message-format": "application/vnd.citationstyles.csl+json",
	  "message": {
		"id": "MIRANDA_DE_MELO_2018",
		"type": "article-journal",
		"author": [
		  {
			"family": "MELO",
			"given": "JOSÉ IRANILDO MIRANDA DE",
			"literal": "JOSÉ IRANILDO MIRANDA DE MELO"
		  },
		  {
			"family": "PAULINO",
			"given": "RENAN DA CRUZ",
			"literal": "RENAN DA CRUZ PAULINO"
		  },
		  {
			"family": "OLIVEIRA",
			"given": "REGINA CÉLIA DE",
			"literal": "REGINA CÉLIA DE OLIVEIRA"
		  },
		  {
			"family": "VIEIRA",
			"given": "DIEGO DALTRO",
			"literal": "DIEGO DALTRO VIEIRA"
		  }
		],
		"event-date": {
		  "date-parts": [
			[
			  2018,
			  6
			]
		  ]
		},
		"issued": {
		  "date-parts": [
			[
			  2018,
			  6
			]
		  ]
		},
		"collection-title": "Phytotaxa",
		"container-title": "Phytotaxa",
		"DOI": "10.11646/phytotaxa.357.4.1",
		"issue": "4",
		"number": "4",
		"number-of-pages": "1",
		"page": "235",
		"page-first": "235",
		"publisher": "Magnolia Press",
		"title": "Flora of Rio Grande do Norte, Brazil: Boraginales",
		"URL": "https://doi.org/10.11646%2Fphytotaxa.357.4.1",
		"volume": "357"
	  }
	}';

	$obj = json_decode($json);

	print_r($obj);

	$target = 'José Melo';


	foreach ($obj->message->author as $author)
	{
		// 1. Compare as array
	
		$query = $author->literal;
	
		$d = compare_as_array($query, $target);
	
		echo $target . ' ' . $query . ' ' . $d->jaccard . "\n";

	}

}



?>
