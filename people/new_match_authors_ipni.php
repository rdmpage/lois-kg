<?php

// Given a bibliographic URI, match authors of work to IPNI taxonomic 
// authors without assuming that they have the same order


error_reporting(E_ALL);

require ('go.php');


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
	
	//echo $data;
	
	return $data;
}


//----------------------------------------------------------------------------------------
function get_works_for_author($name)
{
	$uris = array();

	$sparql = 'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

select distinct ?pub_work
WHERE
{ 
  	VALUES ?pub_name { "' . $name . '" }
    
    # person with name
  	?pub_creator schema:name ?pub_name .

    # papers 
    ?pub_role schema:creator ?pub_creator .
    ?pub_work schema:creator ?pub_role .
  
    # taxon names
  	?ipni_pub schema:sameAs ?pub_work .  
	?ipni tcom:publishedInCitation ?ipni_pub .
  
}

';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	
	
	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$uris[] = $binding->pub_work->value;
		
	
		}
	}
	
	return $uris;

}

//----------------------------------------------------------------------------------------
function get_works_for_author_lastname($name)
{
	$uris = array();

	$sparql = 'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

select distinct ?pub_work
WHERE
{ 
  	VALUES ?last_name { "' . $name . '" }
    
    # person with name
    ?pub_creator schema:familyName ?last_name .
  	?pub_creator schema:name ?pub_name .

    # papers 
    ?pub_role schema:creator ?pub_creator .
    ?pub_work schema:creator ?pub_role .
  
    # taxon names
  	?ipni_pub schema:sameAs ?pub_work .  
	?ipni tcom:publishedInCitation ?ipni_pub .
  
}

';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	
	
	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$uris[] = $binding->pub_work->value;
		
	
		}
	}
	
	return $uris;

}



//----------------------------------------------------------------------------------------


// Given a bibliographic URI, match authors of work to IPNI taxonomic authors

$uris=array(
//'https://doi.org/10.1093/botlinnean/bow013',
//'https://doi.org/10.26492/gbs69(2).2017-08',

//'https://doi.org/10.3897/phytokeys.7.1855',
//'https://doi.org/10.3897/phytokeys.38.7055',
//'https://doi.org/10.3897/phytokeys.47.9076',
//'https://doi.org/10.1016/j.ympev.2011.06.011',

//'https://doi.org/10.26492/gbs69(2).2017-08',
/*'https://doi.org/10.11646/phytotaxa.161.4.1',
'https://doi.org/10.11646/phytotaxa.23.1.1',
'https://doi.org/10.3897/phytokeys.94.21650',
'https://doi.org/10.3897/phytokeys.94.21329',
*/

/*
'https://doi.org/10.3897/phytokeys.95.21832',
'https://doi.org/10.12705/646.8',
'https://doi.org/10.11646/phytotaxa.23.1.1',
'https://doi.org/10.11646/phytotaxa.23.1.3',

'https://doi.org/10.1093/botlinnean/bow013',
*/
//'https://doi.org/10.1017/s0960428603000404',
'https://doi.org/10.4102/abc.v42i1.27',
);

$matches = array();

$ipni_matches = array();

// name cluster

$name = 'John C. Manning';
$name = 'J. C. Manning';
$name = 'J.C. Manning';

$names = array(
//'John Charles Manning',
//'John Manning',
//'J. C. MANNING'
//'M. Hughes',
//'Mark Hughes',
//'Ching-I Peng',
//'C.-I Peng',

//'CHING-I PENG',
//'Koh Nakamura',
//'Peter Goldblatt',
//'Christoph Oberprieler',
//'David Middleton',
//'HANNAH ATKINS',

//'MICHAEL MÖLLER',

//'Hong Quang Bui',
//'H. Q. Bui',

/*
'Gemma L C Bramley',
'Gemma L. C. Bramley',
'G L C Bramley',
'G. L. C. Bramley',
*/

//'Michele Rodda',
//'M. Rodda',
//'Nadhanielle Simonsson Juhonewe',
//'Nadhanielle Simonsson',
//'N. Simonsson',

'W John Kress',
'W. John Kress',
'W. J. Kress',
);

$names=array(
//'Carter',
//'Knapp',
/*
'Bohs',
'Orejuela',
'Wahlert',
'Orozco',
'Barboza',*/

//'Rapini',
//'Kress',
//'Moonlight',
//'Forrest',
//'Tebbit',
//'Burtt',
//'Weston',
//'Möller',
//'Leroy',
'Gillet',
);

foreach ($names as $name)
{

//$uris = get_works_for_author($name);
$uris = get_works_for_author_lastname($name);

foreach ($uris as $uri)
{
	echo "# $uri . \n";


	$sparql = 'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT DISTINCT ?pub_creator ?pub_name ?ipni_member ?ipni_member_name WHERE
{ 
	VALUES ?pub_work { <' . $uri . '> }
	?ipni_pub schema:sameAs ?pub_work .  
	?ipni tcom:publishedInCitation ?ipni_pub .

	?pub_work schema:creator ?pub_role  . 
	?pub_role schema:roleName ?pub_roleName  .    
	?pub_role schema:creator ?pub_creator  .    
	?pub_creator schema:name ?pub_name .  

	?ipni tn:authorteam ?ipni_team .
	?ipni_team tm:hasMember ?ipni_team_member .
	?ipni_team_member tm:role ?ipni_role .
	?ipni_team_member tm:index ?ipni_roleName .
	?ipni_team_member tm:member ?ipni_member .
	?ipni_member dc:title ?ipni_member_name .


  	FILTER (?ipni_role != "Basionym Author")
}';


	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	//print_r($obj);
	
	$rows = array();
	
	
	
	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$row = new stdclass;
			
			$row->pub_name 	= $binding->pub_name->value;
			$row->pub_id 	= $binding->pub_creator->value;
			$row->ipni_id 	= $binding->ipni_member->value;
			$row->ipni_name = $binding->ipni_member_name->value;
			
			// sometimes creators lack names, e.g. if CrossRef metadata
			// is broken (such as affiliations treated as authors)
			
			if ($row->pub_name != '')
			{		
				$rows[] = $row;
			}
		
	
		}
	}
	
	//print_r($rows);
	
	$source = array();
	$target = array();
	$edges = array();
	
	$node_map = array();
	
	$count = 0;
	
	foreach ($rows as $row)
	{
		if (!isset($source[$row->pub_name]))
		{
			$node = new stdclass;
			$node->id = $count++;
			$node->label = $row->pub_name;
			$node->guid = $row->pub_id;
			$node->degree = 0;
			$source[$row->pub_name] = $node;
			
			$node_map[$node->id] = $node->label;
		}
	}
	
	foreach ($rows as $row)
	{
		if (!isset($target[$row->ipni_name]))
		{
			$node = new stdclass;
			$node->id = $count++;
			$node->label = $row->ipni_name;
			$node->guid = $row->ipni_id;
			$node->degree = 0;
			$target[$row->ipni_name] = $node;
			
			$node_map[$node->id] = $node->label;
		}
	}
	
	$conflict = false;
	
	//print_r($source);
	//print_r($target);
	
	//exit();
	
	// compare
	foreach ($rows as $row)
	{
		$r = compare($row->pub_name, $row->ipni_name);
		//print_r($r);
		
		if ($r->p > 0.9)
		{
			$edge = new stdclass;
			
			$edge->weight = $r->d;
			$edge->source_id = $source[$row->pub_name]->id;
			$edge->target_id = $target[$row->ipni_name]->id;

			$edge->source_guid = $source[$row->pub_name]->guid;
			$edge->target_guid = $target[$row->ipni_name]->guid;
			
			$source[$row->pub_name]->degree++;
			$target[$row->ipni_name]->degree++;
			
			if (
				($source[$row->pub_name]->degree > 1)
				|| 
				($target[$row->ipni_name]->degree > 1)
			)
			{
				$conflict = true;
			}
			
			$edges[] = $edge;
		}

		
	}	
	
	if (0)
	{
		echo "Source\n";
		print_r($source);
		echo "Target\n";
		print_r($target);
		echo "Edges\n";
		print_r($edges);
	}
		
	if ($conflict)
	{
		echo "# Conflict \n";
	}
	else
	{
		// output as triples
		

		$triples = array();

		foreach ($edges as $edge)
		{
			$triples[] = '<' . $edge->source_guid . '> <http://schema.org/sameAs> <' . $edge->target_guid . '> . ';
		}

		echo join("\n", $triples) . "\n";
		
		
	
	
	}
	
	
	
	
	
}
}


?>
