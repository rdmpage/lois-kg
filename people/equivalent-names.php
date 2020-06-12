<?php

// Cluster authors based on surname


error_reporting(E_ALL);

//require ('go.php');


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


//--------------------------------------------------------------------------------------------------
function clean($str)
{
	$str = str_replace (".", " ", $str);
	$str = str_replace ("-", " ", $str);  // think about this...
	$str = preg_replace('/\s+/', ' ', $str);
	$str = mb_convert_case ($str, MB_CASE_TITLE);
	$str = trim($str);
	return $str;
}
//--------------------------------------------------------------------------------------------------
// bioguid function
function compare($str1, $str2)
{
	global $gmlfile;
	global $node_map;
	
	$short = clean($str1);
	$long = clean($str2);
	
	$parts = explode(' ', $short);
	$lparts = explode(' ', $long);
	
	//print_r($parts);
	//print_r($lparts);
	
	// Swap if one string is longer than the other
	if (count($parts) > count($lparts))
	{
		$tmp = $parts;
		$parts = $lparts;
		$lparts = $tmp;
		
		$long = $str1;
		$short = $str2;
	}
	
	$pattern = '/';
	foreach ($parts as $p)
	{
		$pat = $p;
		if (strlen($p) > 1)
		{
			$pat = $p[0];
		}		
		$pattern .= '(' . $pat . '\w*)? ';
	}
	$pattern = trim($pattern);
	$pattern .= '/';
	
	//echo "Pattern=$pattern\n";
	
	$match = array();
	
	if (preg_match($pattern, $long . ' ', $match))
	{
//		print_r($match);
	}
	
	// Score for this comparison
	$score = 0;
	
	// We need to match all the parts of the shorter name
	$threshold = count($parts);
	
	// How many matches did we get?
	$count = 0;
	foreach ($match as $k => $v)
	{
		if ($v != '')
		{
			$count++;
		}
	}
	$count--; // ignore match[0], which we always have
	
	//echo "threshold=$threshold, count=$count\n";
	
	$ok = true;
	
	if ($count >= $threshold)
	{
		for ($i = 0; $i < $count; $i++)
		{
			
			// For string comparison we ignore accents
			// From http://www.randomsequence.com/articles/removing-accented-utf-8-characters-with-php/
						
			$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
			$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
			$s1 = str_replace($search, $replace, $parts[$i]);
			$s2 = str_replace($search, $replace, $lparts[$i]);
			
			
			$s1 = $parts[$i];
			$s2 = $lparts[$i];
			
			//echo "s1=$s1, s2=$s2\n";
			
			
			if ((strpos($s1, $s2) === false) and (strpos($s2, $s1) === false))
			{
				// Matches are different names
				$ok = false;
			}
			else
			{
				// any kind of match scores 1
				$score++;
			}
	
			// if the two names are identical, and longer than one
			// character regard them as being full name matches, and
			// the score is 1.1
			if (strcasecmp($s1, $s2) == 0)
			{
				if (strlen($s1) > 1)
				{
					$score += 0.1;
				}
			}
			
			//echo "score=$score\n";
		}
	}
	
	if ($score != 0 and $ok)
	{
		return $score;
	}
	else
	{
		return 0;
	}
	
	
}



//----------------------------------------------------------------------------------------


$familynames = array(
//'Middleton',
//'Larsen',
//'Jones',
//'Linder'
//'Brusse',
//'Blake',


//'Li',
//'Chen',
//'Zhang',

//'Jones',
//'Johnston',
//'Karger',
//'Hughes',
//'Kumar',
//'Dong',
//'Murray',
//'Watson',
//'Ridley',
//'Rolfe',
//'Nguyen',
'Hay',
'Boyce',
);


foreach ($familynames as $familyname)
{

	$sparql = 'PREFIX schema: <http://schema.org/>

SELECT ?person ?givenName
WHERE
{ 
  	?person schema:familyName "' . $familyname . '" .
    ?person schema:givenName ?givenName .  	
}';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);
	
	
	$names = array();
	

	//print_r($obj);
	
	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			if (!isset($names[$binding->givenName->value]))
			{
					$names[$binding->givenName->value] = array();
			}
			$names[$binding->givenName->value][] = $binding->person->value;
		}
	}
	
	$nodes = array();
	$edges = array();
		
	//print_r($names);
	
	$n = count($names);
	
	$nodes = array_keys($names);
	
	for ($i = 0; $i < $n - 1; $i++)
	{
		echo $i . ' ' . $nodes[$i] . "\n";
				
		for ($j = $i + 1; $j < $n; $j++)
		{
			echo " -- " . $nodes[$j] . "\n";
			
			// compare
			$score = compare($nodes[$i], $nodes[$j]);
			
			if ($score > 0)
			{

				if (!isset($edges[$i])) 
				{
					$edges[$i] = array();
				}
				
				$edge = new stdclass;
				$edge->target = $j;
				$edge->score = $score;
				
				$edges[$i][] = $edge;
			}
			
		}
	
	}
	
	print_r($nodes);
	print_r($edges);
	
	// dump
	
	// dot
	
	$dot = '';
	
	$dot .=  "graph {\n";
	
	foreach ($nodes as $k => $v)
	{
		$dot .= $k . ' [label="' . $v . '"];' . "\n";
	}
	
	foreach ($edges as $source => $targets)
	{
		foreach ($targets as $target)
		{
			$dot .=  $source . ' -- ' . $target->target . ' [label="' . $target->score . '"];' . "\n";
		}
	
	}
	
	$dot .=  "}\n";
	
	file_put_contents('names.dot', $dot);
	
	
	// gml
	
	
	
	
	
}




?>
