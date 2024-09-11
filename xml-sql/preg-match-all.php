<?php

/*


<ImageList>
<ProductImage>
<URL>https://c.edsystem.cz/IMGCACHE/_534/534306_0a_3.jpg</URL>
</ProductImage>
<ProductImage>
<URL>https://c.edsystem.cz/IMGCACHE/_534/534306_0b_3.jpg</URL>
</ProductImage>
<ProductImage>
<URL>https://c.edsystem.cz/IMGCACHE/_534/534306_0c_3.jpg</URL>
</ProductImage>
</ImageList>


{
"0": "aaa.jpg",
"1": "bbb.jpg",
"2": "ccc.jpg"	
}



<ProductNavigatorDataList>
<ProductNavigatorData>
<AttributeCode>136</AttributeCode>
<ValueCode>617</ValueCode>
</ProductNavigatorData>
<ProductNavigatorData>
<AttributeCode>137</AttributeCode>
<ValueCode>621</ValueCode>
</ProductNavigatorData>
<ProductNavigatorData>
<AttributeCode>138</AttributeCode>
<ValueCode>624</ValueCode>
</ProductNavigatorData>
</ProductNavigatorDataList>


{

"0": {
"attribute_code": "136",
"value_code": "617"	
},

"1": {
"attribute_code": "137",
"value_code": "621"	
}	

}


<ProductLogisticData>
<typ>JEDN</typ>
<count>1</count>
<weight>0.557</weight>
<length>7</length>
<width>10.5</width>
<height>24</height>
</ProductLogisticData>

<ProductLogisticData>
<typ>PACK</typ>
<count>20</count>
<weight>11.945</weight>
<length>49</length>
<width>35</width>
<height>32</height>
</ProductLogisticData>
</LogisticDataList>

{

"0": {
"typ": "jedn",
"count: "1",
"weight: "10.5",
"length": "7",
"width": "10.5",
"height": "24"
},

"1": {
"typ": "pack",
"count: "20",
"weight: "11.945",
"length": "49",
"width": "35",
"height": "32"
}	

}



*/


$app_xml_data = "
<ProductNavigatorDataList>
<ProductNavigatorData>
<AttributeCode>136</AttributeCode>
<ValueCode>617</ValueCode>
</ProductNavigatorData>
<ProductNavigatorData>
<AttributeCode>137</AttributeCode>
<ValueCode>621</ValueCode>
</ProductNavigatorData>
<ProductNavigatorData>
<AttributeCode>138</AttributeCode>
<ValueCode>624</ValueCode>
</ProductNavigatorData>
</ProductNavigatorDataList>
";

$app_data_output = array();

$app_token_pattern = '/(<AttributeCode[^>]*>(.*?)<\/AttributeCode>)|(<ValueCode[^>]*>(.*?)<\/ValueCode>)/';

//$app_token_pattern = '/(<AttributeCode[^>]*>(.*?)<\/AttributeCode>)/';

$app_token_match_inc = preg_match_all($app_token_pattern, $app_xml_data, $app_token_match_data);

//print_r($app_token_match);
//print_r(array_filter($app_token_match[1]));

echo $app_token_match_inc . " | " . count($app_token_match_data);


$app_token_match_data_length = count($app_token_match_data) - 1;

$app_data = array();

for($i = 0; $i <= $app_token_match_data_length; $i++) {

array_push($app_data, array_filter($app_token_match_data[$i]));

}

print_r($app_data);



/*
for($i = 0; $i < $app_token_match_inc; $i++) {
$a = count(array_filter($app_token_match[$i])) - 1;
}
*/

/*
$cc = count(array_filter($app_token_match[1])) - 1;

$aa = array();

for($k = 0; $k <= $cc; $k++) {
array_push($aa, $k);
}

//print_r($aa);

$res = array_combine($aa, array_filter($app_token_match[1]));

//print_r($res);
*/


/*
for($i = 0; $i < $app_token_match_inc; $i++) {
	$test = array();
	$app_token_match_format = array_filter($app_token_match[1]);

	array_push($test,[$i]);
	$app_data_output[] = $test;
	//$app_data_output[] = $app_token_match[1][$i] . $app_token_match[3][$i];
}
*/

//print_r(array_filter($app_data_output));

//echo json_encode($app_data_output, JSON_FORCE_OBJECT);


/*

for($i = 0; $i < $app_token_match_inc; $i++) {
	$test = array();
	array_push($test, $app_token_match[0][$i]);
	$app_data_output[] = $test;
}


*/






/*
$app_xml_data = "
<AttributeCode>823</AttributeCode>
<ValueCode>10608</ValueCode>
<AttributeCode>823</AttributeCode>
<ValueCode>10608</ValueCode>
";


$app_data_output = array();

$app_token_pattern = '/(<AttributeCode[^>]*>(.*?)<\/AttributeCode>)|(<ValueCode[^>]*>(.*?)<\/ValueCode>)/';


$app_token_match_inc = preg_match_all($app_token_pattern, $app_xml_data, $app_token_match);


for($i = 0; $i < $app_token_match_inc; $i++) {
	$app_data_output[] = $app_token_match[0][$i];
}


print_r($app_data_output);
*/

?>