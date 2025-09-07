<?php
require('top.inc.php');

// if (!isset($_SESSION['user_id']) || !isset($_GET['app_id']) || !isset($_GET['type'])){
//     die("Unauthorized access.");
// }
if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dispatch') {
    die("Access denied. Please login");
}

$search = '';
$updated = false;

if (isset($_POST['update_one'])) {
    $app_id = (int) $_POST['app_id'];
    $table = $_POST['table'];
    $new_msg = trim($_POST['new_msg']);

    if (!empty($app_id) && !empty($table) && !empty($new_msg)) {
        $stmt = $con->prepare("UPDATE {$table} SET msgs = ? WHERE application_id = ? AND status = 'Approved'");
        $stmt->bind_param("si", $new_msg, $app_id);
        $stmt->execute();
        $stmt->close();
        echo "<div class='alert alert-success'>Message updated for Application ID $app_id</div>";
    }
}


// Fetch results from both dcer and mdcer
$results = [];
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $search_like = "%$search%";

    foreach (['dcer', 'mdcer'] as $table) {
        //$stmt = $con->prepare("SELECT application_id, name, rollno, msgs, ? AS source FROM {$table} WHERE status = 'Approved' AND name LIKE ?");
        $stmt = $con->prepare("
        SELECT 
            a.application_id, 
            a.name, 
            a.rollno, 
            a.msgs, 
            u.mobile, 
            ? AS source 
        FROM {$table} a 
        JOIN users u ON a.user_id = u.id 
        WHERE a.status = 'Approved' AND a.name LIKE ?
        ");
        $stmt->bind_param("ss", $table, $search_like);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = $row;
        }
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <h2>Dispatch Panel</h2>

    <?php if ($updated): ?>
        <div class="alert alert-success">Message updated successfully.</div>
    <?php endif; ?>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" placeholder="Search applicant by name" class="form-control" value="<?= htmlspecialchars($search) ?>" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (!empty($results)): ?>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>App ID</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
               <tbody>
<?php foreach ($results as $row): ?>
    <tr>
        <form method="POST" onsubmit="return validateUpdate(this)">
            <td><?= $row['application_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['rollno'] ?></td>
            <td><?= strtoupper($row['mobile']) ?></td>
            <td><?= $row['msgs'] ?></td>
            <td>
                <select name="new_msg" class="form-select"  required>
                    <option value="" disabled selected>--Select--</option>
                    <option value="Ready to Collect">Ready to Collect</option>
                    <option value="Collected">Collected</option>
                    <option value="Dispatched by Post">Dispatched by Post</option>
                </select>
                <input type="hidden" name="app_id" value="<?= $row['application_id'] ?>">
                <input type="hidden" name="table" value="<?= $row['source'] ?>">
                <button type="submit" name="update_one" class="btn btn-success btn-sm mt-4">Update</button>
    
            </td>
        </form>
    </tr>
<?php endforeach; ?>
</tbody>

            </table>
            <!-- <button type="submit" name="update_statuses" class="btn btn-success">Update Status</button> -->
        </form>
    <?php elseif (isset($_GET['search'])): ?>
        <div class="alert alert-warning">No approved applications found for "<?= htmlspecialchars($search) ?>"</div>
    <?php endif; ?>
</div>
<script>
function validateUpdate(form) {
    const select = form.querySelector('select[name="new_msg"]');
    if (!select.value) {
        alert("Please select a valid message before updating.");
        return false; // prevent form submission
    }
    return true;
}
</script>


<?php require('footer.inc.php'); ?>
