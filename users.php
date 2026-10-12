<?php
require('top.inc.php');
isAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $message = '<div class="alert alert-danger">Your session has expired. Refresh the page and try again.</div>';
    } else {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';
        if (!$userId || !in_array($action, ['activate', 'deactivate'], true)) {
            $message = '<div class="alert alert-danger">Invalid applicant action.</div>';
        } elseif ($userId === (int) $_SESSION['user_id']) {
            $message = '<div class="alert alert-warning">You cannot change your own account status here.</div>';
        } else {
            $status = $action === 'activate' ? 1 : 0;
            $stmt = $con->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'user'");
            $stmt->bind_param('ii', $status, $userId);
            $stmt->execute();
            $changed = $stmt->affected_rows;
            $stmt->close();
            $message = $changed === 1
                ? '<div class="alert alert-success">Applicant account updated.</div>'
                : '<div class="alert alert-warning">No applicant account was changed.</div>';
        }
    }
}

$search = trim($_GET['q'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 25;
$where = "WHERE u.role = 'user'";
if ($search !== '') {
    $where .= ' AND (u.name LIKE ? OR u.email LIKE ? OR u.mobile LIKE ? OR u.id = ?)';
    $like = '%' . $search . '%';
    $searchId = ctype_digit($search) ? (int) $search : -1;
}

$countStmt = $con->prepare("SELECT COUNT(*) AS total FROM users u $where");
if ($search !== '') $countStmt->bind_param('sssi', $like, $like, $like, $searchId);
$countStmt->execute();
$total = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$listSql = "SELECT u.id, u.name, u.email, u.mobile, u.status, u.email_verified_at,
    (SELECT COUNT(*) FROM dcer d WHERE d.user_id = u.id) +
    (SELECT COUNT(*) FROM mdcer m WHERE m.user_id = u.id) AS application_count
    FROM users u $where ORDER BY u.id DESC LIMIT ? OFFSET ?";
$listStmt = $con->prepare($listSql);
if ($search !== '') {
    $listStmt->bind_param('sssiii', $like, $like, $like, $searchId, $perPage, $offset);
} else {
    $listStmt->bind_param('ii', $perPage, $offset);
}
$listStmt->execute();
$applicants = $listStmt->get_result();
?>
<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Applicant Management</h6>
    <span class="badge badge-primary"><?php echo $total; ?> applicants</span>
  </div>
  <div class="card-body">
    <?php echo $message; ?>
    <form method="get" class="mb-3"><div class="input-group">
      <input type="search" name="q" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name, email, mobile, or ID">
      <div class="input-group-append"><button class="btn btn-primary" type="submit">Search</button></div>
    </div></form>
    <div class="table-responsive"><table class="table table-bordered table-hover">
      <thead><tr><th>ID</th><th>Applicant</th><th>Contact</th><th>Email verified</th><th>Applications</th><th>Account</th><th>Actions</th></tr></thead>
      <tbody>
        <?php while ($applicant = $applicants->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int) $applicant['id']; ?></td>
            <td><?php echo htmlspecialchars($applicant['name']); ?></td>
            <td><?php echo htmlspecialchars($applicant['email']); ?><br><small><?php echo htmlspecialchars($applicant['mobile']); ?></small></td>
            <td><?php echo $applicant['email_verified_at'] ? '<span class="badge badge-success">Verified</span>' : '<span class="badge badge-secondary">Not verified</span>'; ?></td>
            <td><?php echo (int) $applicant['application_count']; ?></td>
            <td><?php echo (int) $applicant['status'] === 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>'; ?></td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-info" href="profile.php?type=view&id=<?php echo rawurlencode(encrypt($applicant['id'])); ?>">Profile</a>
              <form method="post" class="d-inline" onsubmit="return confirm('Change this applicant account status?');">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>"><input type="hidden" name="user_id" value="<?php echo (int) $applicant['id']; ?>">
                <?php if ((int) $applicant['status'] === 1): ?><button class="btn btn-sm btn-warning" name="action" value="deactivate">Deactivate</button>
                <?php else: ?><button class="btn btn-sm btn-success" name="action" value="activate">Activate</button><?php endif; ?>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if ($total === 0): ?><tr><td colspan="7" class="text-center">No applicants found.</td></tr><?php endif; ?>
      </tbody>
    </table></div>
    <?php if ($totalPages > 1): ?><nav><ul class="pagination justify-content-end">
      <?php for ($number = 1; $number <= $totalPages; $number++): ?><li class="page-item <?php echo $number === $page ? 'active' : ''; ?>"><a class="page-link" href="?q=<?php echo rawurlencode($search); ?>&page=<?php echo $number; ?>"><?php echo $number; ?></a></li><?php endfor; ?>
    </ul></nav><?php endif; ?>
  </div>
</div>
<?php $listStmt->close(); require('footer.inc.php'); ?>
