<?php

require_once('lcs.php');


$pairs = array(
'Anal. Jard. Bot. Madrid',
'Anal. Jard. Bot.'
);

$pairs = array(
'Anal. Jard. Bot. Madrid',
'Anal. J. Bot. Madrid'
);

/*
$pairs = array(
'Anal. Jard. Bot. Madrid',
'Anales Jard. Bot. Madrid'
);
*/


$pairs = array(
'Anales Jardin botanico de Madrid',
'Anal. J. Bot. Madrid'
);

/*
Annu Conserv Jard Bot Geneve	1
Ann. Cons. et Jard. bot. Genève	1
Ann. Conserv. et Jard. Bot. de Genéve	1
Annuaire du Conserv. Jard. Bot. Gèneve	2	
	Annuaire du Conservatoire et du Jardin Botaniques de Geneve	1
	*/
	
$pairs = array(
'Annu Conserv Jard Bot Geneve',
'Annuaire du Conservatoire et du Jardin Botaniques de Geneve'
);	



// clean

$n = count($pairs);
for ($i = 0; $i < $n; $i++)
{
	$str = $pairs[$i];
	
	$str = str_replace('.', '', $str);
	
	$str = strtolower($str);
	
	$str = preg_replace('/\s(et|du|of|the)/u', '', $str);
	
	$pairs[$i] = $str;
}


/*
Anal. J. Bot. Madrid	1	
	Anal. Jard. Bot.	1	
	Anal. Jard. Bot. Madrid	4
*/

$lcs = new LongestCommonSequence($pairs[0], $pairs[1]);
$d = $lcs->score();

echo $lcs->display();

$lcs->show_alignment();


?>
