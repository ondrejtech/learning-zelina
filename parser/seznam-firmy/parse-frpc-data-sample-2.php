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




/*
var data_json = JSON.parse(data);

for(var i in data_json) {

var item = btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob(data_json[3]))))));
buffer.push(item);

}
*/

var data_json = JSON.parse(data);

var item = btoa(unescape(encodeURIComponent(JSON.stringify(JAK.FRPC.parse(JAK.Base64.atob(data_json[3]))))));
buffer.push(item);

document.write(JSON.stringify(buffer));








</script>

</body>
</html>