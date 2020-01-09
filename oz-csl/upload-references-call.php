<?php

// Process changed records and add to triple store

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(dirname(__FILE__)) . '/www/config.inc.php');


$ids = array(
//'https://doi.org/10.1007/s12225-010-9180-9',
//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.174.3.5',
//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.305.1.9',
//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.195.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.208.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.205.4.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.195.1.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.193.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.159.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.164.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.158.3.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.191.1.13',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.188.4.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.184.1.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.184.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.183.4.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.164.2.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.207.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.207.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.203.2.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.202.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.202.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.192.3.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.161.1.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.192.2.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.129.1.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.161.4.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.166.2.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.163.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.178.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.176.1.29',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.178.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.178.3.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.167.3.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.170.4.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.173.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.174.4.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.174.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.5.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.5.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.5.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.1.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.175.3.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.170.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.217.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.217.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.217.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.217.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.213.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.205.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.212.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.212.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.219.3.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.230.2.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.226.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.231.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.231.2.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.231.3.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.60.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.54.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.227.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.222.4.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.221.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.233.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.70.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.224.1.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.237.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.234.1.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.64.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.238.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.238.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.39.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.38.1.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.236.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.234.2.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.239.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.245.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.246.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.247.1.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.247.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.247.2.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.247.4.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.270.1.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.270.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.273.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.277.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.278.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.275.2.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.278.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.282.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.282.1.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.282.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.263.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.263.1.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.296.1.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.296.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.297.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.299.2.13',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.302.2.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.308.2.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.303.1.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.308.1.14',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.306.2.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.305.1.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.307.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.307.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.305.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.302.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.302.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.166.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.117.1.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.233.1.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.238.2.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.254.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.257.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.261.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.265.2.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.265.2.12',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.266.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.266.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.268.4.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.269.3.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.276.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.277.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.280.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.292.2.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.292.3.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.298.2.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.298.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.299.1.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.299.2.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.306.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.306.3.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.309.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.309.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.309.3.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.312.1.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.312.1.16',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.312.2.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.313.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.313.2.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.314.2.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.316.2.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.316.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.317.1.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.317.3.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.317.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.319.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.321.2.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.323.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.323.2.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.324.3.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.324.3.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.326.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.329.3.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.332.1.8',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.333.1.14',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.333.2.14',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.334.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.338.1.13',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.338.3.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.347.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.349.1.14',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.349.2.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.349.2.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.349.3.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.350.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.356.1.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.357.2.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.360.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.360.3.11',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.362.1.5',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.364.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.364.1.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.364.3.2',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.365.3.7',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.367.1.9',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.369.1.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.369.2.6',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.371.1.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.371.4.3',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.374.2.12',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.374.2.13',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.376.2.1',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.379.1.10',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.379.2.4',
'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.63.1.2',
);

$ids = array(
"10.1007/s00606-005-0315-7",
"10.1016/s0304-3770(97)00006-5",
"10.1046/j.1095-8339.2002.00065.x",
"10.1055/s-2001-12895",
"10.1071/sb02020",
"10.1086/509607",
"10.1093/aob/mcf259",
"10.1093/aob/mcg092",
"10.1093/bioinformatics/14.9.817",
"10.1109/tac.1974.1100705",
"10.1111/j.1095-8339.2003.00244.x",
"10.1111/j.1095-8339.2009.00961.x",
"10.1600/0363644042451062",
"10.2307/1224736",
"10.3732/ajb.0900157",
"10.3767/000651909x476157",	
	);
	
$ids=array(
//'https://doi.org/10.1093/aob/mcp090',
//'https://doi.org/10.2307/1222581',
//'https://doi.org/10.1080/00275514.2018.1477004',
//'https://doi.org/10.1126/science.aaf7115',
	'https://doi.org/10.1007/s00606-010-0413-z',



);

	$ids=array(
	'https://doi.org/10.1111/j.1096-3642.2009.00580.x',
"https://doi.org/10.1071/IS02044",
 "https://doi.org/10.1111/j.1096-3642.1994.tb01491.x",
 "https://doi.org/10.1111/j.1096-3642.2004.00123.x",
 "https://doi.org/10.1111/j.1096-3642.2009.00580.x",
 "https://doi.org/10.1126/science.1100167",
 "https://doi.org/10.2307/2412116",
 "https://doi.org/10.5962/bhl.part.13273",
 "https://doi.org/10.5962/bhl.part.15964",
 "https://doi.org/10.11646/zootaxa.1326.1.3",

	);
	
		$ids=array(
	//'https://doi.org/10.1007/s10531-013-0595-0',
	'https://doi.org/10.3897/phytokeys.50.5080',
	);
	
	$ids=array(
	//'https://doi.org/10.3897/phytokeys.50.5080',
	//'https://doi.org/10.11646/phytotaxa.266.2.9',
	//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.266.2.9',
	//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.220.1.6'
	//'http://localhost/~rpage/microcitation/www/citations?guid=10.11646/phytotaxa.334.3.4',
	//'https://doi.org/10.1080/00837792.2015.1082270',
	//'https://doi.org/10.1080/00837792.2019.1675956',

	//'https://doi.org/10.1111/njb.02333',
	//'https://doi.org/10.11609/jott.2708.9.1.9756-9760',
	
	'http://localhost/~rpage/microcitation/www/citations?guid=10.1016/j.sajb.2017.06.021',

	);	



// Create empty file to write triples to
$filename = 'references-call.nt';
file_put_contents ($filename, '');

foreach ($ids as $id)
{
	
	echo $id . "\n";
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/references/_list/triples/nt?key=" . urlencode('"' . $id . '"'));
	
	//echo $resp;
	
	// append triples to file
	file_put_contents ($filename, $resp, FILE_APPEND);

}

// Send to triple store
$namespace = 'https://crossref.org';

// curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@cslx.nt'  --progress-bar | tee /dev/null

$command = "curl " . $config['sparql_endpoint'] . "?context-uri=$namespace -H 'Content-Type: text/rdf+n3' --data-binary '@" . $filename . "' --progress-bar | tee /dev/null";
echo $command . "\n";

system($command);

	
?>