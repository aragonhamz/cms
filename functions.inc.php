<?php
define('ENCRYPTION_KEY', APP_ENCRYPTION_KEY);
define('ENCRYPTION_IV', APP_ENCRYPTION_IV);
define('FROM_EMAIL', SMTP_USERNAME);
define('WEBSITE_NAME', APP_URL);
define('NEW_POST_DAYS', 3);
// Define your base site URL

// Define a fixed amount (can be anything like a fee, price, etc.)
define('DEGREEAMOUNT', 7.00);
define('DUPDEGREEAMOUNT', 2.00);
define('PROVISIONALAMOUNT', 3.00);
define('MIGRATIONAMOUNT', 4.00);

function getAppSetting($key, $default = '') {
    global $con;
    static $tableExists = null;

    if (!$con instanceof mysqli) {
        return $default;
    }
    if ($tableExists === null) {
        $result = $con->query("SHOW TABLES LIKE 'app_settings'");
        $tableExists = $result && $result->num_rows === 1;
    }
    if (!$tableExists) {
        return $default;
    }
    $stmt = $con->prepare('SELECT setting_value FROM app_settings WHERE setting_key = ? LIMIT 1');
    $stmt->bind_param('s', $key);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? $row['setting_value'] : $default;
}

function initializeAppSettings() {
    global $con;
    $con->query("CREATE TABLE IF NOT EXISTS app_settings (
        setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
        setting_value TEXT NOT NULL,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $defaults = [
        'app_url' => APP_URL,
        'ccavenue_merchant_id' => app_env('CCAVENUE_MERCHANT_ID', '213372'),
        'degree_amount' => DEGREEAMOUNT,
        'duplicate_degree_amount' => DUPDEGREEAMOUNT,
        'provisional_amount' => PROVISIONALAMOUNT,
        'migration_amount' => MIGRATIONAMOUNT,
        'mail_from_email' => SMTP_USERNAME,
        'mail_from_name' => 'Examination NEHU',
        'mail_reply_to' => SMTP_USERNAME,
    ];
    $stmt = $con->prepare('INSERT IGNORE INTO app_settings (setting_key, setting_value) VALUES (?, ?)');
    foreach ($defaults as $key => $value) {
        $value = (string) $value;
        $stmt->bind_param('ss', $key, $value);
        $stmt->execute();
    }
    $stmt->close();
}

function saveAppSetting($key, $value) {
    global $con;
    $stmt = $con->prepare('INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    $stmt->bind_param('ss', $key, $value);
    $saved = $stmt->execute();
    $stmt->close();
    return $saved;
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function appUrl() {
    return rtrim(getAppSetting('app_url', APP_URL), '/');
}

function paymentMerchantId() {
    return getAppSetting('ccavenue_merchant_id', app_env('CCAVENUE_MERCHANT_ID', '213372'));
}

function configureMailer($mail) {
    if (SMTP_USERNAME === '' || SMTP_PASSWORD === '') {
        throw new RuntimeException('SMTP is not configured. Set SMTP_USERNAME and SMTP_PASSWORD in .env.');
    }
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_ENCRYPTION === 'ssl' ? 'ssl' : 'tls';
    $mail->Port = SMTP_PORT;
    $fromEmail = getAppSetting('mail_from_email', SMTP_USERNAME);
    $fromName = getAppSetting('mail_from_name', 'Examination NEHU');
    $replyTo = getAppSetting('mail_reply_to', $fromEmail);
    $mail->setFrom($fromEmail, $fromName);
    $mail->addReplyTo($replyTo, 'Support');
}

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
	if (!isset($_SESSION['user_role'])) {
        header('Location: login.php');
        exit;
	}
	if ($_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
	}
}

function LoggedinUserNotAllowed(){
	if (isset($_SESSION['user_id'])) {
		header('Location: index.php');
        exit;
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
		return (float) getAppSetting('degree_amount', DEGREEAMOUNT);
	}elseif($doctype=='DuplicateDegree'){
		return (float) getAppSetting('duplicate_degree_amount', DUPDEGREEAMOUNT);
	}elseif($doctype=='Provisional'){
		return (float) getAppSetting('provisional_amount', PROVISIONALAMOUNT);
	}elseif($doctype=='Migration'){
		return (float) getAppSetting('migration_amount', MIGRATIONAMOUNT);
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
