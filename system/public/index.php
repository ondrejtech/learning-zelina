<?php

require_once("app-path-controller.php");
require_once(APP_PATH . "app-manager.php");

____app_css_file_manager(
APP_INCLUDE_PATH . "res/css/css-file-manager.json",
APP_INCLUDE_PATH . "res/css/css-file-manager.css",
array(
"app_root",
"app_element",
"app_desktop",
"app_mobile"
)
);

____app_js_file_manager(
APP_INCLUDE_PATH . "res/js/js-file-manager.json",
APP_INCLUDE_PATH . "res/js/js-file-manager.js",
array(
"app_icon"	
),
array(
"app_element",
"app_desktop",
"app_mobile"
)
);


?>

<!DOCTYPE html>
<html lang="cs">

<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<title>technika-pro.cz - technicke plyny</title>


<?php
require_once(APP_INCLUDE_PATH . "res/include/app-css-file-manager.php");
?>


<style>

.app_dom_set {
display: block;
padding: 1vw;	
}

.test_a {
color: rgba(var(--app-text-black-dark), 1);
font-family: "app-sans-big-thin";
}

.test_b {
color: rgba(var(--app-text-black-dark), 1);
font-family: "app-sans-small-light";
}

.test_c {
color: rgba(var(--app-text-black-dark), 1);
font-family: "app-sans-small-regular";
}

.test_d {
color: rgba(var(--app-text-black-dark), 1);
font-family: "app-sans-small-medium";
}

.test_e {
color: rgba(var(--app-text-black-dark), 1);
font-family: "app-sans-small-bold";
}

</style>

</head>

<body>
<main class="app_dom_set">


<h1 class="test_a">php application</h1>

</main>

<?php
require_once(APP_INCLUDE_PATH . "res/include/app-js-file-manager.php");
?>

</body>

</html>