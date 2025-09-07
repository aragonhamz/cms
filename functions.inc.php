<?php
define('ENCRYPTION_KEY', 'hamar.kharshiing@gmail.com198632'); // Must be 32 characters for AES-256-CBC
define('ENCRYPTION_IV', '7005160168021282'); // Must be 16 characters
define('FROM_EMAIL', 'aragon.hamz@gmail.com');
define('WEBSITE_NAME', 'http://localhost/cms');
define('NEW_POST_DAYS', 3);
// Define your base site URL

// Define a fixed amount (can be anything like a fee, price, etc.)
define('DEGREEAMOUNT', 7.00);
define('DUPDEGREEAMOUNT', 2.00);
define('PROVISIONALAMOUNT', 3.00);
define('MIGRATIONAMOUNT', 4.00);

function pr($arr){
	echo '<pre>';
	print_r($arr);
}

function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

function get_safe_value($con,$str){
	if($str!=''){
		$str=trim($str);
		return mysqli_real_escape_string($con,$str);
	}
}
function isAdmin(){
	if(!isset($_SESSION['user_role'])){
	?>
		<script>
		window.location.href='login.php';
		</script>
		<?php
	}
	if($_SESSION['user_role']=='user' && $_SESSION['user_role']!='admin'){
		?>
		<script>
		window.location.href='restricted.php';
		</script>
		<?php
	}
}

function LoggedinUserNotAllowed(){
	if (isset($_SESSION['user_id'])) {
		?>
		<script>
		window.location.href='restricted.php';
		</script>
		<?php
	}
}
function checkIfLogin(){
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		header('Location: login.php');
		exit;
	}
}

// Encrypt Function
function encrypt($data) {
    return openssl_encrypt($data, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}

// Decrypt Function
function decrypt($data) {
    return openssl_decrypt($data, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}
function getApprovedDcer($con){
	$sql = "SELECT * from dcer where status='Applied' and payment='Success'";

	if ($result = $con->query($sql)) {

		// Return the number of rows in result set
		$rowcount = $result->num_rows;
		
		// Display result
		return $rowcount;
	}
}
function getApprovedMDcer($con){
	$sql = "SELECT * from mdcer where status='Applied' and payment='Success'";

	if ($result = $con->query($sql)) {

		// Return the number of rows in result set
		$rowcount = $result->num_rows;
		
		// Display result
		return $rowcount;
	}
}
function getRegusers($con){
	$sql = "SELECT * from users where role='user'";

	if ($result = $con->query($sql)) {

		// Return the number of rows in result set
		$rowcount = $result->num_rows;
		
		// Display result
		return $rowcount;
	}
}

function getLoginToken($con, $userid){
	// Generate secure login token
	$login_token = bin2hex(random_bytes(16));
	$_SESSION['login_token'] = $login_token;
	$stmt = $con->prepare("UPDATE users SET login_token = ? WHERE id = ?");
	$stmt->bind_param("si", $login_token, $userid);
	$stmt->execute();
	$stmt->close();
	return $login_token;
}

function getamount($doctype){
	if($doctype=='Degree'){
		return DEGREEAMOUNT;
	}elseif($doctype=='DuplicateDegree'){
		return DUPDEGREEAMOUNT;
	}elseif($doctype=='Provisional'){
		return PROVISIONALAMOUNT;
	}elseif($doctype=='Migration'){
		return MIGRATIONAMOUNT;
	}
}

function encryptAppId($app_id) {
    $key = '12345678910hamarkharshiing@gmail.'; // Must be 32 chars for AES-256
    $iv = openssl_random_pseudo_bytes(16); // AES block size = 16 bytes
    $ciphertext = openssl_encrypt($app_id, 'AES-256-CBC', $key, 0, $iv);
    return urlencode(base64_encode($iv . $ciphertext)); // Store IV + data together
}
function decryptAppId($encrypted) {
    $key = '12345678910hamarkharshiing@gmail.'; // Same key
    $data = base64_decode(urldecode($encrypted));
    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
}


function fetchDistinctValues($con, $table, $column) {
    $options = [];
    // Sanitize table and column names to prevent SQL injection (basic check)
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);

    $sql = "SELECT DISTINCT `$column` FROM `$table` WHERE `$column` IS NOT NULL AND `$column` != '' and enable='1'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options[] = $row[$column];
        }
    }

    return $options;
}
function generateOptions($options, $selectedValue = '') {
    $html = '<option value="" disable>-- Select Document --</option>';
    foreach ($options as $option) {
        $isSelected = ($option === $selectedValue) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($option) . '"' . $isSelected . '>' . htmlspecialchars($option) . '</option>';
    }
    return $html;
}


?>