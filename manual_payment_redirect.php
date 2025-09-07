<?php
session_start();
if (!isset($_SESSION['manual_order_id'])) {
    header('Location: index.php');
    exit();
}
$order_id = $_SESSION['manual_order_id'];
$amount = $_SESSION['manual_amount'];
?>

<!DOCTYPE html>
<html>
<head><title>Redirecting to Payment...</title></head>
<body>
<form method="post" id="manualPaymentForm" action="ccavRequestHandler.php">
    <input type="hidden" name="tid" value="<?php echo time(); ?>" />
    <input type="hidden" name="merchant_id" value="213372" />
    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
    <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
    <input type="hidden" name="currency" value="INR" />
    <input type="hidden" name="redirect_url" value="http://localhost/cms/ccavResponseHandler.php" />
    <input type="hidden" name="cancel_url" value="http://localhost/cms/ccavResponseHandler.php" />
    <input type="hidden" name="language" value="EN" />
    <input type="hidden" name="merchant_param1" value="Manual Degree Certificate" />
</form>

<script>
    document.getElementById("manualPaymentForm").submit();
</script>
<?php
// Optional: delay slightly to ensure redirect before unsetting
sleep(1);
unset($_SESSION['manual_order_id']);
unset($_SESSION['manual_amount']);
?>
</body>
</html>
