<?php

// compare people names

require_once(dirname(__FILE__) . '/lcs.php');

//----------------------------------------------------------------------------------------
// https://stackoverflow.com/a/2759179
function Unaccent($string)
{
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
}

//----------------------------------------------------------------------------------------
function clean ($text)
{
	$text = preg_replace('/([A-Z])\.([A-Z])/u', '$1. $2', $text);
	
	$text = preg_replace('/\./u', ' ', $text);
	$text = preg_replace('/-/u', ' ', $text);
	$text = preg_replace('/,/u', '', $text);

	$text = preg_replace('/\s\s+/u', ' ', $text);
	
	//echo $text . "\n";

	$text = mb_convert_case($text, MB_CASE_TITLE);
	
	//echo $text . "\n";

	// Convert accented characters
	$text = preg_replace('/Æ/u', 'A', $text);


	$text = Unaccent($text);
	/*
	$text = mb_strtr($text, 
		"ÀÁÂÃÄÅàáâãäåĀāĂăĄąÇçĆćĈĉĊċČčÐðĎďĐđÈÉÊËèéêëĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħÌÍÎÏìíîïĨĩĪīĬĭĮįİıĴĵĶķĸĹĺĻļĽľĿŀŁłÑñŃńŅņŇňŉŊŋÒÓÔÕÖØòóôõöøŌōŎŏŐőŔŕŖŗŘřŚśŜŝŞşŠšſŢţŤťŦŧÙÚÛÜùúûüŨũŪūŬŭŮůŰűŲųŴŵÝýÿŶŷŸŹźŻżŽž",
		"AAAAAAaaaaaaaaaaaaccccccccccddddddeeeeeeeeeeeeeeeeeegggggggghhhhiiiiiiiiiiiiiiiiiijjkkkllllllllllnnnnnnnnnnnoooooooooooooooooorrrrrrsssssssssttttttuuuuuuuuuuuuuuuuuuuuwwyyyyyyzzzzzz"
		);
	*/	

	//echo $text . "|\n";

	return $text;
}


//----------------------------------------------------------------------------------------
function compare($name1, $name2, $debug = false)
{
	
	$result = new stdclass;
	$result->names=array();
	$result->names[] = $name1;
	$result->names[] = $name2;
	
	$result->str1 = $name1;
	$result->str2 = $name2;
	
	$result->str1 = clean($result->str1);
	$result->str2 = clean($result->str2);

	$lcs = new LongestCommonSequence($result->str1, $result->str2);
	
	$result->d = $lcs->score();
	
	$result->p = $result->d / min(strlen($result->str1), strlen($result->str2));
	
	$lcs->get_alignment();
	
	
	
	if ($debug)
	{
		$result->alignment = '';
		$result->alignment .= "\n";
		$result->alignment .= $lcs->left . "\n";
		$result->alignment .= $lcs->bars . "\n";
		$result->alignment .= $lcs->right . "\n";
		$result->alignment .= "$result->d characters match\n";
		$result->alignment .= "$result->p of shortest string matches\n";
	}	
	
	// check whether difference is ordering of names (e.g., Chinese names)
	if ($result->p < 0.9)
	{
		$parts1 = explode(' ', $result->str1);
		$parts2 = explode(' ', $result->str2);
		
		if ($debug)
		{
			print_r($parts1);
			print_r($parts2);
		}
		
		// Is last name swapped?
		if (
			($parts1[0] == $parts2[count($parts2) - 1])
			|| ($parts1[count($parts1) - 1] == $parts2[0])
			)
		{
		
			asort($parts1);
			asort($parts2);
		
			$result->str1 = join(' ', $parts1);
			$result->str2 = join(' ', $parts2);
		
			$lcs = new LongestCommonSequence($result->str1, $result->str2);
	
			$result->d = $lcs->score();
	
			$result->p = $result->d / min(strlen($result->str1), strlen($result->str2));

			//$result->p = (2 * $result->d) /(strlen($result->str1) + strlen($result->str2));
	
			$lcs->get_alignment();		
		
			if ($debug)
			{
				$result->alignment = '';
				$result->alignment .= "\n";
				$result->alignment .= $lcs->left . "\n";
				$result->alignment .= $lcs->bars . "\n";
				$result->alignment .= $lcs->right . "\n";
				$result->alignment .= "$result->d characters match\n";
				$result->alignment .= "$result->p of shortest string matches\n";
			}	
		}		
	}
	
	return $result;
}


//----------------------------------------------------------------------------------------

if (0)
{
	$tests = array(
		array('Kaj Vollesen', 'Kaj Børge Vollesen'),
		array('Ko Wanchang', 'Wan Chang Ko'),
	
		// case difference
		array('JOSÉ ESTEBAN JIMÉNEZ', 'José Esteban Jiménez'),

		array('FRED R. BARRIE', 'Fred Rogers Barrie'),
		
		array('WANG Wen-Tsai', 'Wen Tsai Wang'),

		array('Henrik Æ. Pedersen', 'Henrik Aerenlund Pedersen'),
	

	);
	
	$tests = array(
	array('De-Zhu Li', 'Dezhu Li'),
	array('De-Zhu Li', 'D. Z. Li'),
	
	
	
	);
	
	$tests = array(
	array('Nascimento, J.G.A.do', 'J.G.A. do Nascimento'),
	array('De-Zhu Li', 'D. Z. Li'),
	
	);
	

	$debug = true;
	

	foreach ($tests as $k => $name)
	{
		$r = compare($name[0], $name[1]);
		
		print_r($r);
	


	}
}

?>
