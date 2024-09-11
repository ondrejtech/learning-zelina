<?php



if(isset($_POST["app_form_data_input"])) {

echo $_POST["app_form_data_input"];
exit();


}


?>

<!DOCTYPE html>
<html lang="cs">

<head>

<title>AJAX</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" /> 

</head>


<body>
<main>

<input type="text" value="bitch" class="app_form_data_input" />
<br />
<button type="button" class="app_sign_up_form_data_button">submit data</button> 

<br/>

<div class="app_form_data_response"></div>


<script>


var d = document;


function ____app_xhr_form_data() {

	var xhr = new XMLHttpRequest();
	var app_form_data_input = d.querySelector(".app_form_data_input");
	var app_sign_up_form_data_button = d.querySelector(".app_sign_up_form_data_button");
	var app_form_data_response = d.querySelector(".app_form_data_response");


	function ____app_xhr_form_data__submit(event) {
		event.preventDefault();
		var form_data = new FormData();
		form_data.append("app_form_data_input", app_form_data_input.value);

		xhr.open("POST", window.location.href, true);
		xhr.send(form_data);
	}	


	function ____app_xhr_form_data__response(event) {
		if(event.target.readyState == 4 && event.target.status == 200) {
			app_form_data_response.innerHTML = event.target.responseText;
		}
	}


	app_sign_up_form_data_button.addEventListener("click", ____app_xhr_form_data__submit);
	xhr.addEventListener("readystatechange", ____app_xhr_form_data__response);


}


____app_xhr_form_data();


</script>

</main>
</body>

</html>