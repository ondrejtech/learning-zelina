<?php

require_once("simple-html-dom.php");


$app_file = array(
"input" => "data/xml-data.xml",
"output" => "data/sql-data.sql"
);

$app_file_buffer = array();
$app_sql_data_buffer = array();
$app_sql_col = array();

$app_file_buffer["input"] = file_get_contents($app_file["input"]);
$xml_parser = file_get_html($app_file["input"]);


$array_key_count = -1;
$element_count = 0;
$element_children_count = 0;


while(1) {
	$element_children_count++;

	if(!isset($xml_parser->find('ProductComplete',0)->children($element_children_count)->tag)) {
		break;
	}

}


foreach($xml_parser->find('ProductComplete') as $item) {
	$element_count++;
}




for($i=0; $i<=$element_count; $i++) {

array_push(
$app_sql_data_buffer, array()
);

}

array_pop($app_sql_data_buffer);



$test = array();

foreach($xml_parser->find('ProductComplete') as $item) {

$temp = array();

	for($j = 0; $j <= $element_children_count; $j++) {

		array_push($temp, $item->children($j)->tag);

	}

array_push($test, $temp);

$temp = array();

}


print_r($test);



/*
foreach($xml_parser->find('ProductComplete') as $item) {
$array_key_count++;

	for($j = 0; $j <= $element_children_count; $j++) {

		if(isset($item->children($j)->innertext)) {
			$app_sql_data_buffer[$array_key_count][$j] = $item->children($j)->innertext;
		}

	}


}


print_r($app_sql_data_buffer);
*/




/*
$test = $app_sql_col[0];

$comma = implode(", ", $test);


echo "INSERT INTO table_name ({$comma}) VALUES";
echo "<br>";

foreach($app_sql_data_buffer as $item) {

$res = count($item) - 1;

echo "('{$item[0]}', '{$item[1]}', '{$item[2]}'){$item[$res]}";
echo "<br>";


}
*/







?>