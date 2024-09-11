<?php
require_once("subset/simple-html-dom.php");

// function
function json_validate($data = null) {

json_decode($data);

if(json_last_error() === JSON_ERROR_NONE) {
return 1;	
}

}
// end function


$file_json_input = "data/frpc-data.json";
$file_json_output = "data/frpc-data-decoded.json";

$data_frpc_buffer = array();
$file_json_input_size = filesize($file_json_input);

$data_json = file_get_contents($file_json_input);

if($file_json_input_size == 0) {
echo "file is empty";	
}


$data_json_response = json_decode($data_json, true);



if(isset($_POST["app_data"])) {

//echo $_POST["app_data"];


$data_output_buffer = $_POST["app_data"];
$file_handler = @fopen($file_json_output, "w");


if(@fwrite($file_handler, $data_output_buffer) !== FALSE) {
echo "success";	
exit();
}

else {
echo "error";	
exit();
}


fclose($file_handler);


/*
$data = json_decode($_POST["app_test"], true);

// loop
foreach($data as $item) {

echo $item;
echo "<br>";

}
// end loop
*/


}



?>
<!DOCTYPE html>
<html lang="cs">
<head>
<script src="https://api.mapy.cz/loader.js"></script>
<script>Loader.load()</script>
</head>

<body>

<p class="data"></p>

<script>

/*
document.write(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob())));
btoa(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob())))
*/

/*
document.write(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob("<?php echo $data_json_response[0]; ?>"))));

document.write(window.btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob("<?php echo $data_json_response[0]; ?>")))))));
document.write(decodeURIComponent(escape(atob("base64"))));

*/



/*
var frpc_data = '<?php echo $data_json; ?>';
var frpc_data_decode = JSON.parse(frpc_data);

document.write(window.btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob(frpc_data_decode[0])))))));
*/

/*

var frpc_data = '<?php echo $data_json; ?>';
var frpc_data_decode = JSON.parse(frpc_data);

for(var i in frpc_data_decode) {

document.write(window.btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob(frpc_data_decode[i])))))));
document.write("<br/>");

}

*/


var data_buffer = [];
var frpc_data = '<?php echo $data_json; ?>';
var frpc_data_decode = JSON.parse(frpc_data);

for(var i in frpc_data_decode) {

var item = window.btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob(frpc_data_decode[i]))))));

data_buffer.push(item);

}


//document.write(JSON.stringify(data_buffer));

var data = JSON.stringify(data_buffer);

// function
function ____app_xhr_form_data_submit() {

var d = document;

var xhr = new XMLHttpRequest();
var dom_data = d.querySelector(".data");

// function
function ____app_xhr_form_data_submit____trigger() {
var app_form_data = new FormData();

app_form_data.append("app_data", data);

xhr.open("POST", window.location.href, true);
xhr.send(app_form_data);

}
// end function


// function
function ____app_xhr_form_data_submit____response(event) {
if(event.target.readyState == 4 && event.target.status == 200) {

//var app_xhr_response = JSON.parse(event.target.responseText);
var app_xhr_response = event.target.responseText;

dom_data.innerHTML = app_xhr_response;

}
}
// end function

xhr.addEventListener("readystatechange", ____app_xhr_form_data_submit____response);
window.addEventListener("load", ____app_xhr_form_data_submit____trigger);

}
// end function

____app_xhr_form_data_submit();

//document.write(JSON.stringify(data_buffer));


</script>

</body>

</html>