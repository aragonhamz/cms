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
// !isset($_SESSION['user_id']) && 
if (isset($_GET['login_token'])) {
    $login_token = $_GET['login_token'];
    // Retrieve values from URL
    $order_id = $_GET['order_id'] ?? 'N/A';
    $tracking_id = $_GET['tid'] ?? 'N/A';
    $amount = $_GET['amount'] ?? 'N/A';
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
    <div class="card shadow p-4 border-danger">
        <h2 class="text-danger">❌ Payment Failed or Cancelled</h2>
        <p>Your payment was not completed. Please try again after sometime, make sure you check your bank statement before retry probably after 30 mins or contact support if the issue persists.</p>
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
                <th>Amount</th>
                <td>₹<?php echo htmlspecialchars($amount); ?></td>
            </tr>
            <tr>
                <th>Application Type</th>
                <td><?php echo htmlspecialchars($type); ?></td>
            </tr>
        </table>
        
        <a href="<?php echo $redirect; ?>" class="btn btn-secondary mt-3">Try Again</a>
    </div>
</div>
<?php
require('footer.inc.php');
?>