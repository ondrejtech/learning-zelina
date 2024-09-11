<?php


// function
function ____app_css_file_manager($file_source, $file_target, $get_source) {

$json_data = file_get_contents($file_source);
$data_output = "";
$pattern = "/cache=x/";
$replacement = "cache=" . time();

$json_data_replace = preg_replace($pattern, $replacement, $json_data);
$json_data_decode = json_decode($json_data_replace);

foreach($json_data_decode as $key => $value) {

if(!in_array($key, $get_source, true)) {
$data_output .= "";
}

else {
$data_output .= $value . "\n";
}

}

/*
$handle = fopen($file_target, "w");
fwrite($handle, $data_output);
fclose($handle);
*/

file_put_contents($file_target, $data_output);

}
// end function


//FUNC;
function ____app_js_file_manager($file_source, $file_target, $get_sources_header, $get_sources_footer) {

$json_data = file_get_contents($file_source);
$json_data_decode = json_decode($json_data, true);

$js_array_head = "var js_array_head = [ \n";
$js_array_footer = "var js_array_footer = [ \n";

$gsh = $get_sources_header;
array_pop($get_sources_header);

$gsf = $get_sources_footer;
array_pop($get_sources_footer);

// LOOP;
foreach($json_data_decode as $key => $value) {

// HEADER;
if(!in_array($key, $get_sources_header, true)) {
$js_array_head .= "";
}

else {	
$js_array_head .="'" . APP_RES_PATH . "{$value}?cache=" .time() . "',\n";	
}
// END HEADER;

// FOOTER;
if(!in_array($key, $get_sources_footer, true)) {
$js_array_footer .= "";
}

else {	
$js_array_footer .="'" . APP_RES_PATH . "{$value}?cache=" .time() . "',\n";	
}
// END FOOTER;

}
// END LOOP;

$h = end($gsh);
$hh = $json_data_decode[$h];
$hhh = APP_RES_PATH . $hh;

$f = end($gsf);
$ff = $json_data_decode[$f];
$fff = APP_RES_PATH . $ff;


$js_array_head .= "'{$hhh}?cache=" . time() ."'";
$js_array_head .= "\n];\n\n";

$js_array_footer .= "'{$fff}?cache=" . time() ."'";
$js_array_footer .= "\n];\n\n";


file_put_contents($file_target, "");

$file_target_content_2 = file_get_contents($file_target);
file_put_contents($file_target, $js_array_footer . $file_target_content_2);

$file_target_content = file_get_contents($file_target);
file_put_contents($file_target, $js_array_head . $file_target_content);

}
// END FUNC;



?>