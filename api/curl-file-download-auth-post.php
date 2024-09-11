<?php

//http://private.ws.cz.elinkx.biz/service.asmx/getProductCommodityList
$http_url = "http://private.ws.cz.elinkx.biz/service.asmx/getProductCategoryList";
//$http_url = "http://private.ws.cz.elinkx.biz/service.asmx/getProductCatalogueFullNavFilterDownloadXML";


// cilovy nazev souboru
$file_name = "data-category-list-post.xml";

// fopen automaticky generuje neexistujici soubour v adresari, neni potreba ho vytvaret manualne
$file_output = fopen($file_name, 'w+');



// inicializace curl
$curl = curl_init();

// incializace url
curl_setopt($curl, CURLOPT_URL, $http_url);
curl_setopt($curl, CURLOPT_POST, 1);
//curl_setopt($curl, CURLOPT_POSTFIELDS,"login=info@interierovysortiment.cz&password=Zelio_6236&onStock=false&navigatorFilter=52");

curl_setopt($curl, CURLOPT_POSTFIELDS,"login=info@interierovysortiment.cz&password=Zelio_6236");

// casovy limit stahovani souboru v sekundach
curl_setopt($curl, CURLOPT_TIMEOUT, 300);

// prevod dat do ciloveho soubouru + nasledovani presmerovani 
curl_setopt($curl, CURLOPT_FILE, $file_output); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);



// curl odezva a ukonceni
curl_exec($curl); 
curl_close($curl);
fclose($file_output);


?>