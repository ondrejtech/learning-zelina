<?php

$byte_array = unpack('C*', 'hello world');

foreach($byte_array as $item) {

echo $item . ",";

}



?>