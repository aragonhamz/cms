<?php
require('top.inc.php');
?>

<?php
require 'vendor/autoload.php'; // Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['size'] > 0) {
    echo "<script>document.getElementById('loading').style.display = 'block';</script>";
    $file = $_FILES['excelFile']['tmp_name'];
    
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            foreach ($data as $index => $row) {
                if ($index == 0) continue; // Skip header row
                
                $name = $row[0];
                $email = $row[1];
                $age = $row[2];
                
                $stmt = $con->prepare("INSERT INTO dcer (cerno, rollno, name, dept, school, college, degree, degreetype, honours, gradyear, examheld, regNo, ofyear, cgpa, grade, division) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssssssssssssss", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15]);
                $stmt->execute();
            }
            $stmt->close();
            echo "<script>document.getElementById('loading').style.display = 'none';</script>";
            echo "<div class='alert alert-success'>Data imported successfully!</div>";
        } catch (Exception $e) {
            echo "<script>document.getElementById('loading').style.display = 'none';</script>";
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Please select a file to upload.</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Upload Excel File</h2>
    <form method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
        <div class="mb-3">
            <label for="excelFile" class="form-label">Choose Excel File</label>
            <input type="file" class="form-control" name="excelFile" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
<div id="loading" style="display:none; text-align: center; margin-top: 20px;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Uploading and processing...</p>
    </div>
<script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
    </script>
<?php

require('footer.inc.php');
?>