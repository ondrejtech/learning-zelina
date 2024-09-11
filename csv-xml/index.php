<?php

$GLOBALS["csv_xml"]["file_csv_input"] = "data-csv.csv";
$GLOBALS["csv_xml"]["file_xml_output"] = "data-xml.xml";
$GLOBALS["csv_xml"]["file_csv_handler"] = @fopen($GLOBALS["csv_xml"]["file_csv_input"], "r");
$GLOBALS["csv_xml"]["data_csv"] = array();
$GLOBALS["csv_xml"]["xml_tree"] = null;

// function 
function ____app_data_csv____parser() {
	
// while
while($data_csv_buffer = fgetcsv($GLOBALS["csv_xml"]["file_csv_handler"], 1000, ",")) {
array_push($GLOBALS["csv_xml"]["data_csv"], $data_csv_buffer);
}
// end while

$csv_header = $GLOBALS["csv_xml"]["data_csv"][0];
array_shift($GLOBALS["csv_xml"]["data_csv"]);

}
// end function


// function 
function ____app_data_xml____generator() {

$dom = new DOMDocument("1.0", "utf-8");
$element = array();

$element["shop"] = $dom->createElement("SHOP");
$dom->appendChild($element["shop"]);

// foreach
foreach($GLOBALS["csv_xml"]["data_csv"] as $item) {

$element["shop_item"] = $dom->createElement("SHOP_ITEM");

$element["product_id"] = $dom->createElement("PRODUCT_ID", $item[0]);
$element["shop_item"]->appendChild($element["product_id"]);

$element["product_name"] = $dom->createElement("PRODUCT_NAME", $item[1]);
$element["shop_item"]->appendChild($element["product_name"]);

$element["product_price"] = $dom->createElement("PRODUCT_PRICE", $item[2]);
$element["shop_item"]->appendChild($element["product_price"]);


$element["shop"]->appendChild($element["shop_item"]);

}
// end foreach

$GLOBALS["csv_xml"]["xml_tree"] = $dom->saveXML();

}
// end function


// function
function ____app_data_csv____inserter() {

$file_xml = fopen($GLOBALS["csv_xml"]["file_xml_output"], "w");
fwrite($file_xml, $GLOBALS["csv_xml"]["xml_tree"]);

fclose($GLOBALS["csv_xml"]["file_csv_handler"]);

}
// end function

____app_data_csv____parser();
____app_data_xml____generator();
____app_data_csv____inserter();


?>