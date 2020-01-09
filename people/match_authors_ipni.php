<?php

// Given a bibliographic URI, match authors of work to IPNI taxonomic authors


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


// Given a bibliographic URI, match authors of work to IPNI taxonomic authors

$uris = array(
//'https://doi.org/10.11646/phytotaxa.348.3.6',
//'https://doi.org/10.11646/phytotaxa.295.3.1',
//'https://doi.org/10.11646/phytotaxa.297.2.5',
//'https://doi.org/10.12705/661.6',
//'https://doi.org/10.1371/journal.pone.0170107',
//'https://doi.org/10.1007/s12225-017-9681-x',
//'https://doi.org/10.7751/telopea11313',
//'https://doi.org/10.1017/s0960428600002146',

//'https://doi.org/10.1017/s0960428600001128',
//'https://doi.org/10.1017/s096042860200001X',
//'https://doi.org/10.1017/s0960428603000404',

'https://doi.org/10.1017/s0960428607000777',
'https://doi.org/10.1017/s0960428609005484',
'https://doi.org/10.1017/s0960428609990266',
//'https://doi.org/10.1017/s0960428617000087',

//'https://doi.org/10.3897/phytokeys.109.27566',
//'https://doi.org/10.2307/2418868',
'https://doi.org/10.11931/guihaia.gxzw201512015',
'https://doi.org/10.26492/gbs69(2).2017-08',
'https://doi.org/10.11646/phytotaxa.340.2.7',
'https://doi.org/10.11646/phytotaxa.348.3.6',
'https://doi.org/10.11646/phytotaxa.295.3.1',
'https://doi.org/10.11646/phytotaxa.297.2.5',
'https://doi.org/10.12705/661.6',

'https://doi.org/10.7751/telopea11313',
'https://doi.org/10.1017/s0960428600002146',

'https://doi.org/10.1017/10.3969/j.issn.1000-3142.2012.04.001',

);

$uris = array(
//'https://doi.org/10.11646/phytotaxa.273.3.4',
//'https://doi.org/10.7751/telopea11313', // missing author info in metadata
//'https://doi.org/10.1017/s0960428600002146',

'https://doi.org/10.3969/j.issn.1000-3142.2012.04.001',
'https://www.jstor.org/stable/41762765',
'https://doi.org/10.1002/fedr.19110091909',
'https://doi.org/10.26492/gbs69(2).2017-08',
'https://doi.org/10.1017/s0960428618000161',
'https://doi.org/10.1111/jse.12064',

);


$uris = array(
//'https://doi.org/10.1111/jse.12064',
//'https://doi.org/10.1016/j.ympev.2013.01.006',
'https://doi.org/10.11646/phytotaxa.348.3.6',

'https://doi.org/10.3969/j.issn.1000-3142.2012.04.001',
'https://doi.org/10.26492/gbs69(2).2017-08',
'https://doi.org/10.1017/s0960428600002146',
'https://doi.org/10.12705/661.6',
'https://doi.org/10.1371/journal.pone.0170107',
'https://doi.org/10.1007/s12225-017-9681-x',
'https://doi.org/10.11646/phytotaxa.340.2.7',
'https://doi.org/10.11646/phytotaxa.348.3.6',
'https://doi.org/10.11646/phytotaxa.295.3.1',
'https://doi.org/10.11646/phytotaxa.297.2.5',
'https://doi.org/10.12705/661.6',

'https://doi.org/10.1640/0002-8444-101.4.261',

);

$uris=array("https://doi.org/10.5252/adansonia2018v40a3",
"https://doi.org/10.3897/phytokeys.95.22916",
"https://doi.org/10.11646/phytotaxa.338.2.1",
"https://doi.org/10.3897/phytokeys.96.23142",
"https://doi.org/10.11646/phytotaxa.333.1.2",
"https://doi.org/10.11646/phytotaxa.333.2.9",
"https://doi.org/10.11646/phytotaxa.334.1.17",
"https://doi.org/10.11646/phytotaxa.385.1.1",
"https://doi.org/10.11646/phytotaxa.336.3.9",
"https://doi.org/10.11646/phytotaxa.338.2.7",
"https://doi.org/10.11646/phytotaxa.340.1.1",
"https://doi.org/10.11646/phytotaxa.340.2.2",
"https://doi.org/10.11646/phytotaxa.340.2.6",
"https://doi.org/10.11646/phytotaxa.340.2.11",
"https://doi.org/10.1590/0102-33062017abb0368",
"https://doi.org/10.11646/phytotaxa.340.3.9",
"https://doi.org/10.11646/phytotaxa.344.2.1",
"https://doi.org/10.11646/phytotaxa.344.3.11",
"https://doi.org/10.11646/phytotaxa.345.3.4",
"https://doi.org/10.3897/phytokeys.94.21553",
"https://doi.org/10.3897/phytokeys.94.21557",
"https://doi.org/10.3897/phytokeys.95.21832",
"https://doi.org/10.3897/phytokeys.95.21586",
"https://doi.org/10.15407/ukrbotj75.01.003",
"https://doi.org/10.1371/journal.pone.0190385",
"https://doi.org/10.3989/ajbm.2479",
"https://doi.org/10.3989/ajbm.2486",
"https://doi.org/10.3897/phytokeys.92.21815",
"https://doi.org/10.3897/phytokeys.92.22205",
"https://doi.org/10.1371/journal.pone.0192226",
"https://doi.org/10.3897/phytokeys.94.21650",
"https://doi.org/10.3897/phytokeys.94.21329",
"https://doi.org/10.3897/phytokeys.94.20861",
"https://doi.org/10.3897/phytokeys.95.23434",
"https://doi.org/10.1007/s12225-018-9739-4",
"https://doi.org/10.11646/phytotaxa.338.2.6",
"https://doi.org/10.11646/phytotaxa.333.1.3",
"https://doi.org/10.11646/phytotaxa.333.1.5",
"https://doi.org/10.11646/phytotaxa.333.1.10",
"https://doi.org/10.11646/phytotaxa.333.2.6",
"https://doi.org/10.11646/phytotaxa.333.2.7",
"https://doi.org/10.11646/phytotaxa.333.2.13",
"https://doi.org/10.11646/phytotaxa.333.2.15",
"https://doi.org/10.11646/phytotaxa.334.1.6",
"https://doi.org/10.11646/phytotaxa.334.1.8",
"https://doi.org/10.11646/phytotaxa.334.1.9",
"https://doi.org/10.11646/phytotaxa.334.1.10",
"https://doi.org/10.11646/phytotaxa.334.1..13",
"https://doi.org/10.11646/phytotaxa.334.2.1",
"https://doi.org/10.11646/phytotaxa.334.2.4",
"https://doi.org/10.11646/phytotaxa.334.3.4",
"https://doi.org/10.11646/phytotaxa.334.3.7",
"https://doi.org/10.11646/phytotaxa.334.3.9",
"https://doi.org/10.11646/phytotaxa.336.1.5",
"https://doi.org/10.11646/phytotaxa.336.1.6",
"https://doi.org/10.11646/phytotaxa.336.1.8",
"https://doi.org/10.11646/phytotaxa.336.2.6",
"https://doi.org/10.11646/phytotaxa.336.3.2",
"https://doi.org/10.11646/phytotaxa.336.3.3",
"https://doi.org/10.11646/phytotaxa.336.3.6",
"https://doi.org/10.11646/phytotaxa.338.1.5",
"https://doi.org/10.11646/phytotaxa.338.1.8",
"https://doi.org/10.11646/phytotaxa.338.1.9",
"https://doi.org/10.11646/phytotaxa.338.1.12",
"https://doi.org/10.11646/phytotaxa.338.1.16",
"https://doi.org/10.11646/phytotaxa.338.2.9",
"https://doi.org/10.11646/phytotaxa.340.1.2",
"https://doi.org/10.11646/phytotaxa.340.1.6",
"https://doi.org/10.11646/phytotaxa.340.1.8",
"https://doi.org/10.11646/phytotaxa.340.2.3",
"https://doi.org/10.11646/phytotaxa.340.2.7",
"https://doi.org/10.11646/phytotaxa.340.2.8",
"https://doi.org/10.11646/phytotaxa.340.3.5",
"https://doi.org/10.11646/phytotaxa.340.3.7",
"https://doi.org/10.11646/phytotaxa.343.1.5",
"https://doi.org/10.11646/phytotaxa.343.2.3",
"https://doi.org/10.11646/phytotaxa.343.2.4",
"https://doi.org/10.11646/phytotaxa.343.2.7",
"https://doi.org/10.11646/phytotaxa.343.3.3",
"https://doi.org/10.11646/phytotaxa.343.3.4",
"https://doi.org/10.11646/phytotaxa.343.3.6",
"https://doi.org/10.11646/phytotaxa.343.3.10",
"https://doi.org/10.11646/phytotaxa.343.3.11",
"https://doi.org/10.11646/phytotaxa.344.1.2",
"https://doi.org/10.11646/phytotaxa.344.1.6",
"https://doi.org/10.11646/phytotaxa.344.1.18",
"https://doi.org/10.11646/phytotaxa.344.2.4",
"https://doi.org/10.11646/phytotaxa.344.2.7",
"https://doi.org/10.11646/phytotaxa.344.2.8",
"https://doi.org/10.11646/phytotaxa.344.3.4",
"https://doi.org/10.11646/phytotaxa.344.3.12",
"https://doi.org/10.11646/phytotaxa.345.1.7",
"https://doi.org/10.11646/phytotaxa.345.1.9",
"https://doi.org/10.11646/phytotaxa.345.1.10",
"https://doi.org/10.11646/phytotaxa.345.2.7",
"https://doi.org/10.11646/phytotaxa.345.2.9",
"https://doi.org/10.1007/s12225-018-9775-0",
"https://doi.org/10.11646/phytotaxa.358.3.6",
"https://doi.org/10.3767/blumea.2018.63.02.03",
"https://doi.org/10.11646/phytotaxa.372.2.3",
"https://doi.org/10.14203/reinwardtia.v17i1.3517",
"https://doi.org/10.20531/tfb.2018.46.1.13",
"https://doi.org/10.1600/036364418x696987",
"https://doi.org/10.5735/085.055.0417",
"https://doi.org/10.11646/phytotaxa.374.1.10",
"https://doi.org/10.1111/njb.01689",
"https://doi.org/10.11646/phytotaxa.369.1.5",
"https://doi.org/10.3767/blumea.2018.63.01.03",
"https://doi.org/10.14258/turczaninowia.21.2.11",
"https://doi.org/10.26492/gbs70(1).2018-12",
"https://doi.org/10.11646/phytotaxa.373.2.7",
"https://doi.org/10.11646/phytotaxa.376.3.3",
"https://doi.org/10.1640/0002-8444-108.1.27",
"https://doi.org/10.11646/phytotaxa.360.3.6",
"https://doi.org/10.11646/phytotaxa.364.3.11",
"https://doi.org/10.1016/j.sajb.2018.02.402",
"https://doi.org/10.11646/phytotaxa.346.2.8",
"https://doi.org/10.12705/675.7",
"https://doi.org/10.1071/sb17062",
"https://doi.org/10.1071/sb17026",
"https://doi.org/10.11646/phytotaxa.360.2.1",
"https://doi.org/10.5902/2358198035738",
"https://doi.org/10.1111/njb.01780",
"https://doi.org/10.11646/phytotaxa.364.3.6",
"https://doi.org/10.11646/phytotaxa.371.2.8",
"https://doi.org/10.5902/2358198033221",
"https://doi.org/10.3417/2018170",
"https://doi.org/10.3897/phytokeys.112.24897",
"https://doi.org/10.11646/phytotaxa.379.1.4",
"https://doi.org/10.3767/blumea.2018.63.01.07",
"https://doi.org/10.3897/phytokeys.114.27395",
"https://doi.org/10.5735/085.055.0114",
"https://doi.org/10.3897/phytokeys.97.20975",
"https://doi.org/10.3372/wi.48.48208",
"https://doi.org/10.3767/blumea.2018.63.01.06",
"https://doi.org/10.11646/phytotaxa.383.3.6",
"https://doi.org/10.12705/672.2",
"https://doi.org/10.7751/telopea11463",
"https://doi.org/10.12705/672.9",
"https://doi.org/10.3906/bot-1705-19",
"https://doi.org/10.11646/phytotaxa.382.2.6",
"https://doi.org/10.1093/botlinnean/boy055",
"https://doi.org/10.11646/phytotaxa.382.1.3",
"https://doi.org/10.1111/njb.01549",
"https://doi.org/10.3989/ajbm.2499",
"https://doi.org/10.12705/672.5",
"https://doi.org/10.3417/2018053",
"https://doi.org/10.11646/phytotaxa.382.2.3",
"https://doi.org/10.3417/d1700005",
"https://doi.org/10.11646/phytotaxa.373.2.2",
"https://doi.org/10.11646/phytotaxa.350.2.6",
"https://doi.org/10.11646/phytotaxa.351.1.7",
"https://doi.org/10.11646/phytotaxa.358.3.7",
"https://doi.org/10.1080/00837792.2017.1409940",
"https://doi.org/10.6165/tai.2018.63.248",
"https://doi.org/10.11646/phytotaxa.351.1.8",
"https://doi.org/10.5735/085.055.0116",
"https://doi.org/10.11646/phytotaxa.376.1.4",
"https://doi.org/10.11646/phytotaxa.376.5.5",
"https://doi.org/10.3897/phytokeys.98.25044",
"https://doi.org/10.11646/phytotaxa.347.4.1",
"https://doi.org/10.1600/036364418x696969",
"https://doi.org/10.11646/phytotaxa.364.2.7",
"https://doi.org/10.1007/s12225-018-9776-z",
"https://doi.org/10.11646/phytotaxa.370.1.1",
"https://doi.org/10.3897/phytokeys.111.27175",
"https://doi.org/10.1111/njb.01909",
"https://doi.org/10.3417/2018066",
"https://doi.org/10.11646/phytotaxa.364.1.2",
"https://doi.org/10.6165/tai.2018.63.183",
"https://doi.org/10.1111/njb.02067",
"https://doi.org/10.11646/phytotaxa.364.2.1",
"https://doi.org/10.11646/phytotaxa.385.1.4",
"https://doi.org/10.11646/phytotaxa.375.3.3",
"https://doi.org/10.1016/j.sajb.2018.03.006",
"https://doi.org/10.6165/tai.2018.63.327",
"https://doi.org/10.6165/tai.2018.63.163",
"https://doi.org/10.1111/njb.02058",
"https://doi.org/10.11646/phytotaxa.369.3.1",
"https://doi.org/10.20531/tfb.2018.46.1.06",
"https://doi.org/10.11646/phytotaxa.349.3.1",
"https://doi.org/10.11646/phytotaxa.383.3.2",
"https://doi.org/10.3767/blumea.2018.63.02.10",
"https://doi.org/10.6165/tai.2018.63.25",
"https://doi.org/10.5735/085.055.0121",
"https://doi.org/10.3372/wi.48.48107",
"https://doi.org/10.1590/0102-33062017abb0357",
"https://doi.org/10.11646/phytotaxa.361.1.3",
"https://doi.org/10.11646/phytotaxa.357.2.4",
"https://doi.org/10.11646/phytotaxa.361.1.4",
"https://doi.org/10.11646/phytotaxa.365.2.1",
"https://doi.org/10.11646/phytotaxa.371.1.4",
"https://doi.org/10.11646/phytotaxa.351.2.6",
"https://doi.org/10.1007/s10265-018-1032-y",
"https://doi.org/10.1640/0002-8444-108.3.65",
"https://doi.org/10.11646/phytotaxa.358.1.1",
"https://doi.org/10.11646/phytotaxa.374.4.1",
"https://doi.org/10.11646/phytotaxa.356.2.8",
"https://doi.org/10.11646/phytotaxa.351.2.8",
"https://doi.org/10.22497/arnaldoa.251.25103",
"https://doi.org/10.11646/phytotaxa.352.1.1",
"https://doi.org/10.11646/phytotaxa.371.2.5",
"https://doi.org/10.11646/phytotaxa.347.4.3",
"https://doi.org/10.11646/phytotaxa.363.1.1",
"https://doi.org/10.11646/phytotaxa.362.2.10",
"https://doi.org/10.11646/phytotaxa.361.2.2",
"https://doi.org/10.11646/phytotaxa.348.2.2",
"https://doi.org/10.1111/njb.01893",
"https://doi.org/10.3897/phytokeys.113.28242",
"https://doi.org/10.11646/phytotaxa.374.1.9",
"https://doi.org/10.23855/preslia.2018.105",
"https://doi.org/10.1111/njb.01977",
"https://doi.org/10.1590/2175-7860201869224",
"https://doi.org/10.11646/phytotaxa.349.1.1",
"https://doi.org/10.11646/phytotaxa.360.3.2",
"https://doi.org/10.15553/c2018v731a9",
"https://doi.org/10.11646/phytotaxa.349.3.2",
"https://doi.org/10.1600/036364418x696888",
"https://doi.org/10.11646/phytotaxa.382.2.5",
"https://doi.org/10.1600/036364418x697049",
"https://doi.org/10.11646/phytotaxa.376.2.4",
"https://doi.org/10.11646/phytotaxa.365.3.3",
"https://doi.org/10.1111/njb.01975",
"https://doi.org/10.11646/phytotaxa.348.2.7",
"https://doi.org/10.3372/wi.48.48203",
"https://doi.org/10.11646/phytotaxa.379.1.9",
"https://doi.org/10.11646/phytotaxa.360.3.9",
"https://doi.org/10.3372/wi.48.48204",
"https://doi.org/10.11646/phytotaxa.357.3.8",
"https://doi.org/10.11646/phytotaxa.357.3.3",
"https://doi.org/10.12705/671.10",
"https://doi.org/10.11646/phytotaxa.358.2.2",
"https://doi.org/10.11646/phytotaxa.364.2.6",
"https://doi.org/10.11646/phytotaxa.357.4.4",
"https://doi.org/10.15407/ukrbotj75.05.436",
"https://doi.org/10.12705/671.7",
"https://doi.org/10.11646/phytotaxa.347.3.2",
"https://doi.org/10.11646/phytotaxa.375.1.3",
"https://doi.org/10.11646/phytotaxa.350.2.5",
"https://doi.org/10.3417/2018278",
"https://doi.org/10.3897/phytokeys.114.29914",
"https://doi.org/10.1111/njb.01838",
"https://doi.org/10.11646/phytotaxa.376.5.3",
"https://doi.org/10.11646/phytotaxa.373.3.3",
"https://doi.org/10.11646/phytotaxa.375.2.2",
"https://doi.org/10.1111/njb.02069",
"https://doi.org/10.3897/phytokeys.110.28890",
"https://doi.org/10.11646/phytotaxa.372.4.7",
"https://doi.org/10.11646/phytotaxa.382.2.10",
"https://doi.org/10.12705/672.3",
"https://doi.org/10.11646/phytotaxa.381.1.15",
"https://doi.org/10.1017/s096042861800001x",
"https://doi.org/10.11646/phytotaxa.381.1.9",
"https://doi.org/10.11646/phytotaxa.381.1.12",
"https://doi.org/10.26492/gbs70(1).2018-15",
"https://doi.org/10.3417/2018056",
"https://doi.org/10.1017/s0960428618000136",
"https://doi.org/10.11646/phytotaxa.381.1.5",
"https://doi.org/10.11646/phytotaxa.347.3.1",
"https://doi.org/10.11646/phytotaxa.381.1.14",
"https://doi.org/10.6165/tai.2018.63.188",
"https://doi.org/10.11646/phytotaxa.381.1.7",
"https://doi.org/10.11646/phytotaxa.381.1.11",
"https://doi.org/10.11646/phytotaxa.381.1.10",
"https://doi.org/10.1017/s0960428618000033",
"https://doi.org/10.1017/s0960428618000021",
"https://doi.org/10.3897/phytokeys.103.25392",
"https://doi.org/10.1017/s0960428618000070",
"https://doi.org/10.11646/phytotaxa.381.1.4",
"https://doi.org/10.11646/phytotaxa.349.2.12",
"https://doi.org/10.11646/phytotaxa.381.1.8",
"https://doi.org/10.3897/phytokeys.110.25846",
"https://doi.org/10.6165/tai.2018.63.235",
"https://doi.org/10.1590/2175-7860201869313",
"https://doi.org/10.11646/phytotaxa.360.1.10",
"https://doi.org/10.3417/2018106",
"https://doi.org/10.11646/phytotaxa.367.3.7",
"https://doi.org/10.3100/hpib.v23iss1.2018.n3",
"https://doi.org/10.11646/phytotaxa.351.3.6",
"https://doi.org/10.5735/085.055.0411",
"https://doi.org/10.1071/sb17045",
"https://doi.org/10.6165/tai.2018.63.366",
"https://doi.org/10.11646/phytotaxa.356.2.2",
"https://doi.org/10.1111/njb.01770",
"https://doi.org/10.1002/fedr.201700020",
"https://doi.org/10.11646/phytotaxa.367.3.8",
"https://doi.org/10.11646/phytotaxa.360.3.7",
"https://doi.org/10.3897/phytokeys.98.24296",
"https://doi.org/10.11646/phytotaxa.383.1.7",
"https://doi.org/10.1093/botlinnean/boy030",
"https://doi.org/10.11646/phytotaxa.379.3.3",
"https://doi.org/10.12705/672.4",
"https://doi.org/10.11646/phytotaxa.371.3.4",
"https://doi.org/10.3897/phytokeys.109.27049",
"https://doi.org/10.11646/phytotaxa.357.2.8",
"https://doi.org/10.11646/phytotaxa.360.2.3",
"https://doi.org/10.14258/turczaninowia.21.1.18",
"https://doi.org/10.11646/phytotaxa.351.1.1",
"https://doi.org/10.1093/botlinnean/boy056",
"https://doi.org/10.11646/phytotaxa.379.1.3",
"https://doi.org/10.1600/036364418x697085",
"https://doi.org/10.11646/phytotaxa.362.1.8",
"https://doi.org/10.5091/plecevo.2018.1392",
"https://doi.org/10.11646/phytotaxa.351.3.3",
"https://doi.org/10.11646/phytotaxa.360.1.4",
"https://doi.org/10.11646/phytotaxa.379.2.7",
"https://doi.org/10.7717/peerj.4828",
"https://doi.org/10.1007/s00606-018-1504-5",
"https://doi.org/10.3767/blumea.2018.63.02.08",
"https://doi.org/10.5091/plecevo.2018.1387",
"https://doi.org/10.1371/journal.pone.0203443",
"https://doi.org/10.1007/s12225-018-9767-0",
"https://doi.org/10.11646/phytotaxa.360.2.10",
"https://doi.org/10.11646/phytotaxa.369.2.6",
"https://doi.org/10.3897/phytokeys.113.29103",
"https://doi.org/10.2985/015.090.0206",
"https://doi.org/10.2985/015.090.0204",
"https://doi.org/10.11646/phytotaxa.369.4.2",
"https://doi.org/10.2985/015.090.0210",
"https://doi.org/10.11646/phytotaxa.369.3.4",
"https://doi.org/10.3897/phytokeys.111.26856",
"https://doi.org/10.11646/phytotaxa.361.1.10",
"https://doi.org/10.11646/phytotaxa.385.1.6",
"https://doi.org/10.11646/phytotaxa.362.3.2",
"https://doi.org/10.1111/njb.01940",
"https://doi.org/10.1007/s00606-018-1490-7",
"https://doi.org/10.11646/phytotaxa.376.2.5",
"https://doi.org/10.11646/phytotaxa.378.1.1",
"https://doi.org/10.1080/23818107.2018.1452792",
"https://doi.org/10.1111/njb.01758",
"https://doi.org/10.1080/00837792.2018.1470708",
"https://doi.org/10.12705/671.6",
"https://doi.org/10.18016/ksudobil.347445",
"https://doi.org/10.11646/phytotaxa.347.4.2",
"https://doi.org/10.11646/phytotaxa.360.3.3",
"https://doi.org/10.11646/phytotaxa.350.1.11",
"https://doi.org/10.3372/wi.48.48110",
"https://doi.org/10.3897/phytokeys.109.24609",
"https://doi.org/10.11646/phytotaxa.383.1.2",
"https://doi.org/10.5735/085.055.0408",
"https://doi.org/10.11646/phytotaxa.348.3.6",
"https://doi.org/10.11646/phytotaxa.369.3.6",
"https://doi.org/10.11646/phytotaxa.382.1.5",
"https://doi.org/10.3897/phytokeys.109.28956",
"https://doi.org/10.11646/phytotaxa.350.3.5",
"https://doi.org/10.3159/torrey-d-17-00048.1",
"https://doi.org/10.3989/ajbm.xxxx",
"https://doi.org/10.1600/036364418x696941",
"https://doi.org/10.3417/2018064",
"https://doi.org/10.11646/phytotaxa.373.1.1",
"https://doi.org/10.11646/phytotaxa.374.2.2",
"https://doi.org/10.1111/njb.02205",
"https://doi.org/10.3417/2018039",
"https://doi.org/10.26679/pleione.12.2.2018.322-331",
"https://doi.org/10.1080/00837792.2018.1540743",
"https://doi.org/10.5091/plecevo.2018.1503",
"https://doi.org/10.3897/phytokeys.101.25057",
"https://doi.org/10.1002/fedr.201800002",
"https://doi.org/10.11646/phytotaxa.356.3.5",
"https://doi.org/10.11646/phytotaxa.364.2.8",
"https://doi.org/10.11646/phytotaxa.374.2.12",
"https://doi.org/10.1111/njb.01833",
"https://doi.org/10.11646/phytotaxa.349.2.11",
"https://doi.org/10.6165/tai.2018.63.393",
"https://doi.org/10.11646/phytotaxa.360.1.9",
"https://doi.org/10.3767/blumea.2018.63.02.05",
"https://doi.org/10.11646/phytotaxa.369.1.8",
"https://doi.org/10.11646/phytotaxa.356.1.3",
"https://doi.org/10.11646/phytotaxa.360.3.11",
"https://doi.org/10.11646/phytotaxa.374.3.10",
"https://doi.org/10.1007/s12225-018-9756-3",
"https://doi.org/10.6165/tai.2018.63.37",
"https://doi.org/10.1007/s12225-018-9754-5",
"https://doi.org/10.11646/phytotaxa.361.1.9",
"https://doi.org/10.11646/phytotaxa.356.1.4",
"https://doi.org/10.3897/phytokeys.98.23639",
"https://doi.org/10.11646/phytotaxa.348.1.7",
"https://doi.org/10.11646/phytotaxa.368.1.1",
"https://doi.org/10.1111/njb.02056",
"https://doi.org/10.12705/671.4",
"https://doi.org/10.13140/rg.2.2.17303.68008",
"https://doi.org/10.3417/2017049",
"https://doi.org/10.3119/17-08",
"https://doi.org/10.1093/botlinnean/boy040",
"https://doi.org/10.11646/phytotaxa.372.3.3",
"https://doi.org/10.6165/tai.2018.63.1",
"https://doi.org/10.1111/njb.01900",
"https://doi.org/10.11646/phytotaxa.356.2.3",
"https://doi.org/10.1016/j.ympev.2018.04.016",
"https://doi.org/10.11646/phytotaxa.362.3.5",
"https://doi.org/10.11646/phytotaxa.383.1.8",
"https://doi.org/10.1071/sb17046",
"https://doi.org/10.1371/journal.pone.0203478",
"https://doi.org/10.1007/s12225-018-9752-7",
"https://doi.org/10.1080/00837792.2018.1509932",
"https://doi.org/10.3767/blumea.2018.63.02.09",
"https://doi.org/10.5091/plecevo.2018.1380",
"https://doi.org/10.7751/telopea12440",
"https://doi.org/10.11646/phytotaxa.367.2.7",
"https://doi.org/10.1002/fedr.201700004",
"https://doi.org/10.11646/phytotaxa.385.1.7",
"https://doi.org/10.3897/phytokeys.98.23903",
"https://doi.org/10.11646/phytotaxa.376.6.2",
"https://doi.org/10.3767/blumea.2018.63.01.05",
"https://doi.org/10.11646/phytotaxa.346.3.2",
"https://doi.org/10.3372/wi.48.48106",
"https://doi.org/10.11646/phytotaxa.376.3.1",
"https://doi.org/10.18942/apg.201720",
"https://doi.org/10.1111/jse.12305",
"https://doi.org/10.11646/phytotaxa.365.2.3",
"https://doi.org/10.11646/phytotaxa.374.2.9",
"https://doi.org/10.11646/phytotaxa.357.3.9",
"https://doi.org/10.3417/2018209",
"https://doi.org/10.15553/c2018v731a8",
"https://doi.org/10.20531/tfb.2018.46.1.05",
"https://doi.org/10.11646/phytotaxa.346.2.6",
"https://doi.org/10.11646/phytotaxa.356.4.2",
"https://doi.org/10.11646/phytotaxa.360.3.5",
"https://doi.org/10.11646/phytotaxa.367.2.3",
"https://doi.org/10.11646/phytotaxa.367.1.2",
"https://doi.org/10.5091/plecevo.2018.1421",
"https://doi.org/10.11646/phytotaxa.347.2.5",
"https://doi.org/10.11646/phytotaxa.347.4.4",
"https://doi.org/10.1590/2175-7860201869319",
"https://doi.org/10.11646/phytotaxa.383.2.9",
"https://doi.org/10.11646/phytotaxa.371.4.4",
"https://doi.org/10.3897/phytokeys.108.27284",
"https://doi.org/10.11646/phytotaxa.356.2.5",
"https://doi.org/10.11646/phytotaxa.375.3.2",
"https://doi.org/10.11646/phytotaxa.360.1.6",
"https://doi.org/10.11646/phytotaxa.365.3.4",
"https://doi.org/10.1600/036364418x697111",
"https://doi.org/10.1080/00837792.2018.1505379",
"https://doi.org/10.3417/2018131",
"https://doi.org/10.3159/torrey-d-17-0007.1",
"https://doi.org/10.5091/plecevo.2018.1445",
"https://doi.org/10.11646/phytotaxa.350.3.1",
"https://doi.org/10.1111/njb.01413",
"https://doi.org/10.15553/c2018v731a3",
"https://doi.org/10.3989/ajbm.2501",
"https://doi.org/10.3767/blumea.2018.63.02.14",
"https://doi.org/10.11646/phytotaxa.351.1.11",
"https://doi.org/10.1111/njb.01615",
"https://doi.org/10.1590/2175-7860201869244",
"https://doi.org/10.3417/d1700007",
"https://doi.org/10.3897/phytokeys.103.26307",
"https://doi.org/10.1590/2175-7860201869215",
"https://doi.org/10.1186/s40529-018-0225-y",
"https://doi.org/10.1080/00837792.2018.1452370",
"https://doi.org/10.3767/blumea.2018.62.03.04",
"https://doi.org/10.11646/phytotaxa.383.3.4",
"https://doi.org/10.1007/s12225-018-9772-3",
"https://doi.org/10.15553/c2018v731a4",
"https://doi.org/10.3417/2017043",
"https://doi.org/10.11646/phytotaxa.362.2.9",
"https://doi.org/10.11646/phytotaxa.385.1.5",
"https://doi.org/10.1111/njb.01551",
"https://doi.org/10.1017/s0960428618000045",
"https://doi.org/10.3897/phytokeys.99.25265",
"https://doi.org/10.6165/tai.2018.63.397",
"https://doi.org/10.6165/tai.2018.63.232",
"https://doi.org/10.1017/s0960428618000124",
"https://doi.org/10.1111/njb.01514",
"https://doi.org/10.1111/njb.01764",
"https://doi.org/10.1017/s0960428618000148",
"https://doi.org/10.1017/s0960428618000161",
"https://doi.org/10.1111/njb.01664",
"https://doi.org/10.5735/085.055.0106",
"https://doi.org/10.7717/peerj.4946",
"https://doi.org/10.6165/tai.2018.63.54",
"https://doi.org/10.6165/tai.2018.63.305",
"https://doi.org/10.1111/njb.01992",
"https://doi.org/10.3417/2017058",
"https://doi.org/10.3767/blumea.2018.63.02.04",
"https://doi.org/10.7751/telopea11093",
"https://doi.org/10.11646/phytotaxa.385.2.5",
"https://doi.org/10.11646/phytotaxa.364.3.3",
"https://doi.org/10.11646/phytotaxa.361.3.5",
"https://doi.org/10.11646/phytotaxa.375.1.1",
"https://doi.org/10.11646/phytotaxa.357.1.9",
"https://doi.org/10.1111/njb.01744",
"https://doi.org/10.1111/njb.02157",
"https://doi.org/10.12705/673.4",
"https://doi.org/10.11646/phytotaxa.383.3.5",
"https://doi.org/10.11646/phytotaxa.376.5.1",
"https://doi.org/10.11646/phytotaxa.361.2.5",
"https://doi.org/10.11646/phytotaxa.364.2.9",
"https://doi.org/10.11646/phytotaxa.362.1.5",
"https://doi.org/10.11646/phytotaxa.376.2.3",
"https://doi.org/10.12705/672.7",
"https://doi.org/10.11646/phytotaxa.356.1.6",
"https://doi.org/10.11646/phytotaxa.349.2.6",
"https://doi.org/10.5735/085.055.0410",
"https://doi.org/10.1007/s12225-018-9781-2",
"https://doi.org/10.1111/njb.01869",
"https://doi.org/10.3906/bot-1704-35",
"https://doi.org/10.11646/phytotaxa.371.3.1",
"https://doi.org/10.1111/njb.01639",
"https://doi.org/10.12705/674.6",
"https://doi.org/10.1093/aob",);

/*
$uris=array('https://doi.org/10.1111/j.1759-6831.2010.00113.x');

$uris=array('https://doi.org/10.3897/phytokeys.94.21329');

$uris=array(
//'https://doi.org/10.1007/s12225-016-9614-0',
//'https://doi.org/10.12705/672.5',

'https://doi.org/10.6165/tai.2011.56(1).37',
//'https://doi.org/10.6165/tai.2009.54(1).82',

);

$uris=array(
//'https://doi.org/10.1017/s0960428602000082',
'https://doi.org/10.1080/00837792.2017.1402476',
);

// Silvio Fici
$uris=array(
'https://doi.org/10.1007/s12225-011-9284-x',
'https://doi.org/10.1007/s12225-012-9390-4',
'https://doi.org/10.1080/00837792.2003.10670747',
'https://doi.org/10.1080/00837792.2016.1232928',
'https://doi.org/10.1080/00837792.2017.1402476',
'https://doi.org/10.1080/00837792.2018.1470708',
'https://doi.org/10.11646/phytotaxa.174.1.1',
'https://doi.org/10.11646/phytotaxa.203.1.2',

);

$uris=array(
'https://doi.org/10.1071/sb13042',
'https://doi.org/10.1111/njb.01066',
'https://doi.org/10.1600/036364415x689140',
'https://doi.org/10.2985/026.018.0102',
'https://doi.org/10.3897/phytokeys.33.6094',
'https://doi.org/10.5091/plecevo.2011.470',
'https://doi.org/10.5735/085.052.0321',
'https://doi.org/10.11646/phytotaxa.220.1.6',
'https://doi.org/10.11646/phytotaxa.221.2.11',
'https://doi.org/10.12705/643.4',
'https://doi.org/10.12705/644.6',

);
*/
$uris=array(
'https://doi.org/10.1007/s12225-008-9090-2',
'https://doi.org/10.1007/s12225-018-9760-7',
'https://doi.org/10.1111/boj.12246',
'https://doi.org/10.1600/036364412x656581',
'https://doi.org/10.1600/036364410791638397',
'https://doi.org/10.2307/3391775',
'https://doi.org/10.2307/3391775',
'https://doi.org/10.2307/3393570',
'https://doi.org/10.2307/3393570',
'https://doi.org/10.2307/4110993',
'https://doi.org/10.3417/2007057',
'https://doi.org/10.3897/phytokeys.7.1855',
'https://doi.org/10.3897/phytokeys.38.7055',
'https://doi.org/10.3897/phytokeys.47.9076',
'https://doi.org/10.3897/phytokeys.61.7258',
'https://doi.org/10.3897/phytokeys.61.7904',
'https://doi.org/10.3897/phytokeys.111.28595',
'https://doi.org/10.5091/plecevo.2017.1326',
'https://doi.org/10.5091/plecevo.2018.1380',
'https://doi.org/10.11646/phytotaxa.147.2.3',
'https://www.jstor.org/stable/23210319',
'https://www.jstor.org/stable/23499402',
'https://www.jstor.org/stable/24621054',
'https://www.jstor.org/stable/41968561',
);

$uris=array(
'https://doi.org/10.1093/botlinnean/bow013',
);

$matches = array();

$ipni_matches = array();


foreach ($uris as $uri)
{

	$sparql = 'PREFIX tn: <http://rs.tdwg.org/ontology/voc/TaxonName#>
PREFIX tm: <http://rs.tdwg.org/ontology/voc/Team#>
PREFIX schema: <http://schema.org/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX tcom: <http://rs.tdwg.org/ontology/voc/Common#>

SELECT * WHERE
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


  	FILTER(?pub_roleName = ?ipni_roleName)
  	FILTER (?ipni_role != "Basionym Author")
}';

	$url = 'http://localhost/~rpage/lois-kg/www/query.php?query=' . urlencode($sparql);

	$json = get($url);

	$obj = json_decode($json);

	//print_r($obj);
	
	if (isset($obj->results->bindings))
	{
		foreach ($obj->results->bindings as $binding)
		{
			$parameters = array();

			foreach ($binding as $k => $v)
			{
				$parameters[$k] = $v->value;
			}
		
			if (isset($parameters['pub_name']) && ($parameters['ipni_member_name'] != ''))
			{
				echo $parameters['pub_name'] . ' ' .  $parameters['ipni_member_name'] . "\n";
				
				$r = compare($parameters['pub_name'], $parameters['ipni_member_name']);
				print_r($r);
				
				if ($r->p >= 0.95)
				{
					if (!isset($matches[ $parameters['pub_creator'] ]))
					{
						$matches[ $parameters['pub_creator'] ] = new stdclass;
						$matches[ $parameters['pub_creator'] ]->matches = array();
						$matches[ $parameters['pub_creator'] ]->names = array();
					}
					$matches[ $parameters['pub_creator'] ]->matches[] = $parameters['ipni_member'];
					$matches[ $parameters['pub_creator'] ]->names[] = $parameters['pub_name'];

					if (!isset($ipni_matches[ $parameters['ipni_member'] ]))
					{
						$ipni_matches[ $parameters['ipni_member'] ] = new stdclass;
						$ipni_matches[ $parameters['ipni_member'] ]->matches = array();
						$ipni_matches[ $parameters['ipni_member'] ]->names = array();
					}

					$ipni_matches[ $parameters['ipni_member'] ]->matches[] = $parameters['pub_creator'];
					$ipni_matches[ $parameters['ipni_member'] ]->names[] = $parameters['pub_name'];
					$ipni_matches[ $parameters['ipni_member'] ]->names[] = $parameters['ipni_member_name'];
				
				}
			}
		}
	}
	
}

// refine

/*
$n = count($matches)
for ($i = 0; $i < $n; $n++)
{
	
}
*/

foreach ($matches as $k => $v)
{
	$matches[$k]->matches = array_unique($matches[$k]->matches);
	$matches[$k]->names = array_unique($matches[$k]->names);
	
}

print_r($matches);

foreach ($ipni_matches as $k => $v)
{
	$ipni_matches[$k]->matches = array_unique($ipni_matches[$k]->matches);
	$ipni_matches[$k]->names = array_unique($ipni_matches[$k]->names);
	
}

print_r($ipni_matches);

// output matches as RDF

$triples = array();

foreach ($ipni_matches as $k => $v)
{
	foreach ($v->matches as $creator)
	{
		$triples[] = '<' . $creator . '> <http://schema.org/sameAs> <' . $k . '> . ';
	}
}

echo join("\n", $triples) . "\n";


?>
