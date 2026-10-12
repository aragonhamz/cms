<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('This command can only run from the command line.'); }
require __DIR__ . '/connection.inc.php';
require __DIR__ . '/functions.inc.php';
require __DIR__ . '/mail_queue.inc.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;

$limit = isset($argv[1]) ? max(1, min(500, (int) $argv[1])) : 50;
$delayMicroseconds = max(0, (int) app_env('MAIL_QUEUE_DELAY_US', '300000'));
$processed = 0;
while ($processed < $limit && ($email = claimQueuedEmail())) {
    try {
        $mail = new PHPMailer(true);
        configureMailer($mail);
        $mail->addAddress($email['recipient_email'], $email['recipient_name'] ?? '');
        $mail->isHTML(true);
        $mail->Subject = $email['subject'];
        $mail->Body = $email['html_body'];
        $mail->send();
        markQueuedEmailSent($email['id']);
        echo "Sent queue item {$email['id']}\n";
    } catch (Throwable $error) {
        markQueuedEmailFailed($email['id'], $error->getMessage());
        fwrite(STDERR, "Failed queue item {$email['id']}: {$error->getMessage()}\n");
    }
    $processed++;
    if ($delayMicroseconds > 0) usleep($delayMicroseconds);
}
echo "Processed {$processed} message(s).\n";
