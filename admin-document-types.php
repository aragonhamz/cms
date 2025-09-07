<?php
require('top.inc.php');
isAdmin();

$msg = '';

// Handle Add Document
if (isset($_POST['add_document'])) {
    $doc = trim(mysqli_real_escape_string($con, $_POST['document']));
    if ($doc !== '') {
        $stmt = $con->prepare("INSERT INTO doctype (document, enable) VALUES (?, 1)");
        $stmt->bind_param("s", $doc);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Document type added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to add document type.</div>";
        }
        $stmt->close();
    } else {
        $msg = "<div class='alert alert-warning'>Document name cannot be empty.</div>";
    }
}

// Handle Enable/Disable toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = mysqli_query($con, "SELECT enable FROM doctype WHERE doc_id = $id");
    if ($row = mysqli_fetch_assoc($res)) {
        $newStatus = $row['enable'] ? 0 : 1;
        mysqli_query($con, "UPDATE doctype SET enable = $newStatus WHERE doc_id = $id");
        header("Location: admin-document-types.php");
        exit;
    }
}

// Fetch existing document types
$docTypes = mysqli_query($con, "SELECT * FROM doctype ORDER BY doc_id ASC");
?>

<div class="content pb-0">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Manage Document Types</h5>
        </div>
        <div class="card-body">
            <?= $msg ?>

            <!-- Add new document form -->
            <form method="POST" class="mb-4 row g-3">
                <div class="col-md-6">
                    <input type="text" name="document" class="form-control" placeholder="Enter document type" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_document" class="btn btn-primary">Add Document</button>
                </div>
            </form>

            <!-- Table of existing document types -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Toggle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($docTypes)) {
                            $statusText = $row['enable'] ? 'Enabled' : 'Disabled';
                            $btnClass = $row['enable'] ? 'btn-success' : 'btn-secondary';
                            $toggleLabel = $row['enable'] ? 'Disable' : 'Enable';
                            echo "<tr>
                                    <td>{$i}</td>
                                    <td>{$row['document']}</td>
                                    <td><span class='badge {$btnClass}'>{$statusText}</span></td>
                                    <td>
                                        <a href='?toggle=1&id={$row['doc_id']}' class='btn btn-sm {$btnClass}'>{$toggleLabel}</a>
                                    </td>
                                  </tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require('footer.inc.php'); ?>
