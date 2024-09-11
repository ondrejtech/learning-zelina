<?php
require_once("subset/simple-html-dom.php");

$file_json_input = "data/frpc-data-decoded.json";
$file_csv_output = "data/csv-data.csv";

$data_csv = array();

$csv_header = array("url", "title", 'business title', "ic", 'email', 'phone', 'address', 'description');
array_push($data_csv, $csv_header);

$data_json = file_get_contents($file_json_input);

$data_decode_json = json_decode($data_json, true);

foreach($data_decode_json as $item) {

$data_decode = base64_decode($item);
$data_decode_body = json_decode($data_decode, true);


$csv = array();

if(isset($data_decode_body[0]["result"]["urls_all"][0]["baseUrl"])) {
$csv["url"] = $data_decode_body[0]["result"]["urls_all"][0]["baseUrl"];
}

else {
$csv["url"] = "";
}


if(isset($data_decode_body[0]["result"]["title_alternative"])) {
$csv["title"] = $data_decode_body[0]["result"]["title_alternative"];
}

else {
$csv["title"] = "";
}


if(isset($data_decode_body[0]["result"]["title"])) {
$csv["business_title"] = $data_decode_body[0]["result"]["title"];
}

else {
$csv["business_title"] = "";
}


if(isset($data_decode_body[0]["result"]["subject_ic"])) {
$csv["ic"] = $data_decode_body[0]["result"]["subject_ic"];
}

else {
$csv["ic"] = "";
}


if(isset($data_decode_body[0]["result"]["emails_obj"][0]["email"])) {
$csv["email"] = $data_decode_body[0]["result"]["emails_obj"][0]["email"];
}

else {
$csv["email"] = "";
}


if(isset($data_decode_body[0]["result"]["phones_obj"][0]["number"])) {
$csv["phone"] = "+" . $data_decode_body[0]["result"]["phones_obj"][0]["country_code"] . " " . $data_decode_body[0]["result"]["phones_obj"][0]["number"];
}

else {
$csv["phone"] = "";
}

if(isset($data_decode_body[0]["result"]["address"])) {
$csv["address"] = $data_decode_body[0]["result"]["address"];
}

else {
$csv["address"] = "";
}

if(isset($data_decode_body[0]["result"]["description"])) {
$csv["description"] = $data_decode_body[0]["result"]["description"];
}

else {
$csv["description"] = "";
}


array_push(
$data_csv,
array(
$csv["url"],
$csv["title"],
$csv["business_title"],
$csv["ic"],
$csv["email"],
$csv["phone"],
$csv["address"],
$csv["description"]
)
);



/*
array_push(
$data_csv,
array(
$data_decode_body[0]["result"]["urls_all"][0]["baseUrl"],
$data_decode_body[0]["result"]["title_alternative"],
$data_decode_body[0]["result"]["title"],
$data_decode_body[0]["result"]["subject_ic"],
$data_decode_body[0]["result"]["emails_obj"][0]["email"],
"+" . $data_decode_body[0]["result"]["phones_obj"][0]["country_code"] . " " . $data_decode_body[0]["result"]["phones_obj"][0]["number"],
$data_decode_body[0]["result"]["address"],
$data_decode_body[0]["result"]["description"]
)
);
*/

/*
echo $data_decode_body[0]["result"]["title_alternative"];
echo "<br>";
*/

}

$file_csv = fopen($file_csv_output, "w");

foreach($data_csv as $item) {
fputcsv($file_csv, $item);
}

fclose($file_csv);


/*

echo $data_decode_body[0]["result"]["title_alternative"];
echo "<br/>";
echo $data_decode_body[0]["result"]["address"];
echo "<br/>";
echo $data_decode_body[0]["result"]["subject_ic"];
echo "<br/>";
echo $data_decode_body[0]["result"]["urls_all"][0]["baseUrl"];
echo "<br/>";
echo $data_decode_body[0]["result"]["title"];
echo "<br/>";
echo "+" . $data_decode_body[0]["result"]["phones_obj"][0]["country_code"] . " " . $data_decode_body[0]["result"]["phones_obj"][0]["number"];
echo "<br/>";
echo $data_decode_body[0]["result"]["emails_obj"][0]["email"];
echo "<br />";
echo $data_decode_body[0]["result"]["description"];

*/



?>