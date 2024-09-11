<?php

session_start();


//unset($_SESSION["product_set"]);

function ____app_shoppin_cart_item_add() {

	if(isset($_POST["product_item_submit"])) {


		$input_data = array(
			"product_id" => $_POST["product_item_submit"],
			"product_title" => $_POST["product_title"][$_POST["product_item_submit"]],
			"product_price_default" => $_POST["product_price"][$_POST["product_item_submit"]],
			"product_price" => $_POST["product_price"][$_POST["product_item_submit"]] * $_POST["product_quantity"][$_POST["product_item_submit"]],
			"product_quantity" => $_POST["product_quantity"][$_POST["product_item_submit"]],
			"product_image" => $_POST["product_image"][$_POST["product_item_submit"]]
		);
	
		$_SESSION["product_set"][$input_data["product_id"]]["product_id"] = $input_data["product_id"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_title"] = $input_data["product_title"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_price_default"] = $input_data["product_price_default"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_price"] = $input_data["product_price"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_quantity"] = $input_data["product_quantity"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_image"] = $input_data["product_image"];

	}

}


function ____app_shoppin_cart_item_delete() {

	if(isset($_POST["product_item_delete"])) {

		unset($_SESSION["product_set"][$_POST["product_item_delete"]]);

	}

}


function ____app_shopping_cart_item_update() {

	if(isset($_POST["product_item_update"])) {

		$input_data = array(
			"product_id" => $_POST["product_item_update"],
			"product_title" => $_POST["product_title"][$_POST["product_item_update"]],
			"product_price_default" => $_POST["product_price_default"][$_POST["product_item_update"]],
			"product_price" => $_POST["product_price_default"][$_POST["product_item_update"]] * $_POST["product_quantity"][$_POST["product_item_update"]],
			"product_quantity" => $_POST["product_quantity"][$_POST["product_item_update"]],
			"product_image" => $_POST["product_image"][$_POST["product_item_update"]]
		);
	
		$_SESSION["product_set"][$input_data["product_id"]]["product_id"] = $input_data["product_id"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_title"] = $input_data["product_title"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_price_default"] = $input_data["product_price_default"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_price"] = $input_data["product_price"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_quantity"] = $input_data["product_quantity"];
		$_SESSION["product_set"][$input_data["product_id"]]["product_image"] = $input_data["product_image"];

			
	}

}

____app_shoppin_cart_item_add();
____app_shoppin_cart_item_delete();
____app_shopping_cart_item_update();


?>

<!DOCTYPE html>
<html lang="cs">

<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<title>nakupni kosik</title>

<link rel="stylesheet" href="app-desktop.css?cache<?php echo time(); ?>" />

</head>

<body>
<main class="app_dom_set">
<div class="app_dom_subset">


<!-- app_block_grid_product_set" -->
<div class="app_block_shopping_cart_set">
<div class="app_block_shopping_cart_subset">

<div class="app_block_shopping_cart_header">
<p>Nakupni kosik:</p>
<span></span>
</div>

<form action="" method="post">

<ul class="app_block_shopping_cart_subset_content">


<?php

if(isset($_SESSION["product_set"])) {

	foreach($_SESSION["product_set"] as $key => $product_item) {

?>

<li>
<div class="app_block_shopping_cart_item">

<div class="app_block_shopping_cart_item_image">
<img src="<?php echo $product_item["product_image"]; ?>" alt="image" />
</div>

<div class="app_block_shopping_cart_item_quantity">
<input type="number" min="1" max="99" name="product_quantity[<?php echo $product_item['product_id']; ?>]" value="<?php echo $product_item["product_quantity"]; ?>" />
<span> ks</span>
</div>

<div class="app_block_shopping_cart_item_title">
<p><?php echo substr($product_item["product_title"], 0, 30) ?> ...</p>
</div>

<div class="app_block_shopping_cart_item_price">
<p><?php echo number_format($product_item["product_price"]) ?>,-</p>
</div>

<div class="app_block_shopping_cart_item_action">

<input type="hidden" name="product_title[<?php echo $product_item['product_id'];  ?>]" value="<?php echo $product_item["product_title"]; ?>" />	
<input type="hidden" name="product_price_default[<?php echo $product_item['product_id'];  ?>]" value="<?php echo $product_item["product_price_default"]; ?>" />	
<input type="hidden" name="product_price[<?php echo $product_item['product_id'];  ?>]" value="<?php echo $product_item["product_price"]; ?>" />	
<input type="hidden" name="product_image[<?php echo $product_item['product_id'];  ?>]" value="<?php echo $product_item["product_image"]; ?>" />

<button type="submit" name="product_item_update" value="<?php echo $product_item["product_id"] ?>" class="app_block_shopping_cart_item_action_update">prepocitat</button>
<button type="submit" name="product_item_delete" value="<?php echo $product_item["product_id"] ?>" class="app_block_shopping_cart_item_action_delete">smazat</button>

</div>

</div>
</li>

<?php

	}

}

?>

</ul>

</form>

</div>
</div>	
<!-- app_block_grid_product_set -->

	
<!-- app_block_grid_product_set" -->
<div class="app_block_grid_product_set">
<div class="app_block_grid_product_subset">

<div class="app_block_grid_product_header">
<p>Produkty:</p>
<span></span>
</div>

<form action="" method="post">

<ul class="app_block_grid_product_subset_content">

<!-- app_block_grid_product_item -->
<li>
<div class="app_block_grid_product_item">

<div class="app_block_grid_product_item_image">
<img src="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=NA668s40t3" alt="image" />
</div>

<div class="app_block_grid_product_item_title">
<p>Asus ROG Zephyrus G14 GA401QC-K2123T Eclipse Gray</p>
</div>

<div class="app_block_grid_product_item_price">
<p>35 990,-</p>
</div>

<div class="app_block_grid_product_item_button">
<input type="hidden" name="product_title[a]" value="Asus ROG Zephyrus G14 GA401QC-K2123T Eclipse Gray" />	
<input type="hidden" name="product_price[a]" value="35990" />	
<input type="hidden" name="product_image[a]" value="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=NA668s40t3" />
<input type="number" min="1" max="99" name="product_quantity[a]" value="1" />
<button type="submit" name="product_item_submit" value="a">koupit
</button>
</div>

</div>
</li>
<!-- end app_block_grid_product_item -->

<!-- app_block_grid_product_item -->
<li>
<div class="app_block_grid_product_item">

<div class="app_block_grid_product_item_image">
<img src="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=DEb32b32b2" alt="image" />
</div>

<div class="app_block_grid_product_item_title">
<p>Kingston FURY 32GB KIT DDR4 3200MHz CL16 Beast Black</p>
</div>

<div class="app_block_grid_product_item_price">
<p>3 690,-</p>
</div>

<div class="app_block_grid_product_item_button">
<input type="hidden" name="product_title[b]" value="Kingston FURY 32GB KIT DDR4 3200MHz CL16 Beast Black" />	
<input type="hidden" name="product_price[b]" value="3690" />	
<input type="hidden" name="product_image[b]" value="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=DEb32b32b2" />
<input type="number" min="1" max="99" name="product_quantity[b]" value="1" />
<button type="submit" name="product_item_submit" value="b">koupit</button>
</div>

</div>
</li>
<!-- end app_block_grid_product_item -->

<!-- app_block_grid_product_item -->
<li>
<div class="app_block_grid_product_item">

<div class="app_block_grid_product_item_image">
<img src="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=OI22fck" alt="image" />
</div>

<div class="app_block_grid_product_item_title">
<p>Nikon Z fc + Z DX 16–50 mm f/3,5–6,3 VR Silver</p>
</div>

<div class="app_block_grid_product_item_price">
<p>29 490,-</p>
</div>

<div class="app_block_grid_product_item_button">
<input type="hidden" name="product_title[c]" value="Nikon Z fc + Z DX 16–50 mm f/3,5–6,3 VR Silver" />	
<input type="hidden" name="product_price[c]" value="29490" />	
<input type="hidden" name="product_image[c]" value="https://cdn.alza.cz/ImgW.ashx?fd=f16&cd=OI22fck" />
<input type="number" min="1" max="99" name="product_quantity[c]" value="1" />
<button type="submit" name="product_item_submit" value="c">koupit</button>
</div>

</div>
</li>
<!-- end app_block_grid_product_item -->


</ul>

</form>

</div>
</div>	
<!-- app_block_grid_product_set -->



</div>
</main>

</body>

</html>