<?php


$data = '{
"0": "hello",
"1": "javascript"
}';


$data_decode = json_decode($data, true);

echo $data_decode[0];


?>