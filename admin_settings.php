<?php
require('top.inc.php');
isAdmin();
initializeAppSettings();

$fields = [
    'app_url' => ['Public site URL', 'url'],
    'ccavenue_merchant_id' => ['CCAvenue merchant ID', 'text'],
    'degree_amount' => ['Degree certificate fee', 'number'],
    'duplicate_degree_amount' => ['Duplicate degree fee', 'number'],
    'provisional_amount' => ['Provisional certificate fee', 'number'],
    'migration_amount' => ['Migration certificate fee', 'number'],
    'mail_from_email' => ['Sender email address', 'email'],
    'mail_from_name' => ['Sender display name', 'text'],
    'mail_reply_to' => ['Reply-to email address', 'email'],
];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    $message = '<div class="alert alert-danger">Your session has expired. Refresh the page and try again.</div>';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $key => [$label, $type]) {
        $value = trim($_POST[$key] ?? '');
        if (in_array($key, ['app_url', 'mail_from_email', 'mail_reply_to'], true) && $value !== '') {
            $valid = $key === 'app_url' ? filter_var($value, FILTER_VALIDATE_URL) : filter_var($value, FILTER_VALIDATE_EMAIL);
            if (!$valid) {
                $message = '<div class="alert alert-danger">Please enter a valid value for ' . htmlspecialchars($label) . '.</div>';
                break;
            }
        }
        if (str_ends_with($key, '_amount') && (!is_numeric($value) || (float) $value < 0)) {
            $message = '<div class="alert alert-danger">Fees must be non-negative numbers.</div>';
            break;
        }
        saveAppSetting($key, $value);
    }
    if ($message === '') {
        $message = '<div class="alert alert-success">Settings saved.</div>';
    }
}
?>
<div class="card shadow mb-4">
  <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Application Settings</h6></div>
  <div class="card-body">
    <?php echo $message; ?>
    <p class="text-muted">SMTP host, username, password, database credentials, and encryption keys are deployment secrets. Configure them only in <code>.env</code>; they are never shown or stored here.</p>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
      <div class="row">
        <?php foreach ($fields as $key => [$label, $type]): ?>
          <div class="col-md-6 mb-3">
            <label for="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?></label>
            <input id="<?php echo $key; ?>" name="<?php echo $key; ?>" type="<?php echo $type; ?>" class="form-control"
              <?php echo $type === 'number' ? 'step="0.01" min="0"' : ''; ?>
              value="<?php echo htmlspecialchars(getAppSetting($key, '')); ?>" required>
          </div>
        <?php endforeach; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save settings</button>
    </form>
  </div>
</div>
<?php require('footer.inc.php'); ?>
