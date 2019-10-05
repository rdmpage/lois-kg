<?php

// Generate ISSN lookup tables

require_once (dirname(__FILE__) . '/adodb5/adodb.inc.php');


//----------------------------------------------------------------------------------------
function finger_print ($text)
{
	$str = mb_strtolower($text);
	
	// en
	$str = preg_replace('/\bfor\b/u', '', $str);
	$str = preg_replace('/\band\b/u', '', $str);
	$str = preg_replace('/\bof\b/u', '', $str);
	$str = preg_replace('/\bthe\b/u', '', $str);
	$str = preg_replace('/\bin\b/u', '', $str);

	// fr
	$str = preg_replace('/\bde\b/u', '', $str);
	$str = preg_replace('/\bla\b/u', '', $str);
	$str = preg_replace('/\bet\b/u', '', $str);
	
	// de
	$str = preg_replace('/\bdas\b/u', '', $str);
	$str = preg_replace('/\bder\b/u', '', $str);
	$str = preg_replace('/\bfur\b/u', '', $str);
	
	// numbers
	$str = preg_replace('/\d+/u', '', $str);
	
	
	// whitespace
	$str = preg_replace('/\s+/u', '', $str);

	// punctuation
	$str = preg_replace('/[\.|,|\'|\(|\)|"|:|-|&|-]+/u', '', $str);
	
	return $str;
}

//----------------------------------------------------------------------------------------

$issns = array();

// Grab from CouchDB to get a relativey clean set of ISSN with full journal names

$json = '{
    "total_rows": 54,
    "offset": 0,
    "rows": [
        {
            "id": "https://doi.org/10.1590/0102-33062017abb0368",
            "key": "Acta Botanica Brasilica",
            "value": "0102-3306"
        },
        {
            "id": "https://doi.org/10.1640/0002-8444-103.1.21",
            "key": "American Fern Journal",
            "value": "0002-8444"
        },
        {
            "id": "https://doi.org/10.3989/ajbm.2274",
            "key": "Anales del Jardín Botánico de Madrid",
            "value": "0211-1322"
        },
        {
            "id": "https://doi.org/10.5735/085.049.0404",
            "key": "Annales Botanici Fennici",
            "value": "0003-3847"
        },
        {
            "id": "https://doi.org/10.5902/2358198013894",
            "key": "Balduinia",
            "value": "1808-2688"
        },
        {
            "id": "https://doi.org/10.5902/2358198014020",
            "key": "Balduinia",
            "value": "1808-2688"
        },
        {
            "id": "https://doi.org/10.3767/000651906x622247",
            "key": "Blumea - Biodiversity, Evolution and Biogeography of Plants",
            "value": "0006-5196"
        },
        {
            "id": "https://doi.org/10.3767/000651912x653813",
            "key": "Blumea - Biodiversity, Evolution and Biogeography of Plants",
            "value": "0006-5196"
        },
        {
            "id": "https://doi.org/10.11606/issn.2316-9052.v19i0p55-169",
            "key": "Boletim de Botânica",
            "value": "0302-2439"
        },
        {
            "id": "https://doi.org/10.11606/issn.2316-9052.v21i2p277-279",
            "key": "Boletim de Botânica",
            "value": "0302-2439"
        },
        {
            "id": "https://doi.org/10.1111/j.1095-8339.2006.00481.x",
            "key": "Botanical Journal of the Linnean Society",
            "value": "0024-4074"
        },
        {
            "id": "https://doi.org/10.1007/s12228-008-9053-9",
            "key": "Brittonia",
            "value": "0007-196X"
        },
        {
            "id": "https://doi.org/10.2307/2806499",
            "key": "Brittonia",
            "value": "0007-196X"
        },
        {
            "id": "https://doi.org/10.2307/4118686",
            "key": "Bulletin of Miscellaneous Information (Royal Gardens, Kew)",
            "value": "0366-4457"
        },
        {
            "id": "https://doi.org/10.3989/collectbot.2011.v30.007",
            "key": "Collectanea Botanica",
            "value": "0010-0730"
        },
        {
            "id": "https://doi.org/10.1017/S0960428600004054",
            "key": "Edinburgh Journal of Botany",
            "value": "0960-4286"
        },
        {
            "id": "https://doi.org/10.1017/S0960428608005015",
            "key": "Edinburgh Journal of Botany",
            "value": "0960-4286"
        },
        {
            "id": "https://doi.org/10.1017/S0960428609990230",
            "key": "Edinburgh Journal of Botany",
            "value": "0960-4286"
        },
        {
            "id": "https://doi.org/10.1017/s0960428608004915",
            "key": "Edinburgh Journal of Botany",
            "value": "0960-4286"
        },
        {
            "id": "https://doi.org/10.1016/S1567-1356(03)00226-5",
            "key": "FEMS Yeast Research",
            "value": "1567-1356"
        },
        {
            "id": "https://doi.org/10.1007/s13225-017-0386-0",
            "key": "Fungal Diversity",
            "value": "1560-2745"
        },
        {
            "id": "https://doi.org/10.4067/s0717-66432012000200006",
            "key": "Gayana. Botánica",
            "value": "0717-6643"
        },
        {
            "id": "https://doi.org/10.3100/025.018.0214",
            "key": "Harvard Papers in Botany",
            "value": "1043-4534"
        },
        {
            "id": "http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=http://www.jstor.org/stable/24529694",
            "key": "Journal of The Washington Academy of Sciences",
            "value": "0043-0439"
        },
        {
            "id": "http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=10.2307/4110986",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.1007/s12225-008-9087-x",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.1007/s12225-011-9262-3",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.1007/s12225-011-9295-7",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.2307/4110907",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.2307/4110986",
            "key": "Kew Bulletin",
            "value": "0075-5974"
        },
        {
            "id": "https://doi.org/10.13102/neod.32.1",
            "key": "Neodiversity",
            "value": "1809-5348"
        },
        {
            "id": "https://doi.org/10.13102/neod.41.3",
            "key": "Neodiversity",
            "value": "1809-5348"
        },
        {
            "id": "https://doi.org/10.1111/njb.01126",
            "key": "Nordic Journal of Botany",
            "value": "0107-055X"
        },
        {
            "id": "https://doi.org/10.2307/3391911",
            "key": "Novon",
            "value": "1055-3177"
        },
        {
            "id": "https://doi.org/10.2307/3393085",
            "key": "Novon",
            "value": "1055-3177"
        },
        {
            "id": "http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=https://www.zin.ru/journals/parazitologiya/content/1996/prz_1996_2_2_Yankovsky.pdf",
            "key": "Parazitologiya",
            "value": "0031-1847"
        },
        {
            "id": "https://doi.org/10.3897/phytokeys.77.11345",
            "key": "PhytoKeys",
            "value": "1314-2011"
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.174.4.5",
            "key": "Phytotaxa",
            "value": "1179-3155"
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.186.4.4",
            "key": "Phytotaxa",
            "value": "1179-3155"
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.238.1.4",
            "key": "Phytotaxa",
            "value": "1179-3155"
        },
        {
            "id": "https://doi.org/10.11646/phytotaxa.26.1.2",
            "key": "Phytotaxa",
            "value": "1179-3155"
        },
        {
            "id": "https://doi.org/10.1002/fedr.19120110419",
            "key": "Repertorium novarum specierum regni vegetabilis",
            "value": "0375-121X"
        },
        {
            "id": "https://doi.org/10.1002/fedr.19300070501",
            "key": "Repertorium novarum specierum regni vegetabilis",
            "value": "0375-121X"
        },
        {
            "id": "https://doi.org/10.1590/2175-7860201869224",
            "key": "Rodriguésia",
            "value": "0370-6583"
        },
        {
            "id": "https://doi.org/10.5479/si.0081024x.89",
            "key": "Smithsonian Contributions to Botany",
            "value": "0081-024X"
        },
        {
            "id": "https://doi.org/10.1600/0363644053661832",
            "key": "Systematic Botany",
            "value": "0363-6445"
        },
        {
            "id": "https://doi.org/10.1600/036364408784571581",
            "key": "Systematic Botany",
            "value": "0363-6445"
        },
        {
            "id": "https://doi.org/10.1600/036364412x648733",
            "key": "Systematic Botany",
            "value": "0363-6445"
        },
        {
            "id": "http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=10.6165/tai.1983.28.146",
            "key": "Taiwania",
            "value": "0372-333X"
        },
        {
            "id": "https://doi.org/10.1002/tax.12017",
            "key": "TAXON",
            "value": "0040-0262"
        },
        {
            "id": "https://doi.org/10.2307/25065588",
            "key": "TAXON",
            "value": "0040-0262"
        },
        {
            "id": "https://doi.org/10.1017/s0024282915000328",
            "key": "The Lichenologist",
            "value": "0024-2829"
        },
        {
            "id": "https://doi.org/10.3372/wi.44.44211",
            "key": "Willdenowia",
            "value": "0511-9618"
        },
        {
            "id": "http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=10.13346/j.mycosystema.130120",
            "key": "菌物学报",
            "value": "1672-6472"
        }
    ]
}';

$obj = json_decode($json);

foreach ($obj->rows as $row)
{
	$key = finger_print($row->key);
		
	if (!isset($issns[$key]))
	{
		$issns[$key] = array();
	}
	if (!in_array($row->value, $issns[$key]))
	{
		$issns[$key][] = $row->value;
	}
			
}





//----------------------------------------------------------------------------------------
// add from IPNI
//----------------------------------------------------------------------------------------
$db = NewADOConnection('mysqli');
$db->Connect("localhost", 
	'root', '', 'ipni');

// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 


$count = 0;

$page = 1000;
$offset = 0;

$result = $db->Execute('SET max_heap_table_size = 1024 * 1024 * 1024');
$result = $db->Execute('SET tmp_table_size = 1024 * 1024 * 1024');

$done = false;

while (!$done)
{
	$sql = 'SELECT DISTINCT Publication, issn FROM `names` WHERE issn IS NOT NULL LIMIT ' . $page . ' OFFSET ' . $offset;

	$result = $db->Execute($sql);
	if ($result == false) die("failed [" . __FILE__ . ":" . __LINE__ . "]: " . $sql);

	while (!$result->EOF && ($result->NumRows() > 0)) 
	{	
		$journal = $result->fields['Publication'];
		
		$issn = $result->fields['issn'];		
		
		$key = finger_print($journal);
		
		//echo $journal . "\n";
		//echo $key . "\n";
		
		if (!isset($issns[$key]))
		{
			$issns[$key] = array();
		}
		if (!in_array($issn, $issns[$key]))
		{
			$issns[$key][] = $issn;
		}
		
		
		$count++;

		$result->MoveNext();
	}
	
	if ($result->NumRows() < $page)
	{
		$done = true;
	}
	else
	{
		$offset += $page;
	}
}


print_r($issns);

ksort($issns);

print_r($issns);

$keys = array_keys($issns);

print_r($keys);

// Dump for fuzzy string matching

echo "[\n";
foreach ($keys as $k)
{
	echo "'" . $k . "',\n";
}
echo "]\n";

// Dump for matching fingerprints to ISSN
echo json_encode($issns, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);






?>