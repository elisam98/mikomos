<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>Book Template</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<style type="text/css">
			body {max-width: 8.5in; font-family: Arial; margin: 0; padding: 0.1in;}
			.header {margin-bottom: .5in; display: block; overflow: hidden; font-size: 8px;}
			.breadcrumbs {float: left;}
			.pageNum {float: right;}
			ol li {display: inline;}
			ol {padding: 0;}
			.title {font-size: 20px; font-weight: bold;}
			.type {font-size: 12px; font-weight: bold; font-style: italic; color:#888888;}
			.addressBar {font-size: 10px;}
			hr {margin: .05in;}
			.desc {margin-bottom: .1in; font-size: 10px;}
			.tip {margin-bottom: .1in; font-size: 10px; font-weight: bold;}
			span.tips {font-size: 8px; font-style: italic; color:#888888;}
			.content {margin-bottom: .2in;}
			.bottom, #firstRow, #secondRow {overflow: hidden;}
			.topLeft {float:left; width:50%;}
			.topRight {float:right; width:50%;}
			.bottomLeft {float:left; width:50%;}
			.bottomRight {float:right; width:50%;}
			.quarter {margin: .1in;}
			.subHeader {font-size: 10px; font-weight: bold;}
			.subItalic {font-size: 8px; font-style: italic;}
			.subContent {font-size: 8px; color:#888888;}
		</style>
	</head>
	<body>
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$continue = "";
$i = 1;
$fullResults = "";

do 
{
//	$template = file_get_contents("book_template.html");
	$template = <<<EOF
		<div class="header">
			<div class="breadcrumbs">
				<ol>
					<li>Mikomos</li>
					<li>>> <span class="country">{{country}}</span></li>
					<li>>> <span class="state">{{state}}</span></li>
					<li>>> <span class="neighborhood">{{neighborhood}}</span></li>							
				</ol>
			</div>
			<div class="pageNum">Page 
				<span class="pageNumber">{{#}}</span>
			</div>
		</div>
		<div class="main">
			<div class="top">
				<span class="title">{{title}}</span>
				<br />
				<span class="type">{{type}}</span>
				<br />
				<ol class="addressBar">
					<li><span class="neighborhood">{{neighborhood}}</span></li>
					<li> &bull; <span class="address">{{address}}</span></li>
					<li> &bull; 
						<span class="city">{{city}}, </span>
						<span class="state">{{state}} </span>
						<span class="zip">{{zip}} </span>
						<span class="country">{{country}}</span>
					</li>
					<li> &bull; <span class="phone">{{phone}}</span></li>
				</ol>
			</div>
			<hr />
			<div class="content">
				<div class="desc">
					<span class="description">{{description}}</span>
				</div>
				<div class="tip">Tips 
					<br />
					<span class="tips">{{tips}}</span>
				</div>
			</div>
			<div class="bottom">
				<div class="firstRow">
					<div class="topLeft">
						<div class="quarter">
							<span class="subHeader">Hours </span>
							<span class="subItalic">Subject to Change</span>
							<hr />
							<div class="subContent">
								<span class="hours">{{hours}}</span>
							</div>
						</div>
					</div>
					<div class="topRight">
						<div class="quarter">
							<span class="subHeader">Directions</span>
							<hr />
							<div class="subContent">
								<span class="directions">{{directions}}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="secondRow">
					<div class="bottomLeft">
						<div class="quarter">
							<span class="subHeader">Average Price</span>
							<hr />
							<div class="subContent">
								<span class="price">{{price}}</span>
							</div>
						</div>
					</div>
					<div class="bottomRight">
						<div class="quarter">
							<span class="subHeader">Kashrus</span>
							<hr />
							<div class="subContent">
								<ul>
									<li><span class="meat_or_dairy">{{meat_or_dairy}}</span></li>
									<li><span class="hashgacha">{{hashgacha}}</span></li>
									<li><span class="additional_kashrus">{{additional_kashrus}}</span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
EOF;

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
			else {
				$template = str_replace("<li> &bull; <span class=\"address\">{{address}}</span></li>", "", $template);
			}
			if ($city) {
				$template = str_replace("{{city}}", $city, $template);
			}
			else {
				$template = str_replace("<span class=\"city\">{{city}}, </span>", "", $template);
			}
			if ($state) {
				$template = str_replace("{{state}}", $state, $template);
			}
			else {
				$template = str_replace("<li>>> <span class=\"state\">{{state}}</span></li>", "", $template);
				$template = str_replace("<span class=\"state\">{{state}} </span>", "", $template);
			}
			if ($zip) {
				$template = str_replace("{{zip}}", $zip, $template);
			}
			else {
				$template = str_replace("<span class=\"zip\">{{zip}} </span>", "", $template);
			}
			if ($country) {
				$template = str_replace("{{country}}", $country, $template);
			}
			else {
				$template = str_replace("<li>>> <span class=\"country\">{{country}}</span></li>", "", $template);
				$template = str_replace("<span class=\"country\">{{country}}</span>", "", $template);
			}
			if ($phone) {
				$template = str_replace("{{phone}}", $phone, $template);
			}
			else {
				$template = str_replace("<li> &bull; <span class=\"phone\">{{phone}}</span></li>", "", $template);
			}
			if ($neighborhood) {
				$template = str_replace("{{neighborhood}}", $neighborhood, $template);
			}
			else {
				$template = str_replace("<li>>> <span class=\"neighborhood\">{{neighborhood}}</span></li>", "", $template);
				$template = str_replace("<span class=\"neighborhood\">{{neighborhood}}</span></li>
					<li> &bull; ", "", $template);
			}
			if ($price) {
				$template = str_replace("{{price}}", $price, $template);
			}
			else {
				$template = str_replace("<div class=\"quarter\">
							<span class=\"subHeader\">Average Price</span>
							<hr />
							<div class=\"subContent\">
								<span class=\"price\">{{price}}</span>
							</div>
						</div>", "", $template);
			}
			if ($activity_type) {
				$template = str_replace("{{type}}", "Activity; ".$activity_type, $template);
			}
			else {
//				$template = str_replace("{{address}}", "", $template);
			}
			if ($lounge_type) {
				$template = str_replace("{{type}}", "Lounge; ".$lounge_type, $template);
			}
			else {
//				$template = str_replace("{{address}}", "", $template);
			}
			if ($museum_type) {
				$template = str_replace("{{type}}", "Museum; ".$museum_type, $template);
			}
			else {
//				$template = str_replace("{{address}}", "", $template);
			}
			if ($park_type) {
				$template = str_replace("{{type}}", "Park; ".$park_type, $template);
			}
			else {
//				$template = str_replace("{{address}}", $address, $template);
			}
			if ($shopping_type) {
				$template = str_replace("{{type}}", "Shopping; ".$shopping_type, $template);
			}
			else {
//				$template = str_replace("{{address}}", $address, $template);
			}
			if ($cuisine) {
				$template = str_replace("{{type}}", "Food; ".$cuisine, $template);
			}
			else {
				$template = str_replace("<span class=\"type\">{{type}}</span>", $address, $template);
			}
			if ($basic_description) {
				$template = str_replace("{{description}}", $basic_description, $template);
			}
			else {
				$template = str_replace("<span class=\"description\">{{description}}</span>", "", $template);
			}
			if ($tips) {
				$template = str_replace("{{tips}}", $tips, $template);
			}
			else {
				$template = str_replace("<div class=\"tip\">Tips 
					<br />
					<span class=\"tips\">{{tips}}</span>
				</div>", "", $template);
			}
			if (!$dairy_or_meat && !$hashgacha && !$additional_kashrus) {
				$template = str_replace("<div class=\"quarter\">
							<span class=\"subHeader\">Kashrus</span>
							<hr />
							<div class=\"subContent\">
								<ul>
									<li><span class=\"meat_or_dairy\">{{meat_or_dairy}}</span></li>
									<li><span class=\"hashgacha\">{{hashgacha}}</span></li>
									<li><span class=\"additional_kashrus\">{{additional_kashrus}}</span></li>
								</ul>
							</div>
						</div>", "", $template);
			}
			if ($dairy_or_meat) {
				$template = str_replace("{{meat_or_dairy}}", $dairy_or_meat, $template);
			}
			else {
				$template = str_replace("<li><span class=\"meat_or_dairy\">{{meat_or_dairy}}</span></li>", "", $template);
			}
			if ($hashgacha) {
				$template = str_replace("{{hashgacha}}", $hashgacha, $template);
			}
			else {
				$template = str_replace("<li><span class=\"hashgacha\">{{hashgacha}}</span></li>", "", $template);
			}
			if ($additional_kashrus) {
				$template = str_replace("{{additional_kashrus}}", $additional_kashrus, $template);
			}
			else {
				$template = str_replace("<li><span class=\"additional_kashrus\">{{additional_kashrus}}</span></li>", "", $template);
			}
			if ($menu) {
			}
			else {
			}
			if ($hours) {
				$template = str_replace("{{hours}}", $hours, $template);
			}
			else {
				$template = str_replace("<div class=\"quarter\">
							<span class=\"subHeader\">Hours </span>
							<span class=\"subItalic\">Subject to Change</span>
							<hr />
							<div class=\"subContent\">
								<span class=\"hours\">{{hours}}</span>
							</div>
						</div>", "", $template);
			}
			if ($directions) {
				$template = str_replace("{{directions}}", $directions, $template);
			}
			else {
				$template = str_replace("<div class=\"quarter\">
							<span class=\"subHeader\">Directions</span>
							<hr />
							<div class=\"subContent\">
								<span class=\"directions\">{{directions}}</span>
							</div>
						</div>", "", $template);
			}
			++$i;
			$template .= "<div style=\"page-break-before: always\" />";
			print_r ($template);
		}
	}
}
while ($xml->{'query-continue'});


?>
	</body>
</html>