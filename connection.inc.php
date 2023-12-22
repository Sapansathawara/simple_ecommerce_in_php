<?php
    session_start();
    date_default_timezone_set('Asia/Calcutta');
    $date_time = date('Y-m-d H:i:s');
	$con = mysqli_connect("localhost","root","","ecom");

    // Check if the connection was successful
    // if ($pdo_conn) {
    //     echo "Connected to the database successfully!";
    // } else {
    //     // Connection failed
    //     echo "Connection failed!";
    // }
    define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/sapan/ecom/');
    define('SITE_PATH','http://127.0.0.1/sapan/ecom/');

    define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
    define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'media/product/');

    define('PRODUCT_MULTIPLE_IMAGE_SERVER_PATH', SERVER_PATH . 'media/product_images/');
    define('PRODUCT_MULTIPLE_IMAGE_SITE_PATH', SITE_PATH . 'media/product_images/');

    define('BANNER_SERVER_PATH', SERVER_PATH . 'media/banner/');
    define('BANNER_SITE_PATH', SITE_PATH . 'media/banner/');

    define('SMTP_EMAIL','sapansathawara4@gmail.com');
    define('SMTP_PASSWORD','rtvwsoqjijefdmxf');
    define('SMS_KEY','');

    //instamojo payment gateway nu implement kam aapde haju karyu nathi etle aa work karavu nu nathi, khali dhyan ma rahe etle define kari ne rakhyu che (atiyare razerpay nu implement kari ne rakhu che pan work nathi kartu kem ke code ma problem che)
    define('INSTAMOJO_KEY','key');
    define('INSTAMOJO_TOKEN','token');
?>