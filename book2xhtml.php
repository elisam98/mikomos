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

			$template = file_get_contents("book_template.html");
			$template = str_replace("{{title}}", $title, $template);
			
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

        $template = str_replace("{{#}}", $i, $template);

			if ($address) {        
				$template = str_replace("{{address}}", $address, $template);
			}
			if ($city) {
				$template = str_replace("{{city}}", $city, $template);
			}
			if ($state) {
				$template = str_replace("{{state}}", $state, $template);
			}
			if ($zip) {
				$template = str_replace("{{zip}}", $zip, $template);
			}
			if ($country) {
				$template = str_replace("{{country}}", $country, $template);
			}
			if ($phone) {
				$template = str_replace("{{phone}}", $phone, $template);
			}
			if ($neighborhood) {
				$template = str_replace("{{neighborhood}}", $neighborhood, $template);
			}
			if ($price) {
				$template = str_replace("{{price}}", $price, $template);
			}
			if ($web_page) {
			}
			if ($activity_type) {
				$template = str_replace("{{type}}", $activity_type." Activity", $template);
			}
			if ($lounge_type) {
				$template = str_replace("{{type}}", $lounge_type." Lounge", $template);
			}
			if ($museum_type) {
				$template = str_replace("{{type}}", $museum_type." Museum", $template);
			}
			if ($park_type) {
				$template = str_replace("{{type}}", $park_type." Park", $template);
			}
			if ($shopping_type) {
				$template = str_replace("{{type}}", $shopping_type." Shopping", $template);
			}
			if ($cuisine) {
				$template = str_replace("{{type}}", "Food; ".$cuisine, $template);
			}
			if ($basic_description) {
				$template = str_replace("{{description}}", $basic_description, $template);
			}
			else {
				$template = str_replace("{{description}}", "", $template);
			}
			if ($tips) {
				$template = str_replace("{{tips}}", $tips, $template);
			}
			if ($dairy_or_meat) {
				$template = str_replace("{{dairy_or_meat}}", $dairy_or_meat, $template);
			}
			if ($hashgacha) {
				$template = str_replace("{{hashgacha}}", $hashgacha, $template);
			}
			if ($additional_kashrus) {
				$template = str_replace("{{additional_kashrus}}", $additional_kashrus, $template);
			}
			if ($menu) {
			}
			if ($hours) {
				$template = str_replace("{{hours}}", $hours, $template);
			}
			if ($directions) {
				$template = str_replace("{{directions}}", $directions, $template);
			}
			++$i;
			$fullResults .= $template;
		}
	}
}
while ($xml->{'query-continue'});

print_r ($fullResults);

?>