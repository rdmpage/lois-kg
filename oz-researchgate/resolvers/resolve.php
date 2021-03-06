<?php

error_reporting(E_ALL);


// Resolve one object
require_once(dirname(dirname(__FILE__)) . '/documentstore/couchsimple.php');
require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$headers = array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
		'Accept-Language: en-gb',
	);	
	
	//print_r($headers);
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER =>  $headers,
	  CURLOPT_COOKIEJAR => 'cookie.txt'
	);
	
	if (0)
	{
		$opts[CURLOPT_PROXY] = '103.29.90.134:8080';
	}
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}


	
//----------------------------------------------------------------------------------------
function resolve_url($url)
{
	$blacklist = array(
	'https://www.researchgate.net/publication/257190768_A_checklist_of_the_vascular_plants_of_the_lowland_savannas_of_Belize_Central_America',
	'https://www.researchgate.net/publication/321850260_New_data_on_the_spider_fauna_of_Iran_Arachnida_Araneae_Part_IV',
	'https://www.researchgate.net/publication/270654840_Effects_of_logging_and_recruitment_on_community_phylogenetic_structure_in_32_permanent_forest_plots_of_Kampong_Thom_Cambodia',
	'https://www.researchgate.net/publication/325041923_Dividing_and_conquering_the_fastest-growing_genus_Towards_a_natural_sectional_classification_of_the_mega-diverse_genus_Begonia_Begoniaceae',
	'https://www.researchgate.net/publication/320901575_A_geomorphic_and_tectonic_model_for_the_formation_of_the_flight_of_Holocene_marine_terraces_at_Mahia_Peninsula_New_Zealand',
	'https://www.researchgate.net/publication/228495043_Palaeogene_macrofossils_from_CRP-3_Drillhole_Victoria_Land_Basin_Antarctica',
	'https://www.researchgate.net/publication/228542585_Neogene_fossil_tonnoidean_gastropods_of_Indonesia',
	);
	

	$data = null;
	
	if (in_array($url, $blacklist))
	{
	return $data;
	}

	// fetch from rg
	
	$html = get($url);
	
	// echo $html;
	
	if ($html != '')
	{
	
		$dom = str_get_html($html);

		$doi = '';


		// DOI from meta tag
		$metas = $dom->find('meta[property=citation_doi]');
		foreach ($metas as $meta)
		{
			$doi = $meta->content;
		}


		// JSON-LD
		//$scripts = $dom->find('div[class=profile-detail] script[type=application/ld+json]');
		$scripts = $dom->find('script[type=application/ld+json]');
		foreach ($scripts as $script)
		{
			$json = $script->innertext;
			
			echo $json . "\n";

			$obj = json_decode($json);
			
			//print_r($obj);
			//exit();
			
			$data = new stdclass;		
			$data->{'message-format'} = 'application/ld+json';		
			$data->{'message-source'} = $url;	
			
			$data->message = $obj;	
			
				
			/*
			$items = array();
			
			if (isset($obj->{'@graph'}))
			{
				$items = $obj->{'@graph'};
			}
			else
			{
				$items[] = $obj;
			}
			
			foreach ($items as $item)
			{
	
				if ($item->{'@type'} == 'ScholarlyArticle')
				{
					// Add DOI if we have one
					if ($doi != '')
					{
						$item->sameAs = "https://doi.org/" . $doi;
					}
				
					// Get links to add
				
					$data = new stdclass;		
					$data->{'message-format'} = 'application/ld+json';		
					$data->{'message-source'} = $url;
				
					$data->message = $item;

				}
			
				if ($item->{'@type'} == 'Person')
				{
			
					// get links to add
					$links = array();
				
					foreach ($dom->find('div[class=nova-v-publication-item__stack-item] div a') as $a)
					{
						if (preg_match('/\/publication\//', $a->href))
						{
							$links[] = $a->href;
						}
					}
				
				
					$data = new stdclass;		
					$data->{'message-format'} = 'application/ld+json';		
					$data->{'message-source'} = $url;
				
					$data->links = $links;
				
					$data->message = $item;

				}
			}
			*/			

		}	

	}

	return $data;
}

// test
if (1)
{
	$url = 'https://www.researchgate.net/publication/320321240_Phylogenetic_relationships_of_millipedes_in_the_subclass_Penicillata_Diplopoda_with_a_key_to_the_genera';
	
	$url = 'https://www.researchgate.net/profile/Megan_Short3';
	
	$url = 'https://www.researchgate.net/profile/Keita_Kuroda';
	
	$data = resolve_url($url);
	print_r($data);

}

?>