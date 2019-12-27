<?php

// Load the XML source
$xml = new DOMDocument;
$xml->load('xsl/603875.xml');

//$xml->load('xsl/10.1177_1940082917739774.xml');

//$xml->load('xsl/phytokeys-1426.xml');
$xml->load('xsl/phytokeys.xml');

//$xml->load('xsl/113676.jats.xml');

//$xml->load('xsl/h3.jats.xml');

//$xml->load('xsl/219-232-1-PB.xml');


$xsl = new DOMDocument;
$xsl->load('xsl/jats2html.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attach the xsl rules

echo $proc->transformToXML($xml);

?>