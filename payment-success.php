<?php
require('top.inc.php');
date_default_timezone_set('Asia/Kolkata');

$order_id = "";
$tracking_id = "";
$amount = "";
$type = "";
$login_token = "";
$redirect="";

if (!isset($_SESSION['user_id'])){
    header('location: index.php');
    exit();
}
// Restore session using token if needed
if (isset($_GET['login_token'])) {
    $login_token = $_GET['login_token'];
    // Retrieve values from URL
    $order_id = $_GET['order_id'] ?? 'N/A';
    $tracking_id = $_GET['tid'] ?? 'N/A';
    $amount = $_GET['bank_ref_no'] ?? 'N/A';
    $type = $_GET['type'] ?? 'N/A';

    $stmt = $con->prepare("SELECT * FROM users WHERE login_token = ? LIMIT 1");
    $stmt->bind_param("s", $login_token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_mobile'] = $user['mobile'];
        $_SESSION['user_role']  = $user['role'];

        // (Optional) Clear token after use
        $stmt = $con->prepare("UPDATE users SET login_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $stmt->close();
    }
    if($type=="DirectCertificate"){
        $redirect = "mydegreeapplications.php";
    }elseif($type=="ManualCertificate"){
        $redirect = "myapplications.php";
    } 
}


?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-success">🎉 Payment Successful</h2>
        <p>Thank you! Your payment was successfully processed and the form has been submitted.</p>
        <hr>

        <table class="table table-bordered">
            <tr>
                <th>Order ID</th>
                <td><?php echo htmlspecialchars($order_id); ?></td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td><?php echo htmlspecialchars($tracking_id); ?></td>
            </tr>
            <tr>
                <th>Bank reference No</th>
                <td>₹<?php echo htmlspecialchars($bank_ref_no); ?></td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td>₹<?php echo htmlspecialchars($amount); ?></td>
            </tr>
            <tr>
                <th>Application Type</th>
                <td><?php echo htmlspecialchars($type); ?></td>
            </tr>
        </table>

        <a href="<?php echo $redirect; ?>" class="btn btn-primary mt-3">View now</a>
    </div>
</div>
<?php
require('footer.inc.php');
?>