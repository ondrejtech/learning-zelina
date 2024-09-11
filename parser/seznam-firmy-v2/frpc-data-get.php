<?php
require_once("subset/simple-html-dom.php");

function json_validate($data = null) {

json_decode($data);

if(json_last_error() === JSON_ERROR_NONE) {
return 1;	
}

}



$file_json_input = "data/url.json";
$file_json_output = "data/frpc-data.json";

$data_frpc_buffer = array();
$file_json_input_size = filesize($file_json_input);

$data_json = file_get_contents($file_json_input);

if($file_json_input_size == 0) {
echo "file is empty";	
}


if(json_validate($data_json)) {

$data_json_response = json_decode($data_json, true);

// foreach
foreach($data_json_response as $item) {

$option = array(

"http" => array(
"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36 \r\n"
)

);

$context = stream_context_create($option);

$dom = file_get_html($item, false, $context);
$dom_frpc_data = $dom->find("script[id=revivalSettings]", 0)->innertext;

preg_match('/\$IMA.Cache = {(.*?)\}};/s', $dom_frpc_data, $match);
$frpc_data = "{" . $match[1] . "}}";
$frpc_data_decode = json_decode($frpc_data, true);
$frpc_data_decode_key = array_keys($frpc_data_decode);
//print_r($frpc_data_decode_key);


$frpc_data_key_value_length = array_map('strlen', $frpc_data_decode_key);
$frpc_data_key_value_max = array_keys($frpc_data_key_value_length, max($frpc_data_key_value_length));

//echo $frpc_data_key_value_max[0];


$frpc_data_body = $frpc_data_decode[$frpc_data_decode_key[$frpc_data_key_value_max[0]]]["value"]["body"];
$frpc_data_body_result = str_replace(array("\r", "\n"), '', $frpc_data_body);

array_push($data_frpc_buffer, $frpc_data_body_result);


}
// end foreach


//echo json_encode($data_frpc_buffer, JSON_FORCE_OBJECT);



$data_output_buffer = json_encode($data_frpc_buffer, JSON_FORCE_OBJECT);
$file_handler = @fopen($file_json_output, "w");


if(@fwrite($file_handler, $data_output_buffer) !== FALSE) {
echo "success";	
}

else {
echo "error";	
}


fclose($file_handler);


}










/*
foreach($data_json_response as $item) {

echo $item . "<br/>";

}
*/





?>