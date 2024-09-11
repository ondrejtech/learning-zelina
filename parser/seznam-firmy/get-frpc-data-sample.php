<?php
require_once("simple-html-dom.php");


$option = array(

"http" => array(
"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36 \r\n"
)

);

$context = stream_context_create($option);

$dom = file_get_html("https://www.firmy.cz/detail/13330100-mi-band-cz-usti-nad-labem-centrum.html", false, $context);
$dom_frpc_data = $dom->find("script[id=revivalSettings]", 0)->innertext;

preg_match('/\$IMA.Cache = {(.*?)\}};/s', $dom_frpc_data, $match);
$frpc_data = "{" . $match[1] . "}}";
$frpc_data_decode = json_decode($frpc_data, true);
$frpc_data_decode_key = array_keys($frpc_data_decode);

$frpc_data_body = $frpc_data_decode[$frpc_data_decode_key[3]]["value"]["body"];
$frpc_data_body_result = str_replace(array("\r", "\n"), '', $frpc_data_body);


?>

<!DOCTYPE html>
<html lang="cs">
<head>
<script src="https://api.mapy.cz/loader.js"></script>
<script>Loader.load()</script>
</head>

<body>

<script>

document.write(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob("<?php echo $frpc_data_body_result; ?>"))));




btoa(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob())))


</script>

</body>
</html>
