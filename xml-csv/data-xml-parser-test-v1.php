<?php

require_once("simple-html-dom.php");

$file_xml_input = "data-xml.xml";

$data_xml = file_get_html($file_xml_input);

echo $data_xml->find("SHOP_ITEM", 0)->children(1)->innertext;


?>