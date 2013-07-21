<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

echo "Mikomos<br />";

$url = "http://www.mikomos.com/w/index.php?title=Special:Ask&q=[[Category:Mikomos]]&po=?Address%0D%0A?City%0D%0A?State%0D%0A?Zip%0D%0A?Country%0D%0A?Phone%0D%0A?Coordinates&p[format]=json&p[limit]=2000&p[headers]=hide";
$get = file_get_contents ($url);
// http://www.mikomos.com/wiki/Special:Ask/-5B-5BCategory:Mikomos-5D-5D/-3FAddress/-3FCity/-3FState/-3FZip/-3FCountry/-3FPhone/-3FCoordinates/mainlabel%3D/limit%3D1000/format%3Djson"

$json = json_decode($get);
// print_r ($json);
foreach ($json->{'results'} as $key => $value) {
echo "<p>";
print ($value->{'fulltext'});
	if ($value->{'printouts'}->{'Address'}) {
		$address = $value->{'printouts'}->{'Address'}[0];
		print ($address);
		$geoAddr = str_replace(" ", "+", $value->{'printouts'}->{'Address'}[0]);

	}
	if ($value->{'printouts'}->{'City'}) {
		$city = $value->{'printouts'}->{'City'}[0];
		print ($city);
		$geoCity = str_replace(" ", "+", $value->{'printouts'}->{'City'}[0]);
	}
	if ($value->{'printouts'}->{'State'}) {
		$state = $value->{'printouts'}->{'State'}[0]->{'fulltext'};
		print ($state);
		}
	if ($value->{'printouts'}->{'Zip'}) {
		$zip = $value->{'printouts'}->{'Zip'}[0];
		print ($zip);
	}
	if ($value->{'printouts'}->{'Country'}) {
		$country = $value->{'printouts'}->{'Country'}[0]->{'fulltext'};
		print ($country);
	}
	if ($value->{'printouts'}->{'Phone'}) {
		$phone = $value->{'printouts'}->{'Phone'}[0];
		print ($phone);
	}
	if ($value->{'printouts'}->{'Coordinates'}) {
		print ($value->{'printouts'}->{'Coordinates'}[0]->{'lon'});
		print ($value->{'printouts'}->{'Coordinates'}[0]->{'lat'});
	}
	if (!$value->{'printouts'}->{'Coordinates'}) {
//		$geoURL = "http://www.datasciencetoolkit.org/street2coordinates/".$geoAddr.",+".$geoCity.",+".$state."+".$zip;
		$geoURL = "http://www.datasciencetoolkit.org/street2coordinates/".$geoAddr.",+".$geoCity.",+".$state."+".$zip;
		$geoJSON = file_get_contents($geoURL);
		$geo = json_decode($geoJSON);
			foreach ($geo as $key => $value) {
				if ($value) {
					$long = $value->{"longitude"};
					print "Generated ".$long;
					$lat = $value->{"latitude"};
					print "Generated ".$lat;
				}
			}
	}
echo "</p>";
}
?>