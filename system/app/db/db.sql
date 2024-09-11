

/*

----------------------------------------------------------------------------
product_data:

{
"product_description": "<h>hello world</h1>",
"product_property": {
"hmotnost": "2,2kg"	
}

}
----------------------------------------------------------------------------

----------------------------------------------------------------------------
product_doc:

[
"xxx.pdf",
"xxx.pdf"
]
----------------------------------------------------------------------------

----------------------------------------------------------------------------
product_gallery:

[
"aaa.jpg",
"bbb.jpg",
"ccc.jpg"
]
----------------------------------------------------------------------------

----------------------------------------------------------------------------
product_pricing:

{
"purchase_price": "xxx",
"price_without_vat": "xxx",
"price_with_vat": "xxx",
"vat": "xxx",
"discount": "xxx"		
}

----------------------------------------------------------------------------


----------------------------------------------------------------------------
product_attribute:

[
"akce",
"novinka",
"tip",
"vyprodej"
]
----------------------------------------------------------------------------


----------------------------------------------------------------------------
product_seo:

{
"seo_title": "xxx",
"seo_desc": "xxx"
}

----------------------------------------------------------------------------

kontakt na zakaznika
fakturacni adresa
dorucovaci adresa


personal_data_fullname
personal_data_email
personal_data_phone_number


country
currency

shipping
shipping_price
payment
payment_price


billing_address_id
billing_address_vat_number
billing_address_company

billing_address_street
billing_address_house_number
billing_address_city
billing_address_zip_code
billing_address_country

mailing_address_street
mailing_address_house_number
mailing_address_city
mailing_address_zip_code




"order_data": {

"country": "",
"currency": "",

"shipping": "",
"shipping_price": "",
"payment": "",
"payment_price": ""

"personal_data": {
"fullname": "",
"email": "",
"phone_number": ""	
},

"billing_address": {
"company": {
"name": "",
"id": "",
"vat_number": ""	
},
"street": "",
"house_number": "",
"city": "",
"zip_code": "",
"country": ""
},

"mailing_adddress": {
"street": "",
"house_number": "",
"city": "",
"zip_code": ""	
}	

}	


CREATE TABLE IF NOT EXISTS `app_eshop_product_category` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

*/

CREATE DATABASE
`app_technika_pro`
CHARACTER SET
utf8
COLLATE
utf8_general_ci;

CREATE TABLE IF NOT EXISTS `app_manager` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`manager_email` TEXT NOT NULL DEFAULT '',
`manager_hash_password` TEXT NOT NULL DEFAULT '',
`manager_hash_password_prepend` TEXT NOT NULL DEFAULT '',
`manager_hash_password_append` TEXT NOT NULL DEFAULT '',
`manager_hash_password_reset_auth` varchar(255) NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'true',
`modified_at` TEXT NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `app_manager_log` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`manager_public_ip` varchar(255) NOT NULL DEFAULT '',
`manager_agent` TEXT NOT NULL DEFAULT '',
`manager_isp` varchar(255) NOT NULL DEFAULT '',
`manager_country` varchar(255) NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'true',
`modified_at` TEXT NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `app_eshop_product` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`ean_code` varchar(255) NOT NULL DEFAULT '',
`product_name` varchar(255) NOT NULL DEFAULT '',
`product_url_title` varchar(255) NOT NULL DEFAULT '',
`product_data` TEXT NOT NULL DEFAULT '',
`product_doc` TEXT NOT NULL DEFAULT '',
`product_gallery` TEXT NOT NULL DEFAULT '',
`product_category` TEXT NOT NULL DEFAULT '',
`product_pricing` TEXT NOT NULL DEFAULT '',
`product_attribute` TEXT NOT NULL DEFAULT '',
`product_seo` TEXT NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*
product_property => hmotnost,...
product_attribute => tip, vyprodej, novinka, skladem, doruceni do 24 hodin
product_pricing => sleva
*/

CREATE TABLE IF NOT EXISTS `app_eshop_product_category` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`parent_hash_id` varchar(255) NOT NULL DEFAULT '',
`hash_id` varchar(255) NOT NULL DEFAULT '',
`product_category_name` varchar(255) NOT NULL DEFAULT '',
`product_category_url_title` varchar(255) NOT NULL DEFAULT '',
`product_category_desc` TEXT NOT NULL DEFAULT '',
`product_category_image` varchar(255) NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `app_eshop_order` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`order_id` varchar(255) NOT NULL DEFAULT '',
`order_status` varchar(255) NOT NULL DEFAULT '',
`order_data` TEXT NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `app_eshop_order_item` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`order_id` varchar(255) NOT NULL DEFAULT '',		
`order_item_name` varchar(255) NOT NULL DEFAULT '',
`order_item_image` varchar(255) NOT NULL DEFAULT '',
`order_item_quantity` varchar(255) NOT NULL DEFAULT '',
`order_item_pricing` TEXT NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `app_eshop_page` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`eshop_page_name` varchar(255) NOT NULL DEFAULT '',
`eshop_page_url_title` varchar(255) NOT NULL DEFAULT '',
`eshop_page_seo` varchar(255) NOT NULL DEFAULT '',
`eshop_page_content` TEXT NOT NULL DEFAULT '',	
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



CREATE TABLE IF NOT EXISTS `app_eshop_customer` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`customer_email` varchar(255) NOT NULL DEFAULT '',
`customer_hash_password` TEXT NOT NULL DEFAULT '',
`customer_hash_password_prepend` TEXT NOT NULL DEFAULT '',
`customer_hash_password_append` TEXT NOT NULL DEFAULT '',
`customer_hash_password_reset_auth` varchar(255) NOT NULL DEFAULT '',
`customer_data` TEXT NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `app_eshop_customer_order` (
`id` bigint(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`hash_id` varchar(255) NOT NULL DEFAULT '',
`customer_id` varchar(255) NOT NULL DEFAULT '',
`order_id` varchar(255) NOT NULL DEFAULT '',
`row_status` ENUM('true', 'false') NOT NULL DEFAULT 'false',
`modified_at` varchar(255) NOT NULL DEFAULT '',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;