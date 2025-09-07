<?php 
session_start(); // ✅ Needed to use $_SESSION
date_default_timezone_set('Asia/Kolkata');
include('Crypto.php');
include('connection.inc.php');?>
<?php

	error_reporting(0);
	
	$workingKey='95E728FA350ACF438AF698B29A0F4677';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server

		$rcvdString = decrypt($encResponse, $workingKey);  // Decrypt
		$order_status = "";
		$decryptValues = explode('&', $rcvdString);
		$dataSize = sizeof($decryptValues);

		$order_status = "";
		$order_id = "";
		$tid = "";
		$bank_ref_no = "";
		$merchant_param2 = "";
		$trans_date = "";
		$amount = "";
		$merchant_param5 = "";
		$paymentDate = date('Y-m-d H:i:s');
		// Loop through and get values
		for ($i = 0; $i < $dataSize; $i++) {
			$information = explode('=', $decryptValues[$i]);
			if (trim($information[0]) == 'order_id') {
				$order_id = $information[1];
			}
			if (trim($information[0]) == 'order_status') {
				$order_status = $information[1];
			}
			if (trim($information[0]) == 'tracking_id') {
				$tid = $information[1];
			}
			if (trim($information[0]) == 'bank_ref_no') {
				$bank_ref_no = $information[1];
			}
			if (trim($information[0]) == 'merchant_param2') {
				$merchant_param2 = $information[1];
			}
			if (trim($information[0]) == 'trans_date') {
				$trans_date = $information[1];
			}
			if (trim($information[0]) == 'amount') {
				$amount = $information[1];
			}
			if (trim($information[0]) == 'merchant_param5') {
				$merchant_param5 = $information[1];
			}
			
		}

		$login_token = $merchant_param5 ?? '';

			if (!empty($login_token)) {
				$stmt = $con->prepare("SELECT * FROM users WHERE login_token = ? LIMIT 1");
				$stmt->bind_param("s", $login_token);
				$stmt->execute();
				$result = $stmt->get_result();
				$user = $result->fetch_assoc();  // ✅ Use $user instead of $row
				$stmt->close();

				if ($user) {
					// ✅ Restore session from user row
					$_SESSION['user_id']    = $user['id'];
					$_SESSION['user_name']  = $user['name'];
					$_SESSION['user_email'] = $user['email'];
					$_SESSION['user_mobile'] = $user['mobile'];
					$_SESSION['user_role']  = $user['role'];

					// ✅ Clear the login token after use
					// $stmt = $con->prepare("UPDATE users SET login_token = NULL WHERE id = ?");
					// $stmt->bind_param("i", $user['id']);
					// $stmt->execute();
					// $stmt->close();
				}
			}


	if($order_status==="Success")
	{	
		
		if ($merchant_param2 == 'ManualCertificate') {
			$stmt = $con->prepare("UPDATE mdcer SET payment = ?, amount=?, paymentOn=?, transactionID = ?, bank_ref_no = ? WHERE cer_id = ?");
		} elseif ($merchant_param2 == 'DirectCertificate') {
			$stmt = $con->prepare("UPDATE dcer SET payment = ?, amount=?, paymentOn=?, transactionID = ?, bank_ref_no = ? WHERE cer_id = ?");
		}
		
		$stmt->bind_param("ssssss", $order_status, $amount, $paymentDate, $tid, $bank_ref_no, $order_id);
		$stmt->execute();
		$stmt->close();
		$con->close();
		//echo "<br>Thank you for shopping with us. With order id= ". $order_id." Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		header("Location: payment-success.php?order_id=$order_id&tid=$tid&bank_ref_no=$bank_ref_no&type=$merchant_param2&amount=$amount&login_token=$login_token");
    	exit();
		
	}
	else if($order_status==="Aborted" || $order_status==="Failure")
	{
		$trans_date = date('Y-m-d H:i:s');
		if ($merchant_param2 == 'ManualCertificate') {
			$stmt = $con->prepare("UPDATE mdcer SET payment = ?, amount = ?, paymentOn = ?, transactionID = ? WHERE cer_id = ?");
		} elseif ($merchant_param2 == 'DirectCertificate') {
			$stmt = $con->prepare("UPDATE dcer SET payment = ?, amount = ?, paymentOn = ?, transactionID = ? WHERE cer_id = ?");
		}
        
		$stmt->bind_param("sssss", $order_status, $amount, $paymentDate, $tid, $order_id);
		$stmt->execute();
		$stmt->close();
		$con->close();
		
		//echo "<br>Your order has been aborted With order id= ". $order_id." and transaction id ". $tid ." and  token: ".$merchant_param5." and user id: ".$_SESSION['user_id']."";
		header("Location: payment-failure.php?order_id=$order_id&tid=$tid&type=$merchant_param2&amount=$amount&login_token=$login_token");
    	exit();
	
	}
	// else if($order_status==="Failure")
	// {
	// 	if ($merchant_param2 == 'ManualCertificate') {
	// 		$stmt = $con->prepare("UPDATE mdcer SET payment = 'Cancelled', paymentOn=?, transactionID = ? WHERE cer_id = ?");
	// 	} elseif ($merchant_param2 == 'DirectCertificate') {
	// 		$stmt = $con->prepare("UPDATE dcer SET payment = 'Cancelled', paymentOn=?, transactionID = ? WHERE cer_id = ?");
	// 	}
        
	// 	$stmt->bind_param("sss", $trans_date, $tid, $order_id);
	// 	$stmt->execute();
	// 	$stmt->close();
	// 	$con->close();
		
	// 	//echo "<br>Your order has been aborted With order id= ". $order_id." and transaction id ". $tid ." and  token: ".$merchant_param5." and user id: ".$_SESSION['user_id']."";
	// 	header("Location: payment-failure.php?order_id=$order_id&tid=$tid&type=$merchant_param2&login_token=$login_token");
    // 	exit();
	// }
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	// echo "<br><br>";

	// echo "<table cellspacing=4 cellpadding=4>";
	// for($i = 0; $i < $dataSize; $i++) 
	// {
	// 	$information=explode('=',$decryptValues[$i]);
	//     	echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
	// }

	// echo "</table><br>";
	// echo "</center>";
?>
