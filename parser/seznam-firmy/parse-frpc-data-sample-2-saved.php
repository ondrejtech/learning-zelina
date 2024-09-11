<?php
require_once("simple-html-dom.php");

/*
$data = '["aaa","bbb","ccc"]';
print_r(json_decode($data, true));
*/



?>

<!DOCTYPE html>
<html lang="cs">
<head>
<script src="https://api.mapy.cz/loader.js"></script>
<script>Loader.load()</script>
</head>

<body>

<script>


var buffer = [];

var data = `{
"0": "aaa",
"1": "bbb",
"2": "ccc"	
}`;

var data_json = JSON.parse(data);

for(var i in data_json) {
buffer.push(btoa(data_json[i]));
}


//document.write(JSON.stringify(buffer));








</script>

</body>
</html>
