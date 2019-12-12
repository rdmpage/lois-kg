<?php

// Build a tree for a set of sequences


require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');

// https://gist.github.com/jwage/11193216

// 5-mer
$attributeValues 
	= array(
		array('A','C','G','T'), 
		array('A','C','G','T'), 
		array('A','C','G','T'), 
		array('A','C','G','T'), 
		array('A','C','G','T')
	);		

class Cartesian
{
    public static function build($set)
    {
        if (!$set) {
            return array(array());
        }
        $subset = array_shift($set);
        $cartesianSubset = self::build($set);
        $result = array();
        foreach ($subset as $value) {
            foreach ($cartesianSubset as $p) {
                array_unshift($p, $value);
                $result[] = $p;
            }
        }
        return $result;        
    }
}
//print_r(Cartesian::build($attributeValues));

$tuples = Cartesian::build($attributeValues);

$taxon = 'Terniopsis';
$taxon = 'Cyrtochilum';
$taxon = 'Cladopus';

	
$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/_design/kmers/_view/matK?key=" . urlencode('"' . $taxon . '"'));
	
//echo $resp;

$obj = json_decode($resp);

//$sequences = array();





// intialise
$n = count($obj->rows);
$D = array();
for ($i = 0; $i < $n; $i++)
{
	$D[$i] = array();
	for ($j = 0; $j < $n; $j++)
	{
		$D[$i][$j] = 0;
	}
}

// tuples 
for ($i = 1; $i < $n; $i++)
{
	for ($j = 0; $j < $i; $j++)
	{
		$f = 0;
	
		foreach($tuples as $tuple)
		{
			$k = join('', $tuple);
			//echo $k . "\n";
			
			//print_r($obj->rows[$i]);
			
			$na = $obj->rows[$i]->value->tuples->{$k};
			$nb = $obj->rows[$j]->value->tuples->{$k};
			
			$f += min($na, $nb) / (min($obj->rows[$i]->value->length, $obj->rows[$j]->value->length) - 5 + 1);
		}
		
		$d = (log(0.1 + $f) - log(1.1))/log(0.1);
	
		$D[$i][$j] = $d;
		$D[$j][$i] = $d;
		
	}
}

$labels = array();
for ($i = 0; $i < $n; $i++)
{
	$labels[$i] = "'" . $obj->rows[$i]->value->id . ' ' . $obj->rows[$i]->value->label . "'";
}


echo "#NEXUS\n\n";

echo "BEGIN TAXA;\n";
echo "	DIMENSIONS ntax=$n;\n";
echo "	TAXLABELS ";

for ($i = 0; $i < $n; $i++)
{
	//echo preg_replace('/\.\d+$/', '', $obj->rows[$i]->value->id) . ' '; 
	echo $labels[$i] . ' '; 
}

echo ";\n";
echo "	END;\n\n";

// distances


echo "[k-mer distances]\n";
echo "BEGIN DISTANCES;\n"; 
echo "FORMAT TRIANGLE = LOWER;\n"; 
echo "Matrix \n"; 
for ($i = 0; $i < $n; $i++)
{
//	echo str_pad(preg_replace('/\.\d+$/', '', $obj->rows[$i]->value->id), 30, ' ', STR_PAD_RIGHT);
	echo str_pad($labels[$i], 30, ' ', STR_PAD_RIGHT);

	for ($j = 0; $j <= $i; $j++)
	{
		echo ' ' . number_format($D[$i][$j], 3);
	}
	echo "\n";
}
echo ";\n";
echo "END;\n";

// make a tree
$nexus .= "\n";
echo "[PAUP block]\n";
echo "BEGIN PAUP;\n";
echo "   [root trees at midpoint]\n";
echo "   set rootmethod=midpoint;\n";
echo "   set outroot=monophyl;\n";
echo "   [construct tree using neighbour-joining]\n";
echo "   nj;\n";
echo "END;\n";


/*
	// compute distances
	var D = [];
	for (var i = 0; i < n; i++) {
		D[i] = [];
		for (var j = 0; j < n; j++) {
			D[i][j] = 0;
		}
	}

	
	// tuples
	for (var i = 1; i < n; i++) {
		for (var j = 0; j < i; j++) {
			var d = 0;
			// compare tuples
			for (var k in tuples[i]) {
				d += (tuples[i][k] - tuples[j][k]) * (tuples[i][k] - tuples[j][k]);
			}

			D[i][j] = d;
			D[j][i] = d;
		}
	}

*/
	

// curl http://167.71.255.145:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@cslx.nt'  --progress-bar | tee /dev/null

//$command = "curl " . $config['sparql_endpoint'] . "?context-uri=$namespace -H 'Content-Type: text/rdf+n3' --data-binary '@" . $filename . "' --progress-bar | tee /dev/null";
//echo $command . "\n";

//system($command);

?>

