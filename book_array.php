<?php
ini_set('display_errors', 'Off');
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

printTOC($array);


function printTOC($array) {

	foreach ($array as $key => $row) {
		// if ($row['state']) {
			$countrySort[$key] = $row['country'];
			$stateSort[$key] = $row['state'];
			$neighborhoodSort[$key] = $row['neighborhood'];
			$titleSort[$key] = $row['title'];
		// }
	}

	array_multisort($countrySort, SORT_ASC, $stateSort, SORT_ASC, $neighborhoodSort, SORT_ASC, $titleSort, SORT_ASC, $array);

	//	print_r($array);
	// print_r ($fullResults);

		$countries = array();
		$states = array();
		$neighborhoods = array();

	echo "<table>
			<thead>
				<tr>
					<td clospan=\"3\" align=\"justify\"><h1>Table of Contents</h1></td>
				</tr>
				<tr>
					<th></th>
					<th>Makom</th>
					<th>Page</th>
				</tr>
			</thead>
			<tbody>";
	foreach ($array as $key => $value) {
		$key = $key+1;

		if ((!in_array($value['country'], $countries)) || (!in_array($value['state'], $states)) || (!in_array($value['neighborhood'], $neighborhoods))) {
			array_push($countries, $value['country']);
			array_push($states, $value['state']);
			array_push($neighborhoods, $value['neighborhood']);
	//		print_r ($countr."<br>");
			echo "<tr>
					<td>&nbsp;</td>
					<td colspan=\"2\"><hr /></td>
				</tr>
				<tr>";
			
			if ($value['neighborhood']) {
				echo "<th align=\"right\">".$value['country']." &bull; ".$value['state']." &bull; ".$value['neighborhood']."</th>";
			}
			elseif ($value['state']) {
				echo "<th align=\"right\">".$value['country']." &bull; ".$value['state']."</th>";
			}
			elseif ($value['country']) {
				echo "<th align=\"right\">".$value['country']."</th>";
			}
			
			echo "</tr>";
		}

			echo "<tr>
					<td></td>
					<td>".$value['title']."</td>
					<td align=\"right\">".$key."</td>
				</tr>";

	}
	//	print_r ($countries);
		echo "</tbody>
				</table>";
		echo "<div style=\"page-break-before: always\">&nbsp</div>";
}

function printIndex($array) {
	////////////////////////////////////
	// Print Back Index (Alphabetical //
	////////////////////////////////////

	echo "<table>
			<thead>
				<tr>
					<th colspan=\"3\"><h1>Alphabetical Index of Mikomos</h1></th>
				</tr>
			</thead>
			<tbody>";

	$k = 1;


	foreach ($array as $key => $value) {

		$key = $key+1;
	//		echo ($key).": ".$value['title']."<br />";
			$index[$key] = $value['title'];
			// ++$k;
	}
	
	asort($index);
	
	foreach ($index as $key => $value) {

		echo "<tr>
				<td align=\"right\">$value</td>
				<td style=\"width:5in;\"><hr style=\"border: 0; border-bottom: 1px dashed black; color:white;\" /></td>
				<td align=\"justify\">Page $key</td>
			</tr>";
	}
		echo "</tbody>
				</table>";

	///////////////
	// End Index //
	///////////////
}

?>