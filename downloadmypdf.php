<?php
ob_start(); 
date_default_timezone_set('Asia/Kolkata');

require('connection.inc.php');
require('functions.inc.php');
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['app_id']) || !isset($_GET['type'])){
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$encrypted_id = get_safe_value($con, $_GET['app_id'] ?? '');
//$encrypted_id = $_GET['app_id'] ?? '';
$app_id = decryptAppId($encrypted_id);
echo $app_id;
// Fetch application and user details

$db = ($_GET['type'] == 'ManualCertificate') ? 'mdcer' : 'dcer';
$stmt = $con->prepare("
    SELECT {$db}.*, users.name as username, users.email, users.mobile 
    FROM {$db} 
    INNER JOIN users ON {$db}.user_id = users.id
    WHERE {$db}.application_id = ? AND {$db}.user_id = ?
");
$stmt->bind_param("si", $app_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Application not found.");
}

$row = $result->fetch_assoc();

// Setup mPDF
$mpdf = new \Mpdf\Mpdf([
     'tempDir' => '/tmp',
    'format' => 'A4',
    'margin_top' => 55
]);

$mpdf->SetHTMLHeader('
    <div style="text-align: center; line-height: 0.5;">
        <img src="images/NEHU_logo.png" width="80">
        <h1>North-Eastern Hill University</h1>
        <p style="font-size:12px;">Mawlai Umshing, Shillong, 793022</p>
        <hr>
    </div>
');

$mpdf->SetHTMLFooter('
    <hr>
    <div style="text-align: center; font-size: 10px;">
        This is a system-generated certificate. &copy; Computer Center, NEHU : Downloaded on - ' . date('d-M-Y, h:i A') . '
    </div>
');

// Main Certificate Page
$html = '
<style>
    body { font-family: sans-serif; }
    .heading { text-align: center; font-size: 20px; margin-top: 80px; color:rgb(9, 21, 32); }
    .section { margin: 15px 0; }
    .label { font-weight: bold; display: inline-block; width: 180px; vertical-align: top; text-align: center; }
    .value { color:rgb(9, 20, 31); display: inline-block; margin-left:20px; }
    .signature-block { text-align: right; margin-top: 60px; }
</style>

<div class="heading">' . htmlspecialchars($row['doctype']) . ' Certificate Application</div>
<div class="section"><span class="label">Application for the Degree of:  </span><span class="value">' . htmlspecialchars($row['degree']) . '</span></div>
<div class="section"><span class="label">Application ID:  </span><span class="value">' . htmlspecialchars($row['application_id']) . '</span></div>
<div class="section"><span class="label">Roll No:  </span><span class="value">' . htmlspecialchars($row['rollno']) . '</span></div>
<div class="section"><span class="label">Student Name:  </span><span class="value">' . nl2br(htmlspecialchars($row['name'])) . '</span></div>
<div class="section"><span class="label">Registration No:  </span><span class="value">' . htmlspecialchars($row['regno']) . '</span></div>';

// Only show if exists
$fields = [
    'dept' => 'Department',
    'school' => 'School Of',
    'college' => 'College',
    'honours' => 'Honours',
    'gradyear' => 'Graduation Year',
    'cgpa' => 'CGPA',
    'grade' => 'Grade',
    'division' => 'Division',
];

foreach ($fields as $field => $label) {
    if (!empty($row[$field])) {
        $html .= '<div class="section"><span class="label">' . $label . ':  </span><span class="value">' . htmlspecialchars($row[$field]) . '</span></div>';
    }
}

$html .= '
<hr style="margin:30px 0;">
<div class="signature-block">
    <span class="signature-label">Countersigned by:</span><br>
    <span class="signature-label">Principal or Head of Department (With Office Seal)</span>
</div>';

// Write main page
$mpdf->WriteHTML($html);

// ADD NEXT PAGE for RECEIPT
$mpdf->AddPage();

// Payment Receipt Format
$receiptHtml = '
<style>
    .receipt-title { text-align: center; font-size: 24px; margin-bottom: 20px; color: darkgreen; }
    .receipt-section { margin: 20px 0; font-size: 14px; }
    .receipt-label { font-weight: bold; display: inline-block; width: 150px; }
    .receipt-value { display: inline-block; }
</style>

<div class="receipt-title">Payment Receipt</div>

<div class="receipt-section"><span class="receipt-label">Application ID:</span><span class="receipt-value">' . htmlspecialchars($row['application_id']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Name:</span><span class="receipt-value">' . htmlspecialchars($row['username']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Mobile:</span><span class="receipt-value">' . htmlspecialchars($row['mobile']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Email:</span><span class="receipt-value">' . htmlspecialchars($row['email']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Applied Degree:</span><span class="receipt-value">' . htmlspecialchars($row['degree']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Amount Paid:</span><span class="receipt-value">₹ ' . htmlspecialchars($row['amount']) . '</span></div>
<div class="receipt-section"><span class="receipt-label">Payment Status:</span><span class="receipt-value">'. htmlspecialchars($row['payment']).'</span></div>
<div class="receipt-section"><span class="receipt-label">Transaction Date:</span><span class="receipt-value">'. htmlspecialchars($row['paymentOn']).'</span></div>
<div class="receipt-section"><span class="receipt-label">Transaction Ref ID:</span><span class="receipt-value">'. htmlspecialchars($row['transactionID']).'</span></div>
<div class="receipt-section"><span class="receipt-label">Bank reference ID:</span><span class="receipt-value">'. htmlspecialchars($row['bank_ref_no']).'</span></div>

<hr style="margin-top:30px;">

<div style="text-align:center; font-size:12px;">
    This is an electronically generated receipt. <br>No signature is required.
</div>';

// Write receipt page
$mpdf->WriteHTML($receiptHtml);

// Output
ob_end_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Degree_Application_' . $app_id . '.pdf"');
$mpdf->Output('Degree_Application_' . $app_id . '.pdf', 'I');
exit;
?>