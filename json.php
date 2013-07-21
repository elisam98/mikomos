<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

		$geoURL = file_get_contents("http://www.datasciencetoolkit.org/street2coordinates/27+Atlantic+Avenue,+Lynbrook,+NY+11563");
		$geo = json_decode($geoURL);
		foreach ($geo as $key=>$value) {
		var_dump($value->{"longitude"});
		}


?>