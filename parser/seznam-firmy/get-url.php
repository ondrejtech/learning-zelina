<?php
require_once("simple-html-dom.php");


$option = array(

"http" => array(
"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36 \r\n"
)

);

$context = stream_context_create($option);

$file_json_output = "seznam-firmy-url.json";

$url_init = "https://www.firmy.cz/Elektro-mobily-a-pocitace/Prodejci-spotrebni-elektrotechniky";
$page_int = 1;
$data_url_set = array();


$data_url = $url_init;
$dom = file_get_html($data_url, false, $context);
$dom_link_init = $dom->find("a[class=companyTitle statCompanyDetail]", 0);
$dom_link = $dom->find("a[class=companyTitle statCompanyDetail]");


// condition
if($dom_link_init) {

// foreach
foreach($dom_link as $item) {
//echo $item->href . "<br>";
array_push($data_url_set, $item->href);
}
// end foreach

}

else {
echo "end";	
}
// end condition


// while
while($page_int <= 1) {
$page_int++;

$data_url = $url_init . "?page=" . $page_int;
$dom = file_get_html($data_url, false, $context);
$dom_link_init = $dom->find("a[class=companyTitle statCompanyDetail]", 0);
$dom_link = $dom->find("a[class=companyTitle statCompanyDetail]");

// condition
if($dom_link_init) {

// foreach
foreach($dom_link as $item) {
//echo $item->href . "<br>";
array_push($data_url_set, $item->href);
}
// end foreach

}

else {
break;
}
// end condition

}
// end while

$data_output_buffer = json_encode($data_url_set, JSON_FORCE_OBJECT);
$file_handler = @fopen($file_json_output, "w");


if(@fwrite($file_handler, $data_output_buffer) !== FALSE) {
echo "success";	
}

else {
echo "error";	
}


fclose($file_handler);


?>

<!doctype html>
<html>
<head>
<script src="https://api.mapy.cz/loader.js"></script>
<script>Loader.load()</script>
</head>

<body>



<script>


document.write(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob("<?php echo $data_output_result; ?>"))));


</script>

</body>
</html>

