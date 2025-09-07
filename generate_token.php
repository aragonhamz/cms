<?php
require('connection.inc.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$login_token = bin2hex(random_bytes(16));
$_SESSION['login_token'] = $login_token;

$stmt = $con->prepare("UPDATE users SET login_token = ? WHERE id = ?");
$stmt->bind_param("si", $login_token, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success', 'token' => $login_token]);
