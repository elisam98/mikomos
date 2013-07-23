<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$continue = "";
$i = 1;

do 
{
	$xml = simplexml_load_file("http://www.mikomos.com/w/api.php?action=query&format=xml&generator=allpages&prop=revisions&rvprop=content&gaplimit=max&gapcontinue=".$continue);
	if ($xml->{'query-continue'}) {
		$continue = ((string)$xml->{'query-continue'}[0]->allpages['gapcontinue']);
	}
	
	$pages = ($xml->query->pages);

	foreach ($pages->page as $page) {

		$content = (string)$page->revisions->rev;

//		$mb = "{{Makombox";
		$makom = strpos($content, "{{Makombox");

		if ($makom !== false) {

			$content = str_replace("\n", "", $content);
			$content = str_replace("[", "", $content);
			$content = str_replace("]", "", $content);
			$content = str_replace("{{Makombox All", "", $content);
			$content = str_replace("{{Tags", "", $content);
			$content = str_replace("}}", "", $content);
			$content = str_replace("|", "&", $content);

			$title = (string)$page['title'];

			$address="";$city="";$state="";$zip="";$country="";$phone="";
			$neighborhood="";$price="";$web_page="";$basic_description = "";
			$activity_type="";$tips="";$hours="";$directions="";
			$cuisine="";$dairy_or_meat="";$hashgacha="";
			$additional_kashrus="";$menu="";$lounge_type="";
			$museum_type="";$park_type="";$shopping_type="";

			parse_str($content);
			if (!$country && $city) {
						$geoAddr = str_replace(" ", "+", $address);
						$geoCity = str_replace(" ", "+", $city);

						$geoURL = "http://www.datasciencetoolkit.org/street2coordinates/".$geoAddr.",+".$geoCity.",+".$state."+".$zip;
//						print "<a href=".$geoURL.">".$geoURL."</a><br>";
						$geoJSON = file_get_contents($geoURL);
						$geo = json_decode($geoJSON);
						foreach ($geo as $key => $value) {
							if ($value && $value != "null") {
								$country = $value->{"country_code3"};
							}
						}
			}
			if ($country) {
				$country = ucwords($country);
			}
			if ($state != "NY" && $state != "NJ") {
				$neighborhood = "";;
			}
// // // //
			$array[$i]['title'] = $title;
			
			if ($address) {        
				$array[$i]['address'] = $address;
//				++$i;
//				echo "Address: ".$address;
//				echo "<br>";
			}
			if ($city) {
				$array[$i]['city'] = $city;
//				echo "City: ".$city;
//				echo "<br>";
			}
			if ($state) {
				$array[$i]['state'] = $state;
//				echo "State: ".$state;
//				echo "<br>";
			}
			if ($zip) {
				$array[$i]['zip'] = $zip;
//				echo "Zip: ".$zip;
//				echo "<br>";
			}
			if ($country) {
				$array[$i]['country'] = $country;
//				echo "Country: ".$country;
//				echo "<br>";
			}
			if ($phone) {
				$array[$i]['phone'] = $phone;
//				echo "Phone: ".$phone;
//				echo "<br>";
			}
			if ($neighborhood) {
				$array[$i]['neighborhood'] = $neighborhood;
//				echo "Neighborhood: ".$neighborhood;
//				echo "<br>";
			}
			if ($price) {
				$array[$i]['price'] = $price;
//				echo "Price: ".$price;
//				echo "<br>";
			}
/* 			if ($web_page) {
				echo "Website: <a href='".$web_page."'>".$web_page."</a>";
				echo "<br>";
			}
 */			if ($activity_type) {
				$array[$i]['activity_type'] = $activity_type;
//			echo "<strong>";
//				echo "Activity: ".$activity_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($lounge_type) {
				$array[$i]['lounge_type'] = $activity_type;
//			echo "<strong>";
//				echo "Lounge Type: ".$lounge_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($museum_type) {
				$array[$i]['museum_type'] = $museum_type;
//			echo "<strong>";
//				echo "Museum Type: ".$museum_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($park_type) {
				$array[$i]['park_type'] = $park_type;
//			echo "<strong>";
//				echo "Park Type: ".$park_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($shopping_type) {
				$array[$i]['shopping_type'] = $shopping_type;
//			echo "<strong>";
//				echo "Shopping Type: ".$shopping_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($cuisine) {
				$array[$i]['cuisine'] = $cuisine;
//			echo "<strong>";
//				echo "Cuisine: ".$cuisine;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($basic_description) {
				$array[$i]['basic_description'] = $basic_description;
//				echo "Desciption: ".$basic_description;
//				echo "<br>";
			}
			if ($tips) {
				$array[$i]['tips'] = $tips;
//				echo "Tips: ".$tips;
//				echo "<br>";
			}
			if ($dairy_or_meat) {
				$array[$i]['dairy_or_meat'] = $dairy_or_meat;
//				echo "Dairy or Meat? ".$dairy_or_meat;
//				echo "<br>";
			}
			if ($hashgacha) {
				$array[$i]['hashgacha'] = $hashgacha;
//				echo "Hashgacha: ".$hashgacha;
//				echo "<br>";
			}
			if ($additional_kashrus) {
				$array[$i]['additional_kashrus'] = $additional_kashrus;
//				echo "additional_kashrus: ".$additional_kashrus;
//				echo "<br>";
			}
			if ($menu) {
				$array[$i]['menu'] = $menu;
//				echo "Menu: <a href='".$menu."'>".$menu."</a>";
//				echo "<br>";
			}
			if ($hours) {
				$array[$i]['hours'] = $hours;
//				echo "Hours: ".$hours;
//				echo "<br>";
			}
			if ($directions) {
				$array[$i]['directions'] = $directions;
//				echo "Directions: ".$directions;
//				echo "<br>";
			}
			++$i;
		}
	}
}
while ($xml->{'query-continue'});

foreach ($array as $key => $row) {
	// if ($row['state']) {
		$countrySort[$key] = $row['country'];
		$stateSort[$key] = $row['state'];
		$neighborhoodSort[$key] = $row['neighborhood'];
		$titleSort[$key] = $row['title'];
	// }
}

array_multisort($countrySort, SORT_ASC, $stateSort, SORT_ASC, $neighborhoodSort, SORT_ASC, $titleSort, SORT_ASC, $array);
print_r($array);
// print_r ($fullResults);

?>