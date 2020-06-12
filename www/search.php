<?php

// Elastic search

error_reporting(E_ALL);


require_once(dirname(dirname(__FILE__)) . '/oz-elastic/config.inc.php');
require_once(dirname(dirname(__FILE__)) . '/oz-elastic/elastic.php');


function do_search($q)
{
	global $elastic;

	$json = '{
	"size":20,
		"query": {
		   "multi_match" : {
		  "query": "<QUERY>",
		  "fields":["search_data.fulltext", "search_data.fulltext_boosted^4"] 
		}
	},

	"highlight": {
		  "pre_tags": [
			 "<mark>"
		  ],
		  "post_tags": [
			 "<\/mark>"
		  ],
		  "fields": {
			 "search_data.fulltext": {},
			 "search_data.fulltext_boosted": {}
		  }
	   },

	"aggs": {


	"by_cluster_id": {
			"terms": {
				"field": "search_data.cluster_id.keyword",
				"order": {
					"max_score.value": "desc"
				}
			},
	
	
			"aggs": {
				"max_score": {
					"max": {
						"script": {
							"lang": "painless",
							"inline": "_score"
						}
					}
				}
			}
		}
	 }

	}';

	$json = str_replace('<QUERY>', $q, $json);

	$response = $elastic->send('POST',  '_search?pretty', $json);					

	//echo $response;

	$obj = json_decode($response);

	// process and convert to RDF

	// schema.org DataFeed
	$output = new stdclass;

	$output->{'@context'} = (object)array('@vocab' => 'http://schema.org/');

	$output->{'@graph'} = array();
	$output->{'@graph'}[0] = new stdclass;
	$output->{'@graph'}[0]->{'@id'} = "http://example.rss";
	$output->{'@graph'}[0]->{'@type'} = "DataFeed";
	$output->{'@graph'}[0]->dataFeedElement = array();

	$clusters = array();

	if (isset($obj->hits))
	{
		if ($obj->hits->total > 0)
		{
			// Clusters
				
			foreach ($obj->aggregations->by_cluster_id->buckets as $bucket)
			{
				$clusters[$bucket->key] = array();
			}
		
			// Cluster results
			foreach ($obj->hits->hits as $hit)
			{
				$cluster_id = $hit->_source->search_data->cluster_id;
			
				if (!isset($clusters[$cluster_id]))
				{
					$clusters[$cluster_id] = array();
				}
			
				$clusters[$cluster_id][] = $hit->_source->search_result_data;
			}
	
		}

	}

	//print_r($clusters);

	// simplest approach, just add first element in cluster 
	foreach ($clusters as $cluster_id => $cluster)
	{
		$item = new stdclass;
		$item->{'@id'} = $cluster_id;
		$item->{'@type'} = array("DataFeedItem", "ItemList");
	
		$item->name = $cluster[0]->name;
	
		$item->numberOfItems = count($cluster);
		$item->itemListElement = array();
	
		foreach ($cluster as $c)
		{
			$item->itemListElement[] = $c->url;
		}
	
		$output->{'@graph'}[0]->dataFeedElement[] = $item;
	}

	//print_r($output);

	return $output;
}


?>
