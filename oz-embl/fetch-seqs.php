<?php

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

$accs=array();

$accs=array(
'AB450034.1',
'AB698132.1',
'AB698131.1',
'AB083105.1',
'AB083103.1',
'AB083099.1',
'AB083098.1',
'AB038194.1',
'AB048827.1',
);

$accs=array(
'AF432970',
'AF350858',
'AF433014.1',
'FJ563874.1',
'FJ968167',
'AF239494',
'AF263675',
'AF350636',
'AF350637',
'AF350638',
'AF350639',
'AF433008',
'AF433012',
'AF433013',
'AF433014',
'AF433015',
'AF433016',
'EF079205',
'EF079206',
'FJ563842',
'FJ563858',
'FJ563865',
'FJ563874',
'FJ563888',
'FJ564718',
'FJ564725',
'FJ564731',
'FJ564742',
'FJ564743',
'FJ564749',
'FJ564756',
'FJ564758',
'FJ564763',
'FJ564766',
'FJ564775',
'FJ564776',
'FJ564780',
'FJ564785',
'FJ564807',
'FJ564815',
'FJ564830',
'FJ564852',
'FJ564856',
'FJ564858',
'FJ564859',
'FJ564880',
'FJ564945',
'FJ564950',
'FJ564967',
'FJ564969',
'FJ564970',
'FJ564971',
'FJ564988',
'FJ564990',
'FJ564991',
'FJ564994',
'FJ564998',
'FJ565001',
'FJ565043',
'FJ565044',
'FJ565089',
'FJ565101',
'FJ565109',
'FJ565144',
'FJ565148',
'FJ565153',
'FJ565154',
'FJ565156',
'FJ654711',
'KU695541',
);

$accs=array(
'AF347991'
);

// Billolivia
$accs=array(
'KU985115.1',
'KU985114.1',
'KU985113.1',
'KU985112.1',
'KU985111.1',
'KU985110.1',
'KU985109.1',
'KU985108.1',
);

$count = 1;

foreach ($accs as $acc)
{
	$url = 'https://www.ebi.ac.uk/ena/data/view/' . $acc . '&display=text';
	
	echo get($url);
	
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		usleep($rand);
	}
	
	
}

?>