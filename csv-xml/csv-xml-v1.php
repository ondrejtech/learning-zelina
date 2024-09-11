<?php


$file_csv = @fopen("data-csv.csv", "r");

$data_csv = array();

while($data_csv_buffer = fgetcsv($file_csv, 1000, ",")) {
$file_csv_column = count($data_csv_buffer);

array_push($data_csv, $data_csv_buffer);

}

$csv_header = $data_csv[0];
array_shift($data_csv);


$dom = new DOMDocument("1.0", "utf-8");
$element = array();

$element["shop"] = $dom->createElement("SHOP");
$dom->appendChild($element["shop"]);

foreach($data_csv as $item) {

$element["shop_item"] = $dom->createElement("SHOP_ITEM");

$element["product_id"] = $dom->createElement("PRODUCT_ID", $item[0]);
$element["shop_item"]->appendChild($element["product_id"]);

$element["product_name"] = $dom->createElement("PRODUCT_NAME", $item[1]);
$element["shop_item"]->appendChild($element["product_name"]);

$element["product_price"] = $dom->createElement("PRODUCT_PRICE", $item[2]);
$element["shop_item"]->appendChild($element["product_price"]);


$element["shop"]->appendChild($element["shop_item"]);

}	

$data = $dom->saveXML();

$file_xml = fopen("data-xml.xml", "w");
fwrite($file_xml, $data);

fclose($file_csv);


?>