<?php
require_once __DIR__ . '/vendor/autoload.php';
ob_start();
$mpdf = new \Mpdf\Mpdf();



ob_end_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="test.pdf"');
$mpdf->WriteHTML('<h1>Hello World!</h1>');
$mpdf->Output('', 'I');
?>
