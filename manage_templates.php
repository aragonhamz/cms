<?php
require('top.inc.php');
isAdmin();

$msg = '';

// 🔸 Load available format files dynamically from templates/
$formatOptions = [];
$templateDir = __DIR__ . '/templates/';
if (is_dir($templateDir)) {
    $files = glob($templateDir . '*.php');
    foreach ($files as $file) {
        $formatOptions[] = basename($file); // e.g., format1.php
    }
}

// 🔸 Handle AJAX request
if (isset($_POST['ajax']) && $_POST['ajax'] === 'get_format') {
    $degree = mysqli_real_escape_string($con, $_POST['degree']);
    $res = mysqli_query($con, "SELECT format FROM degreetype WHERE degree = '$degree' LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    echo $row['format'] ?? '';
    exit;
}

// 🔸 Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['degree'], $_POST['format']) && !isset($_POST['ajax'])) {
    $degree = mysqli_real_escape_string($con, $_POST['degree']);
    $format = mysqli_real_escape_string($con, $_POST['format']);

    $stmt = $con->prepare("UPDATE degreetype SET format = ? WHERE degree = ?");
    $stmt->bind_param("ss", $format, $degree);
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Format updated for <strong>$degree</strong>.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Failed to update format: {$stmt->error}</div>";
    }
    $stmt->close();
}

// 🔸 Load degree options
$resDegree = mysqli_query($con, "SELECT DISTINCT degree FROM degreetype ORDER BY degree ASC");
$degreeList = [];
while ($row = mysqli_fetch_assoc($resDegree)) {
    $degreeList[] = $row['degree'];
}
?>

<div class="content pb-0">
    <div class="card shadow">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Update Degree Format</h5>
        </div>
        <div class="card-body">
            <?= $msg ?>
            <form method="POST" class="row g-3" id="formatForm">
                <div class="col-md-6">
                    <label class="form-label">Select Degree</label>
                    <select name="degree" id="degreeSelect" class="form-select" required>
                        <option value="">-- Select Degree --</option>
                        <?php foreach ($degreeList as $deg): ?>
                            <option value="<?= htmlspecialchars($deg) ?>"><?= htmlspecialchars($deg) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Select Format</label>
                    <select name="format" id="formatSelect" class="form-select" required>
                        <option value="">-- Select Format --</option>
                        <?php foreach ($formatOptions as $format): ?>
                            <option value="<?= $format ?>"><?= $format ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Update Format</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('degreeSelect').addEventListener('change', function () {
    const degree = this.value;
    const formatSelect = document.getElementById('formatSelect');

    if (degree !== '') {
        const formData = new FormData();
        formData.append('ajax', 'get_format');
        formData.append('degree', degree);

        fetch('', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(format => {
                for (let option of formatSelect.options) {
                    option.selected = (option.value === format);
                }
            });
    } else {
        formatSelect.selectedIndex = 0;
    }
});
</script>

<?php require('footer.inc.php'); ?>
