<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// echo "Mikomos<br />";

	$xml = new DOMDocument('1.0', 'utf-8');
// add namespace
	$kml = $xml->appendChild($xml->createElement('kml'));
	$xmlns = $kml->appendChild($xml->createAttribute('xmlns'));
	$xmlns->appendChild($xml->createTextNode('http://earth.google.com/kml/2.1'));

	$doc = $kml->appendChild($xml->createElement('Document'));
	$docName = $doc->appendChild($xml->createElement('name'));
	$docName->appendChild($xml->createTextNode('Mikomos Dating Spots '.date("Y")));
	$docDesc = $doc->appendChild($xml->createElement('description'));
	$docDesc->appendChild($xml->createTextNode('Generated by EZ Mikomos Script on '.date("m").'-'.date("d").'-'.date("Y")));

$query = json_decode(file_get_contents("http://mikomos.com/w/api.php?action=query&list=categorymembers&cmtitle=Category:Mikomos&format=json"));
// print_r ($query);

foreach ($query->{'query'} as $array) {
	
//	print_r ($array);
	
	foreach ($array as $key => $value) {
		
//		print_r ($value);
		$category = "";
		$category = $value->{'title'};
		
		if ($category != "Event makom") {
		
			$folder = $doc->appendChild($xml->createElement('Folder'));
			$folName = $folder->appendChild($xml->createElement('name'));
			$folName->appendChild($xml->createTextNode(str_replace("Category:", "", $category)));
			
			$url = "http://www.mikomos.com/w/index.php?title=Special:Ask&q=[[".urlencode($category)."]]&po=?Address%0D%0A?City%0D%0A?State%0D%0A?Zip%0D%0A?Country%0D%0A?Phone%0D%0A?Coordinates&p[format]=json&p[limit]=2000&p[headers]=hide";
			$get = file_get_contents ($url);

			$json = json_decode($get);


			foreach ($json->{'results'} as $key => $value) {
			$name = ""; $address = ""; $city = ""; $state = ""; $zip = ""; $country = ""; $phone = ""; $geo = ""; $lon = ""; $lat = "";
				if ($value->{'printouts'}->{'Address'} || $value->{'printouts'}->{'Coordinates'}) {

					$name = $value->{'fulltext'};
					
					if ($value->{'printouts'}->{'Address'}) {
						$address = $value->{'printouts'}->{'Address'}[0];
						$geoAddr = str_replace(" ", "+", $value->{'printouts'}->{'Address'}[0]);

					}
					if ($value->{'printouts'}->{'City'}) {
						$city = $value->{'printouts'}->{'City'}[0];
						$geoCity = str_replace(" ", "+", $value->{'printouts'}->{'City'}[0]);
					}
					if ($value->{'printouts'}->{'State'}) {
						$state = $value->{'printouts'}->{'State'}[0]->{'fulltext'};
						}
					if ($value->{'printouts'}->{'Zip'}) {
						$zip = $value->{'printouts'}->{'Zip'}[0];
					}
					if ($value->{'printouts'}->{'Country'}) {
						$country = $value->{'printouts'}->{'Country'}[0]->{'fulltext'};
					}
					if ($value->{'printouts'}->{'Phone'}) {
						$phone = $value->{'printouts'}->{'Phone'}[0];
					}
					if ($value->{'printouts'}->{'Coordinates'}) {
						$lon = $value->{'printouts'}->{'Coordinates'}[0]->{'lon'};
						$lat = $value->{'printouts'}->{'Coordinates'}[0]->{'lat'};
					}
					if (!$value->{'printouts'}->{'Coordinates'} && $address) {
						$geoURL = "http://www.datasciencetoolkit.org/street2coordinates/".$geoAddr.",+".$geoCity.",+".$state."+".$zip;
//						print "<a href=".$geoURL.">".$geoURL."</a><br>";
						$geoJSON = file_get_contents($geoURL);
						$geo = json_decode($geoJSON);
						foreach ($geo as $key => $value) {
							if ($value && $value != "null") {
								$lon = $value->{"longitude"};
								$lat = $value->{"latitude"};
							}
						}
					}

					$placemark = $folder->appendChild($xml->createElement('Placemark'));
					$placeName = $placemark->appendChild($xml->createElement('name'));
					$placeName->appendChild($xml->createTextNode($name));

					$point = $placemark->appendChild($xml->createElement('Point'));
					$placeCoords = $point->appendChild($xml->createElement('coordinates'));
					$placeCoords->appendChild($xml->createTextNode($lon.",".$lat.", 0"));

					$desc = $placemark->appendChild($xml->createElement('description'));
					$desc->appendChild($xml->createTextNode("Address: \n".$address."\n".$city.", ".$state." ".$zip."\n".$country));

					$phoneNum = $placemark->appendChild($xml->createElement('phoneNumber'));
					$phoneNum->appendChild($xml->createTextNode($phone));
				}
			}

		}

	}

}

$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
// print $xml->saveXML();
$xml->save('Mikomos_EZ_'.date("Y").'.'.date("m").'_'.date("y").date("m").date("d").'.kml');
	
?>