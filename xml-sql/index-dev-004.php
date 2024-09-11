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


/*

<productnavigatordata>        
	<attributecode>136</attributecode>
	<valuecode>617</valuecode>
</productnavigatordata>

<productnavigatordata>
 	<attributecode>137</attributecode>
 	<valuecode>621</valuecode>    
</productnavigatordata>        

<productnavigatordata>         
	<attributecode>138</attributecode>        
	<valuecode>624</valuecode>        
</productnavigatordata>  


*/


$xml_image_list_key = array();
$xml_image_list_json = array();

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


//print_r($xml_image_list_json);



foreach($xml_parser->find('ProductComplete') as $item) {
$array_key_count++;

	for($j = 0; $j <= $element_children_count; $j++) {

		if(isset($item->children($j)->innertext)) {
			$app_sql_data_buffer[$array_key_count][$j] = $item->children($j)->innertext;
		}

		if($j == 48) {
			$app_sql_data_buffer[$array_key_count][$j] = $xml_image_list_json[$array_key_count];
		}

	}


}


print_r($app_sql_data_buffer);




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