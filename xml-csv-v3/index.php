<?php

require_once("simple-html-dom.php");

$file_xml_input = "data-category-list.xml";
$file_csv_output = "data-category-list.csv";

$data_xml = file_get_html($file_xml_input);
$data_csv = array();

$csv_header = array("SuperCategoryCode","SuperCategoryName","ParentSuperCategoryCode");
array_push($data_csv, $csv_header);


// function
function ____app_data_xml____parser() {

global 
$file_xml_input,
$file_csv_output,
$data_xml,
$data_csv;

// foreach
foreach($data_xml->find("ProductSuperCategory") as $item) {

array_push(
$data_csv,
array(
	$item->children(1)->innertext,
	$item->children(2)->innertext,
	$item->children(3)->innertext
)
);


}
// end foreach

print_r($data_csv);

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
