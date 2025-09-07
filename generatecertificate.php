<?php
require_once __DIR__ . '/vendor/autoload.php';
require('connection.inc.php');
require('functions.inc.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['app_id']) || !isset($_GET['type'])){
    die("Unauthorized access.");
}


//$encrypted_id = get_safe_value($con, $_GET['app_id']);
$encrypted_id = $_GET['app_id'];
$app_id = urldecode($encrypted_id);
$app_id = decryptAppId($app_id);
$type = get_safe_value($con, $_GET['type']);
$table = ($type === 'DirectCertificate') ? 'dcer' : 'mdcer';

$stmt = $con->prepare("SELECT * FROM $table WHERE application_id = ?");
$stmt->bind_param("i", $app_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Application not found.");
}

// Extract dynamic fields
$name = $data['name'];
$roll = $data['rollno'];
$regno = $data['regno'];
$degree = $data['degree'];
$cgpa = $data['cgpa'];
$grade = $data['grade'];
$gradyear = $data['gradyear'];
$today = date('d-m-Y');
$serial_no = str_pad($app_id, 6, '0', STR_PAD_LEFT);


$degreetype = $data['degreetype'];

// Get format from degreetype table
$stmt = $con->prepare("SELECT format FROM degreetype WHERE degree = ?");
$stmt->bind_param("s", $degree);
$stmt->execute();
$res = $stmt->get_result();
$formatRow = $res->fetch_assoc();
$format = $formatRow['format'] ?? 'default';
$stmt->close();


// Path to logo
$logoPath = realpath(__DIR__ . '/images/NEHU_logo.png');
$logoPath = str_replace('\\', '/', $logoPath);

// mPDF setup
 
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => '/tmp',
    'fontDir' => array_merge($fontDirs, [__DIR__ . '/vendor/mpdf/mpdf/ttfonts']),
    'fontdata' => $fontData + [
        'devlys010' => ['R' => 'Devlys010.ttf'],
        'kruti' => ['R' => 'Kruti Dev 010 Regular.ttf'],
        'kokila' => ['R' => 'Kokila.ttf'],
        'timesnewroman' => ['R' => 'times.ttf']
    ],
    'default_font' => 'timesnewroman',
    'format' => [216, 356], // A4 in mm
]);

// QR Code data
$qrURL = "http://exams.nehu.ac.in/mcverify?v1=$roll&v2=$app_id";
$qrHTML = "<barcode code='$qrURL' type='QR' size='1' error='M' class='qr' />";

$templateFile = __DIR__ . "/templates/" . strtolower($format) . "";
if (file_exists($templateFile)) {
    include($templateFile);
} else {
    die("Template not found for format: $format");
}


// Output PDF
$mpdf->WriteHTML($html);
$mpdf->Output("Certificate_$app_id.pdf", "I");
?>
