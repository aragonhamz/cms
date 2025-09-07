<?php
require('top.inc.php');
isAdmin();
if(isset($_GET['type']) && $_GET['type']!=''){
	
	$type=get_safe_value($con,$_GET['type']);
    $db =($type=='DirectCertificate') ? 'dcer' : 'mdcer';
}else {
        exit();
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $app_id = $_POST['application_id'];
    $printed_date = $_POST['printed_date'];
    $app_id = get_safe_value($con, $app_id);
    
    if (!empty($app_id) && !empty($printed_date)) {
        $stmt = $con->prepare("UPDATE {$db} SET printed_date = ? WHERE application_id = ?");
        $stmt->bind_param("si", $printed_date, $app_id);
        if ($stmt->execute()) {
            echo "Printed date updated successfully.";
        } else {
            echo "Failed to update printed date.";
        }
    } else {
        echo "Invalid input.";
    }
}
?>
