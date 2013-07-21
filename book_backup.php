<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$continue = "";

do 
{
	
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
			
			echo "<strong>";
			echo $title;
			echo "</strong>";
			echo "<br>";

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

			if ($address) {
				echo "Address: ".$address;
				echo "<br>";
			}
			if ($city) {
				echo "City: ".$city;
				echo "<br>";
			}
			if ($state) {
				echo "State: ".$state;
				echo "<br>";
			}
			if ($zip) {
				echo "Zip: ".$zip;
				echo "<br>";
			}
			if ($country) {
				echo "Country: ".$country;
				echo "<br>";
			}
			if ($phone) {
				echo "Phone: ".$phone;
				echo "<br>";
			}
			if ($neighborhood) {
				echo "Neighborhood: ".$neighborhood;
				echo "<br>";
			}
			if ($price) {
				echo "Price: ".$price;
				echo "<br>";
			}
			if ($web_page) {
				echo "Website: <a href='".$web_page."'>".$web_page."</a>";
				echo "<br>";
			}
			if ($activity_type) {
			echo "<strong>";
				echo "Activity: ".$activity_type;
				echo "<br>";
			echo "</strong>";
			}
			if ($lounge_type) {
			echo "<strong>";
				echo "Lounge Type: ".$lounge_type;
				echo "<br>";
			echo "</strong>";
			}
			if ($museum_type) {
			echo "<strong>";
				echo "Museum Type: ".$museum_type;
				echo "<br>";
			echo "</strong>";
			}
			if ($park_type) {
			echo "<strong>";
				echo "Park Type: ".$park_type;
				echo "<br>";
			echo "</strong>";
			}
			if ($shopping_type) {
			echo "<strong>";
				echo "Shopping Type: ".$shopping_type;
				echo "<br>";
			echo "</strong>";
			}
			if ($cuisine) {
			echo "<strong>";
				echo "Cuisine: ".$cuisine;
				echo "<br>";
			echo "</strong>";
			}
			if ($basic_description) {
				echo "Desciption: ".$basic_description;
				echo "<br>";
			}
			if ($tips) {
				echo "Tips: ".$tips;
				echo "<br>";
			}
			if ($dairy_or_meat) {
				echo "Dairy or Meat? ".$dairy_or_meat;
				echo "<br>";
			}
			if ($hashgacha) {
				echo "Hashgacha: ".$hashgacha;
				echo "<br>";
			}
			if ($additional_kashrus) {
				echo "additional_kashrus: ".$additional_kashrus;
				echo "<br>";
			}
			if ($menu) {
				echo "Menu: <a href='".$menu."'>".$menu."</a>";
				echo "<br>";
			}
			if ($hours) {
				echo "Hours: ".$hours;
				echo "<br>";
			}
			if ($directions) {
				echo "Directions: ".$directions;
				echo "<br>";
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
			echo "<hr>";
		}
	}
}
while ($xml->{'query-continue'});
?>