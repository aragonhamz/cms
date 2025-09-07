<?php
ob_start();
	session_start();
	$database = 'ecom';
	$host = 'localhost';
	$username ='root';
	$password = '';
	$con = new mysqli($host, $username, $password, $database);
	if($con->connect_error){
		die('Connection failed'. mysqli_connect_error());
	}

	// define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/php/ecom/');
	// define('SITE_PATH','http://127.0.0.1/php/ecom/');
	// define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
	// define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'media/product/');
?>