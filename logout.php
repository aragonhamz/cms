<?php
ob_start();
session_start();
if(isset($_SESSION['user_id'])) {
	session_destroy();
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_email']);
	unset($_SESSION['user_mobile']);
	unset($_SESSION['user_role']);
	header('location:index.php');
	die();
	
} else {
	header("Location: login.php");
}
?>