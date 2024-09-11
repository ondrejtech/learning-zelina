<?php

$REMOTE_HOSTNAME = "";
$LOCAL_HOSTNAME = "http://localhost/app/system/";

$REMOTE_STORAGE_SERVER = "";
$LOCAL_STORAGE_SERVER = "http://localhost/app/system/upload/";


$localhost_list = array(
"127.0.0.1",
"::1"
);

if(!in_array($_SERVER["REMOTE_ADDR"], $localhost_list)) {

define("APP_PATH", "../app/");
define("APP_RES_PATH", "{$REMOTE_HOSTNAME}");
define("APP_INCLUDE_PATH", "");
define("APP_STORAGE_PATH", "{$REMOTE_STORAGE_SERVER}");

}

else {

define("APP_PATH", "../app/");
define("APP_RES_PATH", "{$LOCAL_HOSTNAME}");
define("APP_INCLUDE_PATH", "");
define("APP_STORAGE_PATH", "{$LOCAL_STORAGE_SERVER}");

}


?>