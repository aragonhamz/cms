<?php
require('top.inc.php');
require_once 'mail_queue.inc.php';
isAdmin();
initializeMailQueue();

$summary = $con->query("SELECT status, COUNT(*) AS total FROM email_queue GROUP BY status");
$counts = ['pending' => 0, 'processing' => 0, 'sent' => 0, 'failed' => 0];
while ($row = $summary->fetch_assoc()) $counts[$row['status']] = (int) $row['total'];
$recent = $con->query("SELECT id, recipient_email, subject, status, attempts, created_at, sent_at, last_error FROM email_queue ORDER BY id DESC LIMIT 100");
?>
<div class="card shadow mb-4">
  <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Email Delivery Queue</h6></div>
  <div class="card-body">
    <div class="row mb-4">
      <?php foreach ($counts as $status => $count): ?><div class="col-md-3 mb-2"><div class="border rounded p-3 text-center"><strong class="d-block text-capitalize"><?php echo $status; ?></strong><span class="h4 mb-0"><?php echo $count; ?></span></div></div><?php endforeach; ?>
    </div>
    <p class="text-muted">The background worker sends pending items. Failed items retry automatically up to five times; error details are shown below.</p>
    <div class="table-responsive"><table class="table table-bordered table-sm">
      <thead><tr><th>ID</th><th>Recipient</th><th>Subject</th><th>Status</th><th>Attempts</th><th>Queued</th><th>Sent</th><th>Last error</th></tr></thead>
      <tbody><?php while ($email = $recent->fetch_assoc()): ?><tr>
        <td><?php echo (int) $email['id']; ?></td><td><?php echo htmlspecialchars($email['recipient_email']); ?></td><td><?php echo htmlspecialchars($email['subject']); ?></td>
        <td><span class="badge badge-<?php echo $email['status'] === 'sent' ? 'success' : ($email['status'] === 'failed' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($email['status']); ?></span></td>
        <td><?php echo (int) $email['attempts']; ?></td><td><?php echo htmlspecialchars($email['created_at']); ?></td><td><?php echo htmlspecialchars($email['sent_at'] ?? ''); ?></td><td><?php echo htmlspecialchars($email['last_error'] ?? ''); ?></td>
      </tr><?php endwhile; ?></tbody>
    </table></div>
  </div>
</div>
<?php require('footer.inc.php'); ?>
