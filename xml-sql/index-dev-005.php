<?php

require_once("simple-html-dom.php");


$app_file = array(
"input" => "data/xml-data.xml",
"output" => "data/sql-data.sql"
);

$app_file_buffer = array();
$app_data_list_output = array();
$app_data_list_header = array();

//$app_file_buffer["input"] = file_get_contents($app_file["input"]);

$app_xml_parser = file_get_html($app_file["input"]);

$app_xml_data__elm_inc = 0;
$app_xml_data__elm_inc_b = 0;
$app_xml_data__elm_children_inc = 0;


$app_xml_data__elm_name__image_list_pattern = array();
$app_xml_data__elm_name__image_list_match = array();

$app_xml_data__elm_name__navigator_data_list_pattern = array();
$app_xml_data__elm_name__navigator_data_list_match = array();

$app_xml_data__elm_name__logistic_data_list_pattern = array();
$app_xml_data__elm_name__logistic_data_list_match = array();



// ____app_xml_data__elm_children_inc
while(1) {
	$app_xml_data__elm_children_inc++;

	if(!isset($app_xml_parser->find('ProductComplete',0)->children($app_xml_data__elm_children_inc)->tag)) {
		break;
	}

}
// end ____app_xml_data__elm_children_inc


// ____app_data_list_output__create
foreach($app_xml_parser->find('ProductComplete') as $item) {
	$app_xml_data__elm_inc++;
}

for($i = 0; $i <= $app_xml_data__elm_inc; $i++) {
	array_push($app_data_list_output, array());
}

array_pop($app_data_list_output);
// end ____app_data_list_output__create



/*
$inc_a = -1;
foreach($xml_parser->find('ProductComplete') as $item) {
$inc_a++;

array_push($xml_navigator_data_list_key, $inc_a);
array_push($xml_navigator_data_list_json, $inc_a);

$children_count = -1;

	while(1) {
		$children_count++;
		if(!isset($xml_parser->find("ProductNavigatorDataList", $inc_a)->children($children_count)->tag)) {
			break;	
		}

	}

$xml_navigator_data_list_key[$inc_a] = $children_count;

}



$inc_b = -1;
foreach($xml_parser->find('ProductNavigatorDataList') as $item) {
$inc_b++;

$json_navigator_data_list = array();

for($i = 0; $i <= $xml_navigator_data_list_key[$inc_b]; $i++) {
	//array_push($json_image_list, trim(strip_tags($item->children($i)->innertext)));

	array_push($json_navigator_data_list, $item->children($i)->innertext);
}	

array_pop($json_navigator_data_list);
$xml_navigator_data_list_json[$inc_b] = json_encode($json_navigator_data_list, JSON_FORCE_OBJECT);
$json_navigator_data_list = array();

}


print_r($xml_navigator_data_list_json);
*/


// IMAGE_LIST
/*
$inc_a = -1;
foreach($xml_parser->find('ProductComplete') as $item) {
$inc_a++;

array_push($xml_image_list_key, $inc_a);
array_push($xml_image_list_json, $inc_a);

$children_count = -1;

	while(1) {
		$children_count++;
		if(!isset($xml_parser->find("ImageList", $inc_a)->children($children_count)->tag)) {
			break;	
		}

	}

$xml_image_list_key[$inc_a] = $children_count;

}



$inc_b = -1;
foreach($xml_parser->find('ImageList') as $item) {
$inc_b++;

$json_image_list = array();

for($i = 0; $i <= $xml_image_list_key[$inc_b]; $i++) {
	array_push($json_image_list, trim(strip_tags($item->children($i)->innertext)));
}	

array_pop($json_image_list);
$xml_image_list_json[$inc_b] = json_encode($json_image_list, JSON_FORCE_OBJECT);
$json_image_list = array();

}


print_r($xml_image_list_json);
*/
// END IMAGE_LIST



// ARRAY_RESPONSE
/*
foreach($xml_parser->find('ProductComplete') as $item) {
$array_key_count++;

	for($j = 0; $j <= $element_children_count; $j++) {

		if(isset($item->children($j)->innertext)) {
			$app_sql_data_buffer[$array_key_count][$j] = $item->children($j)->innertext;
		}

		if($j == 48) {
			$app_sql_data_buffer[$array_key_count][$j] = $xml_image_list_json[$array_key_count];
		}

		if($j == 49) {
			
		}

		if($j == 50) {
			
		}

	}


}


print_r($app_sql_data_buffer);
*/
// END ARRAY_RESPONSE




// SQL_RESPONSE
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
// END SQL_RESPONSE






?>