<?php

// import from RIS

require_once(dirname(__FILE__) . '/ris.php');


//--------------------------------------------------------------------------------------------------
function ris_import($reference)
{
	//print_r($reference);
	
	if (isset($reference->journal) && isset($reference->pages))
	{
		$reference->journal->pages .= $reference->pages; 
		unset($reference->pages);
	}

	// post processing
	if (preg_match('/s(?<series>\d+)-(?<volume>\d+)/', $reference->journal->volume, $m))
	{
		$reference->journal->series = $m['series'];
		$reference->journal->volume = $m['volume'];
	}
	
	$guid = '';
	
	$pdf = '';
	
	$keys = array();
	$values= array();
	
	$keys[] = 'title';
	$values[] = '"' . addcslashes(strip_tags($reference->title), '"') . '"';

	// journal
		
	$journal = $reference->journal->name;
	if (!isset($reference->journal->series ))
	{
		if (preg_match('/^(?<journal>.*),\s+[S|s]eries\s+(?<series>\d+)$/', $journal, $m))
		{
			$journal = $m['journal'];
			$reference->journal->series = $m['series'];
		}
	}
	
	// handle some messy journal names
	if ($journal == 'Berichte Der Schweizerischen Botanischen Gesellschaft = Bulletin de la Société Botanique Suisse')
	{
		$journal = 'Berichte Der Schweizerischen Botanischen Gesellschaft';
	}
		
	$keys[] = 'journal';
	$values[] = '"' . addcslashes($journal, '"') . '"';
	
	if (isset($reference->journal->series))
	{
		$keys[] = 'series';
		$values[] = '"' . addcslashes($reference->journal->series, '"') . '"';	
	}
	
	if (isset($reference->journal->volume))
	{
		$keys[] = 'volume';
		$values[] = '"' . addcslashes($reference->journal->volume, '"') . '"';
	}
	
	if (isset($reference->journal->issue))
	{
		$keys[] = 'issue';
		$values[] = '"' . addcslashes($reference->journal->issue, '"') . '"';	
	}	
	
	if (isset($reference->journal->pages))
	{
		if (preg_match('/(?<spage>(\d+|\w+))--(?<epage>(\d+|\w+))/', $reference->journal->pages, $m))
		{
			$keys[] = 'spage';
			$values[] = '"' . addcslashes($m['spage'], '"') . '"';
			$keys[] = 'epage';
			$values[] = '"' . addcslashes($m['epage'], '"') . '"';	
		}
		else
		{
			$keys[] = 'spage';
			$values[] = '"' . addcslashes($reference->journal->pages, '"') . '"';
		}
	}
	
	if (isset($reference->journal->identifier))
	{
		$issn = '';
		foreach ($reference->journal->identifier as $identifier)
		{
			switch ($identifier->type)
			{
				case 'issn':
					if ($issn == '')
					{
						$keys[] = 'issn';
						$values[] = '"' . $identifier->id . '"';
						
						$issn = $identifier->id;
					}
					else
					{
						$keys[] = 'eissn';
						$values[] = '"' . $identifier->id . '"';
					}					
					break;
								
				default:
					break;
			}
		}
	}

	$keys[] = 'year';
	$values[] = '"' . addcslashes($reference->year, '"') . '"';
	
	if (isset($reference->date))
	{
		$keys[] = 'date';
		$values[] = '"' . $reference->date . '"';
	}
	
	if (isset($reference->link))
	{
		foreach ($reference->link as $link)
		{
			if ($link->anchor == 'LINK')
			{
				$guid = $link->url;
				
				
				$add = true;
			
				if (preg_match('/http:\/\/dx.doi.org\//', $link->url))
				{
					// ignore DOIs
					$add = false;
				}
				
				if (preg_match('/https?:\/\/hdl.handle.net\//', $link->url))
				{
					// ignore handles
					$add = false;
				}

				if (preg_match('/https?:\/\/www.jstor.org\//', $link->url))
				{
					// ignore jstor
					$add = false;
					$add = true;
				}
				
				
				if ($add)
				{		
					if (1)	
					{
					$keys[] = 'url';
					$values[] = '"' . $link->url . '"';
					}
					
					if (0)
					{			
						if (preg_match('/http:\/\/www.jstor.org\/stable\/(?<id>\d+)$/', $link->url, $m))
						{
							$guid = '10.2307/' . $m['id'];
						}
					}
				}			
			}
			if ($link->anchor == 'PDF')
			{
				$keys[] = 'pdf';
			
				$pdf = $link->url ;
				
				if ($guid == '')
				{
					$guid = $link->url;
				}
			
				if (preg_match('/wenjianming=(?<pdf>.*)&/Uu', $pdf, $m))
				{
					$pdf = 'http://www.plantsystematics.com/qikan/manage/wenzhang/' . $m['pdf'] . '.pdf';
				}
			
				$values[] = '"' . $pdf . '"';
			}
		}
	}
		
	if (isset($reference->identifier))
	{
		foreach ($reference->identifier as $identifier)
		{
			switch ($identifier->type)
			{
				case 'doi':
					$guid = $identifier->id;
				
					$keys[] = 'doi';
					$values[] = '"' . $identifier->id . '"';
					break;
				
				case 'handle':
					$guid = $identifier->id;
				
					$keys[] = 'handle';
					$values[] = '"' . $identifier->id . '"';
					break;
					
				case 'isbn':
					if (strlen($identifier->id) == 13)
					{
						$keys[] = 'isbn13';
					}
					else
					{
						$keys[] = 'isbn10';
					}
					$values[] = '"' . $identifier->id . '"';
					break;
					

				case 'jstor':
					$keys[] = 'jstor';
					$values[] = '"' . $identifier->id . '"';
					break;

				case 'wos':
					if ($guid == '')
					{
						$guid = $identifier->id;
					}
					$keys[] = 'wos';
					$values[] = '"' . $identifier->id . '"';
					break;
				
				default:
					break;
			}
		}
	}	
	
	
	
	//print_r($reference);
		
	$authors =  array();
	if (isset($reference->author))
	{
		foreach ($reference->author as $author)
		{
			if (isset($author->lastname))
			{
				$authors[] = addcslashes($author->lastname . ', ' . $author->firstname, '"');
			}
			else
			{
				$authors[] = addcslashes($author->name, '"');
			}
		}
		if (count($authors) > 0)
		{
			$keys[] = 'authors';
			$values[] = '"' . join(';', $authors) . '"';
		}
	}
	
	if (isset($reference->abstract))
	{
		$keys[] = 'abstract';
		$values[] = '"' . addcslashes($reference->abstract, '"') . '"';	
	}
	
	
	if (isset($reference->publisher_id))
	{
		$keys[] = 'publisher_id';
		$values[] = '"' . addcslashes($reference->publisher_id, '"') . '"';			
	}

	
	if ($guid == '')
	{	
		$guid = md5(join('', $values));
	}
	
	
	if ($issn == '0368-0177')
	{
		$guid = $issn . '-' . basename($guid);
	}

	// Geodiversitas has obscenely long URLs
	if (($issn == '1280-9659') && (strlen($guid) >= 255))
	{
		$guid = md5(join('', $values));
	}
	
	// Zoosystema has obscenely long URLs	
	if (($issn == '1280-9551') && (strlen($guid) >= 255))
	{
		$guid = md5(join('', $values));
	}

	// Adansonia has obscenely long URLs	
	if (($issn == '1280-8571') && (strlen($guid) >= 255))
	{
		$guid = md5(join('', $values));
	}
	
	if (strlen($guid) >= 255)
	{
		$guid = md5(join('', $values));
	}
	
	
	
	$keys[] = 'guid';
	$values[] = '"' . $guid . '"';
	
	// populate from scratch (default)
	if (1)
	{
		$sql = 'REPLACE INTO loiskg (' . join(',', $keys) . ') values('
			. join(',', $values) . ');';
		echo $sql . "\n";
	}


	
}




//--------------------------------------------------------------------------------------------------
$filename = '';
if ($argc < 2)
{
	echo "Usage: import_ris.php <RIS file> \n";
	exit(1);
}
else
{
	$filename = $argv[1];
}

$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_ris_file($filename, 'ris_import');


?>