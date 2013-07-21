<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$continue = "";
$i = 1;
$fullResults = "";

do 
{
	$template = file_get_contents("book_template.html");

	$xml = simplexml_load_file("http://www.mikomos.com/w/api.php?action=query&format=xml&generator=allpages&prop=revisions&rvprop=content&gaplimit=max&gapcontinue=".$continue);
	if ($xml->{'query-continue'}) {
		$continue = ((string)$xml->{'query-continue'}[0]->allpages['gapcontinue']);
	}
	
$pages = ($xml->query->pages);

	foreach ($pages->page as $page) {

		$title = (string)$page['title'];

		$content = (string)$page->revisions->rev;

		$mb = "{{Makombox";
		$makom = strpos($content, "{{Makombox");

		if ($makom !== false) {
//			utf8_decode($content);
			$template = file_get_contents("book_template.html");
			$template = str_replace("{{title}}", $title, $template);
			
//			echo "<strong>";
//			echo $title;
//			echo "</strong>";
//			echo "<br>";

			$content = str_replace("\n", "", $content);
			$content = str_replace("[", "", $content);
			$content = str_replace("]", "", $content);
			$content = str_replace("{{Makombox All", "", $content);
			$content = str_replace("{{Tags", "", $content);
			$content = str_replace("}}", "", $content);
			$content = str_replace("|", "&", $content);

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
        $template = str_replace("{{#}}", $i, $template);

			if ($address) {        
        $template = str_replace("{{address}}", $address, $template);
        //				$array[$country][$state][$neighborhood][$title]['Address'] = $address;
//				++$i;
//				echo "Address: ".$address;
//				echo "<br>";
			}
			if ($city) {
        $template = str_replace("{{city}}", $city, $template);
        //				$array[$country][$state][$neighborhood][$title]['City'] = $city;
//				echo "City: ".$city;
//				echo "<br>";
			}
			if ($state) {
        $template = str_replace("{{state}}", $state, $template);
        //				$array[$country][$state][$neighborhood][$title]['State'] = $state;
//				echo "State: ".$state;
//				echo "<br>";
			}
			if ($zip) {
        $template = str_replace("{{zip}}", $zip, $template);
        //				$array[$country][$state][$neighborhood][$title]['Zip'] = $zip;
//				echo "Zip: ".$zip;
//				echo "<br>";
			}
			if ($country) {
        $template = str_replace("{{country}}", $country, $template);
//				echo "Country: ".$country;
//				echo "<br>";
			}
			if ($phone) {
        $template = str_replace("{{phone}}", $phone, $template);
//				echo "Phone: ".$phone;
//				echo "<br>";
			}
			if ($neighborhood) {
        $template = str_replace("{{neighborhood}}", $neighborhood, $template);
//				echo "Neighborhood: ".$neighborhood;
//				echo "<br>";
			}
			if ($price) {
        $template = str_replace("{{price}}", $price, $template);
//				echo "Price: ".$price;
//				echo "<br>";
			}
			if ($web_page) {
//				echo "Website: <a href='".$web_page."'>".$web_page."</a>";
//				echo "<br>";
			}
			if ($activity_type) {
        $template = str_replace("{{type}}", $activity_type." Activity", $template);
//			echo "<strong>";
//				echo "Activity: ".$activity_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($lounge_type) {
        $template = str_replace("{{type}}", $lounge_type." Lounge", $template);
//			echo "<strong>";
//				echo "Lounge Type: ".$lounge_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($museum_type) {
        $template = str_replace("{{type}}", $museum_type." Museum", $template);
//			echo "<strong>";
//				echo "Museum Type: ".$museum_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($park_type) {
        $template = str_replace("{{type}}", $park_type." Park", $template);
//			echo "<strong>";
//				echo "Park Type: ".$park_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($shopping_type) {
        $template = str_replace("{{type}}", $shopping_type." Shopping", $template);
//			echo "<strong>";
//				echo "Shopping Type: ".$shopping_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($cuisine) {
        $template = str_replace("{{type}}", "Food; ".$cuisine, $template);
//			echo "<strong>";
//				echo "Cuisine: ".$cuisine;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($basic_description) {
        $template = str_replace("{{description}}", $basic_description, $template);
//				echo "Desciption: ".$basic_description;
//				echo "<br>";
			}
			if ($tips) {
        $template = str_replace("{{tips}}", $tips, $template);
//				echo "Tips: ".$tips;
//				echo "<br>";
			}
			if ($dairy_or_meat) {
        $template = str_replace("{{dairy_or_meat}}", $dairy_or_meat, $template);
//				echo "Dairy or Meat? ".$dairy_or_meat;
//				echo "<br>";
			}
			if ($hashgacha) {
        $template = str_replace("{{hashgacha}}", $hashgacha, $template);
//				echo "Hashgacha: ".$hashgacha;
//				echo "<br>";
			}
			if ($additional_kashrus) {
        $template = str_replace("{{additional_kashrus}}", $additional_kashrus, $template);
//				echo "additional_kashrus: ".$additional_kashrus;
//				echo "<br>";
			}
			if ($menu) {
//				echo "Menu: <a href='".$menu."'>".$menu."</a>";
//				echo "<br>";
			}
			if ($hours) {
        $template = str_replace("{{hours}}", $hours, $template);
//				echo "Hours: ".$hours;
//				echo "<br>";
			}
			if ($directions) {
        $template = str_replace("{{directions}}", $directions, $template);
//				echo "Directions: ".$directions;
//				echo "<br>";
			}
			// if ($tips) {
				// echo "Tips: ".$tips;
				// echo "<br>";
			// }
			// if ($tips) {
				// echo "Tips: ".$tips;
				// echo "<br>";
			// }
			// if ($tips) {
				// echo "Tips: ".$tips;
				// echo "<br>";
			// }
//			echo "<hr>";
			++$i;
			$fullResults .= $template;
		}
	}
}
while ($xml->{'query-continue'});

//print_r($array);
print_r ($fullResults);

?>