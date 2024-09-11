<?php

$file_csv = @fopen("data-csv.csv", "r");
$data_csv = array();


while($data_csv_buffer = fgetcsv($file_csv, 1000, ",")) {
array_push($data_csv, $data_csv_buffer);
}

print_r($data_csv);

fclose($file_csv);

/*
$csv = array_map('str_getcsv', file('data-csv.csv'));

print_r($csv);
*/


?>