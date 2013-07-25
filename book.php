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
			$museum_type="";$park_type="";$shopping_type="";$image="";

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

			if ($address) {        
				$array[$country][$state][$neighborhood][$title]['address'] = $address;
//				++$i;
//				echo "Address: ".$address;
//				echo "<br>";
			}
			if ($city) {
				$array[$country][$state][$neighborhood][$title]['city'] = $city;
//				echo "City: ".$city;
//				echo "<br>";
			}
			if ($state) {
				$array[$country][$state][$neighborhood][$title]['state'] = $state;
//				echo "State: ".$state;
//				echo "<br>";
			}
			if ($zip) {
				$array[$country][$state][$neighborhood][$title]['zip'] = $zip;
//				echo "Zip: ".$zip;
//				echo "<br>";
			}
			if ($country) {
				$array[$country][$state][$neighborhood][$title]['country'] = $country;
//				echo "Country: ".$country;
//				echo "<br>";
			}
			if ($phone) {
				$array[$country][$state][$neighborhood][$title]['phone'] = $phone;
//				echo "Phone: ".$phone;
//				echo "<br>";
			}
			if ($neighborhood) {
				$array[$country][$state][$neighborhood][$title]['neighborhood'] = $neighborhood;
//				echo "Neighborhood: ".$neighborhood;
//				echo "<br>";
			}
			if ($price) {
				$array[$country][$state][$neighborhood][$title]['price'] = $price;
//				echo "Price: ".$price;
//				echo "<br>";
			}
			if ($web_page) {
//				echo "Website: <a href='".$web_page."'>".$web_page."</a>";
//				echo "<br>";
			}
			if ($activity_type) {
				$array[$country][$state][$neighborhood][$title]['activity_type'] = $activity_type;
//			echo "<strong>";
//				echo "Activity: ".$activity_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($lounge_type) {
				$array[$country][$state][$neighborhood][$title]['lounge_type'] = $activity_type;
//			echo "<strong>";
//				echo "Lounge Type: ".$lounge_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($museum_type) {
				$array[$country][$state][$neighborhood][$title]['museum_type'] = $museum_type;
//			echo "<strong>";
//				echo "Museum Type: ".$museum_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($park_type) {
				$array[$country][$state][$neighborhood][$title]['park_type'] = $park_type;
//			echo "<strong>";
//				echo "Park Type: ".$park_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($shopping_type) {
				$array[$country][$state][$neighborhood][$title]['shopping_type'] = $shopping_type;
//			echo "<strong>";
//				echo "Shopping Type: ".$shopping_type;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($cuisine) {
				$array[$country][$state][$neighborhood][$title]['cuisine'] = $cuisine;
//			echo "<strong>";
//				echo "Cuisine: ".$cuisine;
//				echo "<br>";
//			echo "</strong>";
			}
			if ($basic_description) {
				$array[$country][$state][$neighborhood][$title]['basic_description'] = $basic_description;
//				echo "Desciption: ".$basic_description;
//				echo "<br>";
			}
			if ($tips) {
				$array[$country][$state][$neighborhood][$title]['tips'] = $tips;
//				echo "Tips: ".$tips;
//				echo "<br>";
			}
			if ($dairy_or_meat) {
				$array[$country][$state][$neighborhood][$title]['dairy_or_meat'] = $dairy_or_meat;
//				echo "Dairy or Meat? ".$dairy_or_meat;
//				echo "<br>";
			}
			if ($hashgacha) {
				$array[$country][$state][$neighborhood][$title]['hashgacha'] = $hashgacha;
//				echo "Hashgacha: ".$hashgacha;
//				echo "<br>";
			}
			if ($additional_kashrus) {
				$array[$country][$state][$neighborhood][$title]['additional_kashrus'] = $additional_kashrus;
//				echo "additional_kashrus: ".$additional_kashrus;
//				echo "<br>";
			}
			if ($menu) {
				$array[$country][$state][$neighborhood][$title]['menu'] = $menu;
//				echo "Menu: <a href='".$menu."'>".$menu."</a>";
//				echo "<br>";
			}
			if ($hours) {
				$array[$country][$state][$neighborhood][$title]['hours'] = $hours;
//				echo "Hours: ".$hours;
//				echo "<br>";
			}
			if ($directions) {
				$array[$country][$state][$neighborhood][$title]['directions'] = $directions;
//				echo "Directions: ".$directions;
//				echo "<br>";
			}
			if ($image) {
				$image = str_replace(" ", "%20", $image);
				$query = simplexml_load_file("http://www.mikomos.com/w/api.php?action=query&titles=File:$image&prop=imageinfo&iiprop=url&format=xml");
				$result = (string)$query->query->pages->page->imageinfo->ii['url'];

				echo $image."<br />";
				echo "<img src=\"$result\">";
				echo "<br />";
//				echo "<br />";
				
/* 				$hash = md5($image);
				$one = substr($hash, 0, 1);
				$two = substr($hash, 0, 2);
				
				$url = "http://www.mikomos.com/w/images/$one/$two/$image";
				$result = get_headers($url);
				$result = $result[0];
				
//					echo $result.": ".$url."<br />";
					
 				if ($result == "HTTP/1.0 200 OK") {
					echo $image.": ".$hash."<br />";
					echo "<img src=\"$url\">";
				}
				else {
					$url = "http://upload.wikimedia.org/wikipedia/commons/$one/$two/$image";
					echo $image.": ".$hash."<br />";
					echo "<img src=\"$url\">";
					echo "<br />";
				}
 */			
/* 				if (strpos($image, "http://")) {
					echo "<img src='".$image."' />";
				}
				else {
					echo "<img src='http://www.mikomos.com/w/images/b/b4/".$image."' />";
				} */
			}
			++$i;
			$fullResults .= $template;
		}
	}
}
while ($xml->{'query-continue'});

// print_r($array);
// print_r ($fullResults);

?>