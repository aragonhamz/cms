<?php
/** Database-backed outbound email queue. Web requests only enqueue messages; a CLI worker sends them. */
function initializeMailQueue() {
    global $con;
    static $initialized = false;
    if ($initialized) return;
    $con->query("CREATE TABLE IF NOT EXISTS email_queue (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        recipient_email VARCHAR(254) NOT NULL,
        recipient_name VARCHAR(255) DEFAULT NULL,
        subject VARCHAR(255) NOT NULL,
        html_body MEDIUMTEXT NOT NULL,
        priority TINYINT NOT NULL DEFAULT 10,
        status ENUM('pending','processing','sent','failed') NOT NULL DEFAULT 'pending',
        attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
        available_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        locked_at DATETIME DEFAULT NULL,
        sent_at DATETIME DEFAULT NULL,
        last_error TEXT DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX email_queue_delivery (status, available_at, priority)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $initialized = true;
}

function queueEmail($email, $subject, $htmlBody, $priority = 10, $name = null) {
    global $con;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('A valid recipient email address is required.');
    initializeMailQueue();
    $priority = max(0, min(100, (int) $priority));
    $stmt = $con->prepare('INSERT INTO email_queue (recipient_email, recipient_name, subject, html_body, priority) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssi', $email, $name, $subject, $htmlBody, $priority);
    $queued = $stmt->execute();
    $stmt->close();
    return $queued;
}

function claimQueuedEmail() {
    global $con;
    initializeMailQueue();
    $con->begin_transaction();
    try {
        $result = $con->query("SELECT * FROM email_queue WHERE (status = 'pending' OR (status = 'failed' AND attempts < 5) OR (status = 'processing' AND locked_at < DATE_SUB(NOW(), INTERVAL 20 MINUTE))) AND available_at <= NOW() ORDER BY priority DESC, id ASC LIMIT 1 FOR UPDATE");
        $email = $result ? $result->fetch_assoc() : null;
        if (!$email) { $con->commit(); return null; }
        $stmt = $con->prepare("UPDATE email_queue SET status = 'processing', attempts = attempts + 1, locked_at = NOW() WHERE id = ?");
        $stmt->bind_param('i', $email['id']);
        $stmt->execute();
        $stmt->close();
        $con->commit();
        return $email;
    } catch (Throwable $error) {
        $con->rollback();
        throw $error;
    }
}

function markQueuedEmailSent($id) {
    global $con;
    $stmt = $con->prepare("UPDATE email_queue SET status = 'sent', sent_at = NOW(), last_error = NULL WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function markQueuedEmailFailed($id, $error) {
    global $con;
    $error = substr($error, 0, 4000);
    $delayMinutes = min(60, max(1, 2 ** min(5, (int) $id % 6)));
    $stmt = $con->prepare("UPDATE email_queue SET status = 'failed', last_error = ?, available_at = DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE id = ?");
    $stmt->bind_param('sii', $error, $delayMinutes, $id);
    $stmt->execute();
    $stmt->close();
}
