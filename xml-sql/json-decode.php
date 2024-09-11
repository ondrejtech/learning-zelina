<?php

$app_data = '{"0":"https:\/\/c.edsystem.cz\/IMGCACHE\/_534\/534306_0a_3.jpg<\/URL>","1":"https:\/\/c.edsystem.cz\/IMGCACHE\/_534\/534306_0b_3.jpg<\/URL>","2":"https:\/\/c.edsystem.cz\/IMGCACHE\/_534\/534306_0c_3.jpg<\/URL>"}';


$app_json_data = json_decode($app_data, true);

echo $app_json_data[0];



?>