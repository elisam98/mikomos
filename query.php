<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$query = json_decode(file_get_contents("http://mikomos.com/w/api.php?action=query&list=categorymembers&cmtitle=Category:Mikomos&format=json"));
// print_r ($query);

foreach ($query->{'query'} as $array) {
	foreach ($array as $key => $value) {
	print_r ($value->{'title'});
	}
}

?>