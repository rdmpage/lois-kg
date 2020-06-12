<?php

error_reporting(0); // there is an unexplained error in json-ld php

// IIIF manifest etc. for BHL/Internet Archive content

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');


$config = array();

$config['cache_dir'] = 'cache';


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
function is_xml ($filename)
{
	$ok = true;
	
	if (file_exists($filename))
	{
		$file_handle = fopen($filename, "r");
		$line = fgets($file_handle);
		
		if (preg_match('/<!DOCTYPE html>/', $line))	
		{
			$ok = false;
		}		
	}
	else
	{
		$ok = false;
	}
	
	return $ok;
}

//----------------------------------------------------------------------------------------
function ia_download($identifier, $destination, $force = false)
{
	$dir = $destination . '/' . $identifier;
	
	// Folder for data
	if (!file_exists($dir ))
	{
		$oldumask = umask(0); 
		mkdir($dir , 0777);
		umask($oldumask);
	}
	
	// IA scan data
	$filename = $dir  . '/' . $identifier . '_scandata.xml';
	
	if (!file_exists($filename) && !$force)
	{
	
		$url = 'http://www.archive.org/download/' . $identifier  . '/' . $identifier . '_scandata.xml';
		
		//$url = 'http://www.archive.org/download/' . $identifier   . '/PMC3496941-zookeys.238.3999_scandata.xml';
	
		
		$command = "curl  --location '" . $url . "' > " . $filename;
		echo $command . "\n";
		system ($command);
	}
	
	// BHL mets (has PageIDs)
	$filename = $dir  . '/' . $identifier . '_bhlmets.xml';
	
	if (!file_exists($filename) && !$force)
	{
	
		$url = 'http://www.archive.org/download/' . $identifier  . '/' . $identifier . '_bhlmets.xml';
		$command = "curl  --location '" . $url . "' > " . $filename;
		echo $command . "\n";
		system ($command);
	}


}

//----------------------------------------------------------------------------------------
function ia_scandata($identifier, $destination, &$info)
{	
	$dir = $destination . '/' . $identifier;
	
	$filename = $dir  . '/' . $identifier . '_scandata.xml';
	
	if (!is_xml($filename))
	{
		echo "No scan data!\n";
	
		return;
	}	
	
	$info->identifier = $identifier;
	$info->title = $info->identifier;

	$xml = file_get_contents($filename);
	
	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	// Sometimes (e.g., mobot31753002456132) some pages have zero width and height
	// in the XML, so we keep some default values handy for these cases
	$width 	= 1000;
	$height = 1000;
		
	$nodeCollection = $xpath->query ('/book/pageData/page');
	foreach($nodeCollection as $node)
	{
		$page = new stdclass;
		
		if ($node->hasAttributes()) 
		{ 
			$attributes = array();
			$attrs = $node->attributes; 
			
			foreach ($attrs as $i => $attr)
			{
				$attributes[$attr->name] = $attr->value; 
			}
			
			$page->leafNum = (Integer)$attributes['leafNum'];
			$page->label = (Integer)$attributes['leafNum'] + 1;
		}
		
		$nc = $xpath->query ('origWidth', $node);
		foreach($nc as $n)
		{
			$page->width = (Integer)$n->firstChild->nodeValue;
			
			if ($page->width == 0)
			{
				$page->width = $width;
			}
			else
			{
				$width = $page->width;
			}
		}

		$nc = $xpath->query ('origHeight', $node);
		foreach($nc as $n)
		{
			$page->height = (Integer) $n->firstChild->nodeValue;
			
			if ($page->height == 0)
			{
				$page->height = $height;
			}
			else
			{
				$height = $page->height;
			}
			
		}
	
		$info->page_metadata[] = $page;
	
	}
}


//----------------------------------------------------------------------------------------
// Parse BHL METS so we can link to BHL pages
function bhl_details($identifier, $destination, &$info)
{	
	$dir = $destination . '/' . $identifier;
	
	$filename = $dir  . '/' . $identifier . '_bhlmets.xml';
		
	if (!is_xml($filename))
	{
		return;
	}

	$xml = file_get_contents($filename);
	
	$page_counter = 0;

	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath->registerNamespace('mets', 'http://www.loc.gov/METS/');
	$xpath->registerNamespace('mods', 'http://www.loc.gov/mods/v3');

	// title
	$nodeCollection = $xpath->query ('//mods:mods/mods:titleInfo[1]/mods:title');
	foreach($nodeCollection as $node)
	{
		$info->title = $node->firstChild->nodeValue;
		
		// to do: BHL logo
		//$info->logo = '';
	}	
	
	// more specific title (e.g. volume details)
	$nodeCollection = $xpath->query ('//mods:mods/mods:note[@type="content"]');
	foreach($nodeCollection as $node)
	{
		if (isset($info->title))
		{
			$info->title .= ' ' . $node->firstChild->nodeValue;
		}
	}
		
	// thumbnail
	$nodeCollection = $xpath->query ('//mods:mods/mods:location/mods:url[@usage="primary display"]');
	foreach($nodeCollection as $node)
	{
		$info->thumbnail = $node->firstChild->nodeValue;
	}
	
	
	// BHL link
	$nodeCollection = $xpath->query ('//mods:mods/mods:identifier[@type="uri"]');
	foreach($nodeCollection as $node)
	{
		$info->bhl = $node->firstChild->nodeValue;
	}	
	
	// to do: metadata pairs for more details
	/*
"metadata": [
    {"label":"Author", "value":"Anne Author"},
    {"label":"Published", "value": [
        {"@value": "Paris, circa 1400", "@language":"en"},
        {"@value": "Paris, environ 1400", "@language":"fr"}
      ]
    },
    {"label":"Source",
     "value": "<span>From: <a href=\"http://example.org/db/1.html\">Some Collection</a></span>"}
  ],
  */	
		
	
	$nodeCollection = $xpath->query ('/mets:mets/mets:structMap/mets:div/mets:div');
	foreach($nodeCollection as $node)
	{
		if (!isset($info->page_metadata[$page_counter]))
		{
			$info->page_metadata[$page_counter] = $page;
		}
		
		// attributes
		if ($node->hasAttributes()) 
		{ 
			$attributes = array();
			$attrs = $node->attributes; 
			
			foreach ($attrs as $i => $attr)
			{
				$attributes[$attr->name] = $attr->value; 
			}
			
			$info->page_metadata[$page_counter]->label = $attributes['LABEL'];
		}
		
		$nc = $xpath->query ('mets:fptr[1]/@FILEID', $node);
		foreach($nc as $n)
		{
			$info->page_metadata[$page_counter]->PageID =  $n->firstChild->nodeValue;		
		
			$info->page_metadata[$page_counter]->PageID = str_replace('pageImg', '', $info->page_metadata[$page_counter]->PageID);
		
		}
	
		$page_counter++;
	
	}
	
}





//----------------------------------------------------------------------------------------
// Create manifest for BHL content in IA
function create_manifest_triples($info)
{
	$triples = array();
	
	$base_id 		= 'https://archive.org/details/' . $info->identifier;
	$subject_id 	= $base_id . '/manifest.json';
	$sequences_id 	= $base_id . '/canvas/default';

	$triples[] = '<' . $subject_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Manifest> .';

	// label
	$triples[] = '<' . $subject_id . '> <http://www.w3.org/2000/01/rdf-schema#label> "' . $info->title  . '" .';

	// Link to Internet Archive
	//$triples[] = '<' . $subject_id . '> <http://www.w3.org/2000/01/rdf-schema#seeAlso> <https://archive.org/details/' . $info->identifier  . '> .';

	// Link to BHL
	if ($info->bhl)
	{
		$triples[] = '<' . $subject_id . '> <http://purl.org/dc/terms/relation> <' . $info->bhl  . '> .';	
	}
	
	// Thumbnail
	if ($info->thumbnail)
	{
		$triples[] = '<' . $subject_id . '> <http://xmlns.com/foaf/0.1/thumbnail> <' . $info->thumbnail  . '> .';	
	}
	

	$triples[] = '<' . $subject_id . '> <http://iiif.io/api/presentation/2#hasSequences> <' . $sequences_id . '> .';
	$triples[] = '<' . $sequences_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Sequence> .';

	foreach ($info->page_metadata as $i => $page)
	{	
		$leafNum = str_pad($page->leafNum, 4, '0', STR_PAD_LEFT);
			
		$canvas_id = $base_id . '/canvas/c' . $leafNum;	
		$annotation_id = $canvas_id . '/annotation';	
		$resource_id = 'http://www.archive.org/download/' . $info->identifier . '/' . $info->identifier  . '/page/n' . $i . '_medium.jpg';
	
		// canvas
		$triples[] = '<' . $sequences_id . '> <http://iiif.io/api/presentation/2#hasCanvases> <' . $canvas_id . '> .';
		$triples[] = '<' . $canvas_id. '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://iiif.io/api/presentation/2#Canvas> .';
		$triples[] = '<' . $canvas_id . '> <http://www.w3.org/2000/01/rdf-schema#label> "' . $page->label . '" .';
		$triples[] = '<' . $canvas_id . '> <http://www.w3.org/2003/12/exif/ns#width> "' . $page->width . '" .';
		$triples[] = '<' . $canvas_id . '> <http://www.w3.org/2003/12/exif/ns#height> "' . $page->height . '" .';
		
		// if we have BHL PageID add link
		if (isset($page->PageID))
		{
			// dcterms:relation
			$triples[] = '<' . $canvas_id . '> <http://purl.org/dc/terms/relation> <https://www.biodiversitylibrary.org/page/' . $page->PageID . '> .';			
		}
		
		// annotation
		$triples[] = '<' . $canvas_id . '> <http://iiif.io/api/presentation/2#hasImageAnnotations> <' . $annotation_id . '> .';
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/ns/oa#Annotation> .';
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/ns/oa#motivatedBy> <http://iiif.io/api/presentation/2#painting> .';
		$triples[] = '<' . $annotation_id . '> <http://www.w3.org/ns/oa#hasTarget> <' . $canvas_id . '> .';
		
		// resource 
		$triples[] = '<' . $annotation_id . '> <http://iiif.io/api/presentation/2#hasAnnotations> <' . $resource_id . '> .';
		$triples[] = '<' . $resource_id . '> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/dc/dcmitype/Image> .';
		$triples[] = '<' . $resource_id . '> <http://purl.org/dc/terms/format> "image/jpeg" .';
		$triples[] = '<' . $resource_id . '> <http://www.w3.org/2003/12/exif/ns#width> "' . $page->width . '" . ';
		$triples[] = '<' . $resource_id . '> <http://www.w3.org/2003/12/exif/ns#height> "' . $page->height . '" . ';
		
			
		
	}
	
	//print_r($triples);
	
	$nt = join("\n", $triples);
	
	if (1)
	{
		// triples
		echo $nt . "\n";
	}
	else
	{
	
		// JSON-LD
	
		
	
		$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

		// Context 
		$context = new stdclass;

		$context->sc = 'http://iiif.io/api/presentation/2#';

		$context->rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
		$context->type = 'rdf:type';

		$context->rdfs = 'http://www.w3.org/2000/01/rdf-schema#';

		$context->dc = 'http://purl.org/dc/elements/1.1/';
		$context->dcterms = 'http://purl.org/dc/terms/';
		$context->dctypes = 'http://purl.org/dc/dcmitype/';


		$context->exif = 'http://www.w3.org/2003/12/exif/ns#';
		$context->oa = 'http://www.w3.org/ns/oa#';


		$context->sequences = new stdclass;
		$context->sequences->{'@id'} = 'sc:hasSequences';
		$context->sequences->{'@container'} = '@set';

		$context->canvases = new stdclass;
		$context->canvases->{'@id'} = 'sc:hasCanvases';
		$context->canvases->{'@container'} = '@set';

		$context->images = new stdclass;
		$context->images->{'@id'} = 'sc:hasImageAnnotations';
		$context->images->{'@container'} = '@set';

		$context->resource = 'sc:hasAnnotations';


		// works
		$context->motivation = new stdclass;
		$context->motivation->{'@type'} = '@id';
		$context->motivation->{'@id'} = 'oa:motivatedBy';

		// works
		$context->on = new stdclass;
		$context->on->{'@type'} = '@id';
		$context->on->{'@id'} = 'oa:hasTarget';
	
		$context->label 	= 'rdfs:label';
		$context->format 	= 'dcterms:format';
		$context->related 	= 'dcterms:relation';
		$context->seeAlso 	= 'rdfs:seeAlso';

		$context->width 	= 'exif:width';
		$context->height 	= 'exif:height';

		$frame = (object)array(
			'@context' => $context,	
			'@type' => 'http://iiif.io/api/presentation/2#manifest'
		);	
		$data = jsonld_frame($doc, $frame);
	
		echo 'var data=' 
			. json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
			. ';';
	}
	
	



}

//----------------------------------------------------------------------------------------
function bhl_item_to_ia($item)
{
	$ia = '';
	
	$url = 'https://www.biodiversitylibrary.org/api2/httpquery.ashx?op=GetItemMetadata&itemid=' . $item . '&pages=f&apikey=' . '0d4f0303-712e-49e0-92c5-2113a5959159' . '&format=json';

	$json = get($url);
	
	//echo $url;

	$obj = json_decode($json);
	
	//print_r($obj);
	
	if (isset($obj->Result->SourceIdentifier))
	{
		$ia = $obj->Result->SourceIdentifier;
	}

	return $ia;

}

//----------------------------------------------------------------------------------------
// Get BHL ItemID for a BHL pageID
function bhl_page_to_item($PageID)
{
	$ItemID = '';
	
	$url = 'https://www.biodiversitylibrary.org/api2/httpquery.ashx?op=GetPageMetadata&pageid=' . $PageID . '&pages=f&apikey=' . '0d4f0303-712e-49e0-92c5-2113a5959159' . '&format=json';

	$json = get($url);
	
	//echo $url;

	$obj = json_decode($json);
	
	//print_r($obj);
	
	if (isset($obj->Result->ItemID))
	{
		$ItemID = $obj->Result->ItemID;
	}

	return $ItemID;

}

//----------------------------------------------------------------------------------------
// Get BHL ItemID for a BioStor id
function biostor_to_item($biostor)
{
	$ItemID = '';
	
	$parameters = array(
		'op' => 'GetPartByIdentifier',
		'type' => 'biostor',
		'value' => $biostor,
		'format' => 'json',
		'apikey' => '0d4f0303-712e-49e0-92c5-2113a5959159'
	);
	
	
	$url = 'https://www.biodiversitylibrary.org/api2/httpquery.ashx?' . http_build_query($parameters);

	//echo $url . "\n";
	
	$json = get($url);
	
	//echo $json;
	
	$obj = json_decode($json);
	
	//print_r($obj);
	
	if (isset($obj->Result[0]->ItemID))
	{
		$ItemID = $obj->Result[0]->ItemID;
	}

	
	return $ItemID;
	
}






$ia = 'telopea8natic';

$ia = 'mobot31753002251079';

//$ia = 'pacific-insects-6-005';
//$ia = 'eos-a0sxkss5';

//$ia = 'bulletinofnatura32natulond'; // scan is zip file, may need to parse differently

//$ia = 'mobot31753002733472';

$ia = 'gardensbulletins461unse';



// List of BHL Items to add
$items = array(
90529,
49423,
49419,
49405,
48797,
48795,
48794,
49408,
48796,
49409,
49406,
48798,

);

// Annales Musei botanici lugduno-batavi
$items = array(
2540,
2543,
2545,
2547,
);

// Prodromus systematis naturalis regni vegetabilis
$items=array(
7150,
7151,
7152,
7153,
7154,
7155,
7156,
7157,
2477,
7158,
7159,
7160,
7161,
7162,
7163,
109211,
7165,
7166,
7167,
7168,
7169,
114361,
114362,
249664,
249740,
251542,
252387,
256853,
256898,
256946,
257265,
257276,
257288,
257385,
257482,
258862,
260177,
262821,
);


$items = array(
49408
);


//----------------------------------------------------------------------------------------
// get list of items from list of PageIDs
if (0)
{
	$items = array();

	$pages = array(
	//17913792, // fails as no scan data as XML (it's a zip)
	15628854,
	);

	foreach ($pages as $PageID)
	{
		$ItemID = bhl_page_to_item($PageID);
		if ($ItemID != '')
		{
			$items[] = $ItemID;
		}
	}
}

//----------------------------------------------------------------------------------------
// get list of items from list of BioStor ids

$items = array();

$biostor_ids = array(
//206864,
217668,
);


foreach ($biostor_ids as $biostor)
{
	$ItemID = biostor_to_item($biostor);
	if ($ItemID != '')
	{
		$items[] = $ItemID;
	}
}



$ias = array();

//$ias = array('mobot31753002456132');

// Get IA identifiers for items
foreach ($items as $item)
{
	$ia = bhl_item_to_ia($item);
	if ($ia != '')
	{
		$ias[] = $ia;
	}
}

// Get (unless cached) metadata from IA, generate IIIF manifests
foreach ($ias as $ia)
{


	ia_download($ia, dirname(__FILE__) . '/' . $config['cache_dir']);

	$info = new stdclass;
	$info->identifier =  $ia;
	$info->page_metadata = array();

	ia_scandata($ia, dirname(__FILE__) . '/' . $config['cache_dir'], $info );

	bhl_details($ia, dirname(__FILE__) . '/' . $config['cache_dir'], $info );

	//print_r($info->page_metadata);

	// Manifest
	//create_manifest($ia, dirname(__FILE__) . '/' . $config['cache_dir'], 'http://localhost/~rpage/bhl-ia-iiif-o/cache', $info->page_metadata);

	create_manifest_triples($info);
}

?>


