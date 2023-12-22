<?php
session_start();
date_default_timezone_set('Asia/Calcutta');
$date_time = date('Y-m-d H:i:s');
// Attempt to create a PDO connection
$con = mysqli_connect("localhost", "root", "", "ecom");

// Check if the connection was successful
// if ($pdo_conn) {
//     echo "Connected to the database successfully!";
// } else {
//     // Connection failed
//     echo "Connection failed!";
// }
define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'] . '/sapan/ecom/');
define('SITE_PATH', 'http://127.0.0.1/sapan/ecom/');

define('PRODUCT_IMAGE_SERVER_PATH', SERVER_PATH . 'media/product/');
define('PRODUCT_IMAGE_SITE_PATH', SITE_PATH . 'media/product/');

define('PRODUCT_MULTIPLE_IMAGE_SERVER_PATH', SERVER_PATH . 'media/product_images/');
define('PRODUCT_MULTIPLE_IMAGE_SITE_PATH', SITE_PATH . 'media/product_images/');

define('BANNER_SERVER_PATH', SERVER_PATH . 'media/banner/');
define('BANNER_SITE_PATH', SITE_PATH . 'media/banner/');

define('SHIPROCKET_TOKEN_EMAIL','sapansathawara7@gmail.com');
define('SHIPROCKET_TOKEN_PASSWORD','password');