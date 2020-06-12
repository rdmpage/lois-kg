<?php

// Taxonomic tree from Wikidata


//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);

	if ($content_type != '')
	{
		$opts[CURLOPT_HTTPHEADER] = array(
			"Accept: " . $content_type, 
			"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" 
		);
	}
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}



//----------------------------------------------------------------------------------------
// Get child taxa
function wikidata_child_taxa($parent)
{
	$sparql = 'PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX wd: <http://www.wikidata.org/entity/>
SELECT *
WHERE
{
 VALUES ?parent { wd:' . $parent . ' }
 ?child wdt:P171 ?parent .
 ?child wdt:P225 ?child_name .
  
 # details
  
  OPTIONAL {
   ?child wdt:P105 ?rank .
    ?rank rdfs:label ?rank_label .
    FILTER (lang(?rank_label) = "en")
  }
  
  # roles
  OPTIONAL {
   ?child wdt:P2868 ?role .
    ?role rdfs:label ?role_label .
    FILTER (lang(?role_label) = "en")
  }  
  
 # basionym
  OPTIONAL {
   ?child wdt:P566 ?basionym .
  }    
  
  # image
   OPTIONAL {
   ?child wdt:P18 ?image .
  }   
  
 # ids
 
OPTIONAL {
   ?sitelink schema:about ?child .
   ?sitelink schema:isPartOf <https://en.wikipedia.org/> .
   ?sitelink schema:name ?wikipedia .
  }   
  
 OPTIONAL {
   ?child wdt:P846 ?gbif .
  }  
  
 OPTIONAL {
   ?child wdt:P685 ?ncbi .
  }
  
 OPTIONAL {
   ?child wdt:P5037 ?powo .
  }  
  
 OPTIONAL {
   ?child wdt:P961 ?ipni .
  }
  
 OPTIONAL {
   ?child wdt:P1070 ?tpl .
  } 
  
 OPTIONAL {
   ?child wdt:P830 ?eol .
  }
  
 OPTIONAL {
   ?child wdt:P960 ?tropicos .
  } 
  
 OPTIONAL {
   ?child wdt:P5055 ?irming .
  } 
  
  OPTIONAL {
   ?child wdt:P3288 ?wsc .
  } 
  
  OPTIONAL {
   ?child wdt:P3151 ?inat .
  } 
  
  OPTIONAL {
   ?child wdt:P815 ?itis .
  }   
  
  OPTIONAL {
   ?child wdt:P815 ?wsc .
  }  
  
  OPTIONAL {
   ?child wdt:P850 ?worms .
  }              
 
    OPTIONAL {
   ?child wdt:P1746 ?zoobank .
  }  
  
    OPTIONAL {
   ?child wdt:P842 ?fossilworks .
  } 

    OPTIONAL {
   ?child wdt:P1391 ?indexfungorum .
  } 
      OPTIONAL {
   ?child wdt:P962 ?mycobank .
  } 
  
      OPTIONAL {
   ?child wdt:P3606 ?bold .
  }   
    
  
      
  
}
      ';
	
	//echo $sparql . "\n";
	
	$url = 'https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' . urlencode($sparql);
	
	//echo $url . "\n";
	
	$json = get($url, '', 'application/json');
	
	//echo $json;
	
	$obj = null;
		
	if ($json != '')
	{
		$obj = json_decode($json);
		
		//print_r($obj);
		
		/*
		if (isset($obj->results->bindings))
		{
			if (count($obj->results->bindings) != 0)	
			{
				$item = $obj->results->bindings[0]->work->value;
				$item = preg_replace('/https?:\/\/www.wikidata.org\/entity\//', '', $item);
			}
		}
		*/
	}
	
	return $obj ;
}

//----------------------------------------------------------------------------------------


$root = 'Q6011736'; // Metastelmatinae
$root = 'Q54950'; // Diplura
$root = 'Q30101379'; // Gibbosporina

//$root = 'Q27991970'; // Trochonanina
$root = 'Q1595529'; // Parotis

$root = 'Q5811470'; // Ditassa

//$root = 'Q2347764';

$root = 'Q6011736'; // Metastelmatinae

$root = 'Q5707972'; // Asclepiadeae

$root = 'Q1806376'; // Larsenianthus
$root = 'Q16766367'; // Zingibereae

$root = 'Q6171406'; // Zingiberoideae
$root = 'Q37021'; // Zingiberaceae


$keys = array(
	'id',
	'parent',
	
	'name',
	'image',
	'rank',
	'status',
	
	'basionym',
	
	'wikipedia',
	
	'bold',
	'eol',
	'fossilworks',
	'gbif',
	'indexfungorum',
	'inat',
	'irming',
	'itis',
	'ipni',
	'mycobank',
	'ncbi',
	'powo',
	'tpl',
	'tropicos',
	'worms',
	'wsc',
	'zoobank',


);


echo join("\t", $keys) . "\n";

$stack = array();
$stack[] = $root;

$dive = false; // just grab immediate children
$dive = true; // if we want to go down the tree

while (count($stack) > 0)
{
	$id = array_pop($stack);
	
	$obj = wikidata_child_taxa($id);
	
	// print_r($obj);
	
	if (isset($obj->results->bindings))
	{
		if (count($obj->results->bindings) > 0)	
		{
			foreach ($obj->results->bindings as $item)
			{
			
				$taxon = new stdclass;
				$taxon->parent = $id;
				$taxon->status = 'accepted';
				
				foreach ($item as $k => $v)
				{
					switch ($k)
					{
						case 'child':
							$taxon->id = str_replace('http://www.wikidata.org/entity/', '', $v->value);
							break;
					
						case 'child_name':
							$taxon->name = $v->value;
							break;
							
						case 'image':
							$taxon->image = $v->value;
							break;
	
						case 'rank_label':
							$taxon->rank = $v->value;
							break;

						case 'basionym':
							$taxon->basionym = str_replace('http://www.wikidata.org/entity/', '', $v->value);
							break;

						case 'role_label':
							switch ($v->value)
							{
								case 'basionym':
									$taxon->status = 'not accepted';
									break;
									
								default:
									break;
							}
							break;
						
						case 'wikipedia':
							$taxon->{$k} = str_replace(' ', '_', $v->value);
							break;
							
						case 'bold':
						case 'eol':
						case 'fossilworks':
						case 'gbif':
						case 'indexfungorum':
						case 'inat':
						case 'irming':
						case 'itis':
						case 'ipni':
						case 'mycobank':
						case 'ncbi':
						case 'powo':
						case 'tpl':
						case 'tropicos':
						case 'worms':
						case 'wsc':		
						case 'zoobank':				
							$taxon->{$k} = $v->value;
							break;
					
					
						default:
							break;
					}
				}
				
				//print_r($taxon);
				
				if ($dive)
				{
					// add to stack
					$stack[] = $taxon->id;
				}
				
				
				// create row
				$row = array();
				foreach ($keys as $k)
				{
					$value = '';
					if (isset($taxon->{$k}))
					{
						$value = $taxon->{$k};
					}
					$row[] = $value;									
				}
				
				//print_r($row);
				
				// dump
				echo join("\t", $row) . "\n";
			
			
			}
		}
	}
	

	
}


?>
