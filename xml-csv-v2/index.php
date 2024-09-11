<?php

require_once("simple-html-dom.php");

$file_xml_input = "product-category.xml";
$file_csv_output = "product-category.csv";

$data_xml = file_get_html($file_xml_input);
$data_csv = array();

$csv_header = array("category_code", "category_name", "attribute_code", "attribute_name", "is_primary", "filter_operator");
array_push($data_csv, $csv_header);


// function
function ____app_data_xml____parser() {

global 
$file_xml_input,
$file_csv_output,
$data_xml,
$data_csv;

// foreach
foreach($data_xml->find("ProductCategory") as $item) {

$attribute_code = null;
$attribute_name = null;
$is_primary = null;
$filter_operator = null;

if($item->children(2)) {
if($item->children(2)->children(0)) {
if($item->children(2)->children(0)->children(0)) {

$attribute_code = $item->children(2)->children(0)->children(0)->innertext;
$attribute_name = $item->children(2)->children(0)->children(1)->innertext;
$is_primary = $item->children(2)->children(0)->children(2)->innertext;
$filter_operator = $item->children(2)->children(0)->children(3)->innertext;

}
}
}

array_push(
$data_csv,
array(
$item->children(0)->innertext,
$item->children(1)->innertext,
$attribute_code,
$attribute_name,
$is_primary,
$filter_operator
)
);


}
// end foreach

}
// end function


// function 
function ____app_data_csv____inserter() {

global 
$file_xml_input,
$file_csv_output,
$data_xml,
$data_csv;

$file_csv = fopen($file_csv_output, "w");

foreach($data_csv as $item) {
fputcsv($file_csv, $item);
}

fclose($file_csv);

}
// end function

____app_data_xml____parser();
____app_data_csv____inserter();

?>
