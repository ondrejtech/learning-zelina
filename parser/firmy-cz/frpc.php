<?php

require_once("simple-html-dom.php");


$option = array(

"http" => array(
"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36 \r\n"
)

);

$context = stream_context_create($option);

$dom = file_get_html("https://www.firmy.cz/detail/13001695-iswap-cz-praha-vokovice.html", false, $context);
$dom_javascript = $dom->find("script[id=revivalSettings]", 0)->innertext;

preg_match('/\$IMA.Cache = {(.*?)\}};/s', $dom_javascript, $match);
$data_json = "{" . $match[1] . "}}";
$data_json_result = json_decode($data_json, true);

$data_key = array_keys($data_json_result);
//print_r($data_key);

$data_output = $data_json_result[$data_key[3]]["value"]["body"];
$data_output_result = str_replace(array("\r", "\n"), '', $data_output);


//echo $data_output_result;

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

