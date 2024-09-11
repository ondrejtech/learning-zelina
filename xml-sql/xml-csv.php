<?php

ini_set("memory_limit", "-1");


require_once("simple-html-dom.php");


$app_file = array(
"input" => "data/xml-product.xml",
"output" => "data/xml-csv.csv"
);

$app_file_buffer = array();
$app_data_list_buffer = array();
$app_data_list_header = array();


$app_xml_parser = file_get_html($app_file["input"]);

$app_data_list__inc = -1;
$app_data_list__item_children_inc = -1;

$app_data_list__response_inc = -1;
$app_data_list__image_list_inc = -1;
$app_data_list__image_list_b_inc = -1;


$app_data_list__image_list_pattern = array();
$app_data_list__image_list_match = array();

$app_data_list__navigator_data_list_pattern = array();
$app_data_list__image_list_match = array();


$app_data_list__logistic_data_list_pattern = array();
$app_data_list__logistic_data_list_match = array();





//echo $app_xml_parser->find('imageList',3)->innertext;


// ____app_data_list__children_count
while(1) {
	$app_data_list__item_children_inc++;

	if(!isset($app_xml_parser->find('ProductComplete',0)->children($app_data_list__item_children_inc)->tag)) {
		break;
	}

	else {
    array_push($app_data_list_header, $app_xml_parser->find('ProductComplete',0)->children($app_data_list__item_children_inc)->tag);
	}

}
// end ____app_data_list__children_count

//array_push($app_data_list_buffer, $app_data_list_header);

//print_r($app_data_list_header);


// ____app_data_list__item_count
foreach($app_xml_parser->find('ProductComplete') as $item) {
	$app_data_list__inc++;
}

for($i = 0; $i <= $app_data_list__inc; $i++) {
	array_push($app_data_list_buffer, array());
}
// end ____app_data_list__children_count


// ____app_data_list__data_push
foreach($app_xml_parser->find('ProductComplete') as $item) {
$app_data_list__response_inc++;

	for($j = 0; $j <= $app_data_list__item_children_inc; $j++) {

		if(isset($item->children($j)->innertext)) {
			$app_data_list_buffer[$app_data_list__response_inc][$j] = $item->children($j)->innertext;
		}

		if($j == 48) {
	        
			$app_data_output = array();

			if(isset($item->children(48)->children(0)->innertext)) {
				if(isset($item->children(48)->children(0)->children(0)->innertext)) {

				$test = $item->children(48)->children(0)->children(0)->innertext;
				$app_data_list_buffer[$app_data_list__response_inc][$j] = trim(strip_tags($test));

				}
			}

			else {
				$app_data_list_buffer[$app_data_list__response_inc][$j] = "";
			}

		}

		if($j == 49) {
			$app_data_list_buffer[$app_data_list__response_inc][$j] = "";
		}

		if($j == 50) {
			$app_data_list_buffer[$app_data_list__response_inc][$j] = "";
		}

	}


}
// // ____app_data_list__data_push


$set = array();
$tester = array_push($set, $app_data_list_header);

$result = array_merge($set, $app_data_list_buffer);
//print_r($result);


$file_csv = fopen($app_file["output"], "w");

foreach($result as $item) {
fputcsv($file_csv, $item);
}

fclose($file_csv);

echo "SCRIPT FINISHED...";


// SQL_RESPONSE



/*
echo count($app_data_list_buffer) - 1;
echo "<br />";
*/

// str_replace('"', "'", htmlspecialchars($item, ENT_QUOTES, 'UTF-8'))

/*
$app_sql_column = implode(", ", $app_data_list_header);

echo "INSERT INTO test ({$app_sql_column}) VALUES";
echo "<br><br>";

for($i = 0; $i <= $app_data_list__inc; $i++) {

	$app_sql_row = "(";
	$inc = 0;
	
	foreach($app_data_list_buffer[$i] as $item) {
		$inc++;

		if(!($app_data_list__item_children_inc == $inc)) {
			$app_sql_row .= '"' . str_replace('"', "'", $item) . '"' . ",";
		}

		else {
    		$app_sql_row .= '"' . str_replace('"', "'", $item) . '"' . "";
		}


	}

    
    if( !($i == count($app_data_list_buffer) - 1) ) {
    	$app_sql_row .= "),";
    }

    else {
    	$app_sql_row .= ");";
    }

	echo $app_sql_row;
	echo "<br /><br />";

}


/*
foreach($app_data_list_buffer as $item) {

$res = count($item) - 1;


echo "('{$item[0]}', '{$item[1]}', '{$item[2]}'){$item[$res]}";
echo "<br>";


}
*/

// END SQL_RESPONSE






?>