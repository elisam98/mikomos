<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$archiveFile = "templates/MakomTemplate.odt";
$dataFile = "content.xml";

    // Create new ZIP archive
    $zip = new ZipArchive;


if ($zip->open($archiveFile) == TRUE) {
            // If found, read it to the string
            $data = $zip->getFromName("content.xml");
			$data = str_replace("{{address}}", "12 Timothy Court", $data);
			
			$zip->deleteName("content.xml");
			
			$xml = simplexml_load_string($data);

            print_r($xml->saveXML());

			$zip->addFromString("content.xml", $xml->saveXML());
			$zip->close();
        }
?>