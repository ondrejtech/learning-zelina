<?php


if(isset($_POST["app_test"])) {

echo json_encode($_POST['app_test']);
exit();

}


?>
<!DOCTYPE html>
<html lang="cs">
<head>

</head>

<body>

<p class="data"></p>

<script>


var data = 'hello world';

// function
function ____app_xhr_form_data_submit() {

var d = document;

var xhr = new XMLHttpRequest();
var dom_data = d.querySelector(".data");

// function
function ____app_xhr_form_data_submit____trigger() {
var app_form_data = new FormData();

app_form_data.append("app_test", data);

xhr.open("POST", window.location.href, true);
xhr.send(app_form_data);

}
// end function


// function
function ____app_xhr_form_data_submit____response(event) {
if(event.target.readyState == 4 && event.target.status == 200) {

var app_xhr_response = JSON.parse(event.target.responseText);

dom_data.innerHTML = app_xhr_response;

}
}
// end function

xhr.addEventListener("readystatechange", ____app_xhr_form_data_submit____response);
window.addEventListener("load", ____app_xhr_form_data_submit____trigger);

}
// end function

____app_xhr_form_data_submit();


</script>

</body>

</html>