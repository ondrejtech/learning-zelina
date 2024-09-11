<?php
ini_set('memory_limit', '-1');
header('Content-Encoding: UTF-8');

require_once("simple_html_dom.php");

if(isset($_POST["submit"])){
    $file_xml_input = "xml-data.xml";
    $file_csv_output ="periferie.csv";
    
    $data_xml = file_get_html($file_xml_input);
    $data_csv = array();
    
    $csv_header = array("ProId","Code","Name","waranty","endUserPrice","PartNumber",
    "OnStock","OnStockText","ProducerName","EANCode","descriptionShort","description","img");
    array_push($data_csv, $csv_header);
    
    
    // function
    function ____app_data_xml____parser() {
    
    global 
    $file_xml_input,
    $file_csv_output,
    $data_xml,
    $data_csv;
    
    // foreach
    foreach($data_xml->find("ProductComplete") as $item) {
    
    //$item->children(48)->children(0)->children(0)->innertext
    $img = "";

        if(isset($item->children(48)->children(0)->innertext)) {
                if(isset($item->children(48)->children(0)->children(0)->innertext)) {

                $test = $item->children(48)->children(0)->children(0)->innertext;
                $img = trim(strip_tags($test));

                }
            }

    array_push(
    $data_csv,
    array(
        $item->children(0)->innertext,//proid
        $item->children(1)->innertext,//code
        $item->children(2)->innertext,//name
        $item->children(10)->innertext,//varanty
        $item->children(13)->innertext,//enduserprice
        $item->children(15)->innertext,//partnuber
        $item->children(16)->innertext,//onstock
        $item->children(17)->innertext,//onstocktext
        $item->children(19)->innertext,//Producername
        $item->children(21)->innertext,//eancode
        "",
        "",
        $img
    )
    );
    
    
    }
    // end foreach
    
    print_r($data_csv);
    
    }
    // end function
    
    
    // function 
    function ____app_data_csv____inserter() {
    
    global 
    $file_xml_input,
    $file_csv_output,
    $data_xml,
    $data_csv;
    
    $file_csv = fopen($file_csv_output, "w");
    
    foreach($data_csv as $item) {
    fputcsv($file_csv, $item);
    }
    
    fclose($file_csv);
    
    }
    // end function
    
    ____app_data_xml____parser();
    ____app_data_csv____inserter();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="submit" value="Stahnout CSV" name="submit">
    </form>
</body>
</html>
