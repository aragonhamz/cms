<?php
require('connection.inc.php');
$color ='';
$msg = '';
if(isset($_GET['type']) && $_GET['type']!=''){
	$type=get_safe_value($con,$_GET['type']);
	if($type=='status'){
		$operation=get_safe_value($con,$_GET['operation']);
		$id=get_safe_value($con,$_GET['id']);
		if($operation=='activate'){
			$status='1';
		}else{
			$status='0';
		}
		$update_status_sql="update users set status='$status' where id='$id'";
		//mysqli_query($con,$update_status_sql);
		if($con->query($update_status_sql)==true){
			$color = 'success';
			$msg = "User has been '". $operation . "' successfully";
		}else{
			$msg = "Error '". $operation. "' user";
			$color = 'danger';
		}
	}
	
	if($type=='delete'){
		$id=get_safe_value($con,$_GET['id']);
		$delete_sql="delete from coupon_master where id='$id'";
		// if($con->query($delete_sql)==true){
		// 	$color = 'success';
		// 	$msg = "User has been deleted successfully";
		// }else{
		// 	$msg = "Error deleting user";
		// 	$color = 'danger';
		// }
	}
}
?>