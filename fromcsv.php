<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);


function readCSV($csvFile){
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
}


// Set path to CSV file
$csvFile = "http://www.mikomos.com/w/index.php?title=Special:Ask&q=[[Category:Mikomos]]&po=?Address%0D%0A?City%0D%0A?State%0D%0A?Zip%0D%0A?Country%0D%0A?Phone%0D%0A?Coordinates&p[format]=csv&p[limit]=2000&p[headers]=hide";

$arrCSV = readCSV($csvFile);
// print_r($arrCSV);

	$xml = new DOMDocument('1.0', 'utf-8');
// add namespace
	$kml = $xml->appendChild($xml->createElement('kml'));
	$xmlns = $kml->appendChild($xml->createAttribute('xmlns'));
	$xmlns->appendChild($xml->createTextNode('http://earth.google.com/kml/2.1'));

	$doc = $xml->appendChild($xml->createElement('Document'));
	$docName = $doc->appendChild($xml->createElement('name'));
	$docName->appendChild($xml->createTextNode('Mikomos Dating Spots'));
	
foreach ($arrCSV as $value)
{
	$name = $value[0];
	$address = $value[1];
	$city = $value[2];
	$state = $value[3];
	$zip = $value[4];
	$country = $value[5];
	$phone = $value[6];
	$coordinates = $value[7];
//	if ($country == "USA" /* $coordinates == "" && $address != "" */) {	
		$placemark = $doc->appendChild($xml->createElement('Placemark'));
		$placeName = $placemark->appendChild($xml->createElement('name'));
		$placeName->appendChild($xml->createTextNode($name));
	
		$geoAddr = str_replace(" ", "+", $address);
		$geoCity = str_replace(" ", "+", $city);
		$geoAddr = str_replace(" ", "+", $address);
		$geoURL = "http://www.datasciencetoolkit.org/street2coordinates/".$geoAddr.",+".$geoCity.",+".$state."+".$zip;
		$geoJSON = file_get_contents($geoURL);
		$geo = json_decode($geoJSON);
			foreach ($geo as $key => $value) {
				$long = $value->{"longitude"};
				$lat = $value->{"latitude"};
				if ($long == "" || $lat == "") {
					$long = $coordinates;
					$lat = $coordinates;
				}
			}

//		var_dump($geo);
		$point = $placemark->appendChild($xml->createElement('Point'));
		$placeCoords = $point->appendChild($xml->createElement('coordinates'));
		$placeCoords->appendChild($xml->createTextNode($long.", ".$lat.", 0"));

		$desc = $placemark->appendChild($xml->createElement('description'));
		$desc->appendChild($xml->createTextNode($address."\n".$city.", ".$state." ".$zip."\n".$country));

//	}
}
//	storeXML($xml);
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
 print $xml->saveXML();
// function storeXML($xml) {

// $temp = <<<EOT
// <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
// <xsl:output method="xml" encoding="UTF-8" indent="yes"/>
// <xsl:strip-space elements="*"/>
// <xsl:template match="node()">
  // <xsl:copy>
    // <xsl:apply-templates select="node()">
      // <xsl:sort select="lastname"/>
    // </xsl:apply-templates>
  // </xsl:copy>
// </xsl:template>
// </xsl:stylesheet>
// EOT;

// $xsl = new DOMDocument;
// $xsl->loadXML($temp);
// $proc = new XSLTProcessor;
// $proc->importStyleSheet($xsl); // attach the xsl rules

// $sorted = $proc->transformToXML($xml);

// $newXML = new DOMDocument;
// $newXML->loadXML($sorted);

// $newXML->preserveWhiteSpace = false;
// $newXML->formatOutput = true;
// $newXML->save('students.xml');
// echo "Success!";
// }
// To create a fragment, use the following lines:
// $fragment = $doc->createDocumentFragment();

?>