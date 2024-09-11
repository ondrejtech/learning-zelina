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

/*
$response = $xml_parser->find('ProductComplete', 0)->children(0)->innertext;
echo $response;
*/


/*
$app_xml_item_count = count($xml_parser->find('ProductComplete'));
echo $app_xml_item_count;
*/

/*
$app_xml_item_count = $xml_parser->find('ProductComplete',0)->children(60)->innertext;
echo $app_xml_item_count;
*/


/*
$a = 0;

while(1) {
$a++;

if( !isset($xml_parser->find('ProductComplete',0)->children($a)->tag) ) {

//echo $a;
break;

}

}
*/



/*
foreach($xml_parser->find('ProductComplete') as $item) {

for($i=0; $i<=$a; $i++) {

	if(isset($item->children($i)->innertext)) {

		echo $item->children($i)->innertext;
		echo "<br>";

	}

	else {
		echo "EMPTY";
		echo "<br>";	
	}
}

echo "---------------------------------------<br><br>";

}
*/




//print_r($app_xml_item_count);



foreach($xml_parser->find('ProductComplete') as $item) {


array_push(
$app_sql_data_buffer,
array(
$item->children(0)->innertext,
$item->children(1)->innertext,
$item->children(2)->innertext,
$item->children(3)->innertext,
$item->children(4)->innertext,
$item->children(5)->innertext,
$item->children(6)->innertext,
$item->children(7)->innertext,
$item->children(8)->innertext,
$item->children(9)->innertext,
$item->children(10)->innertext,
$item->children(11)->innertext,
$item->children(12)->innertext,
$item->children(13)->innertext,
$item->children(14)->innertext,
$item->children(15)->innertext,
$item->children(16)->innertext,
$item->children(17)->innertext,
$item->children(18)->innertext,
$item->children(19)->innertext,
$item->children(20)->innertext,
";"
)
);


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