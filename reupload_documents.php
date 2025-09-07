<?php 
require('top.inc.php');

// isAdmin();

if (!isset($_SESSION['user_id']) || !isset($_GET['app_id']) || !isset($_GET['type'])) {
    die("Unauthorized access.");
}

$userid = $_SESSION['user_id'];
$type = $_GET['type'] ?? 'DirectCertificate';
$db = ($type == 'DirectCertificate') ? 'dcer' : 'mdcer';

$encrypted_id = get_safe_value($con, $_GET['app_id'] ?? '');
$app_id = decryptAppId($encrypted_id);

// Fetch application data
$stmt = $con->prepare("SELECT * FROM {$db} WHERE application_id = ? AND user_id = ?");
$stmt->bind_param("ii", $app_id, $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Application not found or access denied.');
}

$row = $result->fetch_assoc();
$regno = $row['regno'] ?? '';
if (empty($regno)) {
    die('Registration number not found for this application.');
}
// if (strtolower($row['status']) !== 'rejected') {
//     die('Re-upload allowed only for rejected applications.');
// }

// Handle upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $max_file_size = 2 * 1024 * 1024; // 2MB
    $upload_dir = 'uploads/';

    function handle_file_upload($file, $prefix) {
        global $allowed_types, $max_file_size, $upload_dir;

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return '';

        if (!in_array($file['type'], $allowed_types)) return '';

        if ($file['size'] > $max_file_size) return '';

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_name = $prefix . '_' . uniqid() . '.' . $ext;
        $target_path = $upload_dir . basename($unique_name);

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return $target_path;
        }

        return '';
    }

    $regcard_path = handle_file_upload($_FILES['fileToUpload'], 'regcard_' . $regno);
    $marksheet_path = handle_file_upload($_FILES['fileToUpload1'], 'marksheet_' . $regno);

    $regUploaded = !empty($regcard_path);
    $marksheetUploaded = !empty($marksheet_path);

    if ($regUploaded && $marksheetUploaded) {
        $update = $con->prepare("UPDATE {$db} SET regcard_file = ?, marksheet_file = ?, status = 'Reuploaded', msgs ='Waiting for Approval' WHERE application_id = ?");
        $update->bind_param("ssi", $regcard_path, $marksheet_path, $app_id);
        $update->execute();
    } elseif ($regUploaded) {
        $update = $con->prepare("UPDATE {$db} SET regcard_file = ?, status = 'Reuploaded', msgs ='Waiting for Approval' WHERE application_id = ?");
        $update->bind_param("si", $regcard_path, $app_id);
        $update->execute();
    } elseif ($marksheetUploaded) {
        $update = $con->prepare("UPDATE {$db} SET marksheet_file = ?, status = 'Reuploaded', msgs ='Waiting for Approval' WHERE application_id = ?");
        $update->bind_param("si", $marksheet_path, $app_id);
        $update->execute();
    }

    if ($regUploaded || $marksheetUploaded) {
        if($type=='DirectCertificate') {
            
             echo "<div class='alert alert-success'>Document(s) re-uploaded successfully.<a href='mydegreeapplications'>View Now</a></div>";
        } else {

            echo "<div class='alert alert-success'>Document(s) re-uploaded successfully.<a href='myapplications'>View Now</a></div>";
        }
       
    } else {
        echo "<div class='alert alert-warning'>No file was uploaded or all uploads failed.</div>";
    }
}

?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
         
		  <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  Re-Upload Documents for Application ID: <?= htmlspecialchars($app_id) ?>
                </h6>
              </div>
              <div class="card-body">
                <h4>Please reupload only the required document</h4>
                <form method="POST" enctype="multipart/form-data" class="was-validated">
                     <!-- Upload Registration Card -->
                        <div class="row">
                            <div class="col-sm-3"><h6 class="mb-0">Upload Registration Card</h6></div>
                            <div class="col-sm-9 text-secondary">
                            <div class="file-upload">
                                    <div class="file-select">
                                        <div class="file-select-button"></div>
                                        <div class="file-select-name" id="noFile"></div> 
                                        <input type="file" name="fileToUpload" class="form-control" id="fileToUpload">
                                        <!-- <div class="valid-feedback">
                                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            * Registration Card file upload is required
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr>

                        <!-- Upload Marksheet -->
                        <div class="row">
                        <div class="col-sm-3"><h6 class="mb-0">Upload Marksheet</h6></div>
                            <div class="col-sm-9 text-secondary">
                                <div class="file-upload">
                                    <div class="file-select">
                                        <div class="file-select-button"></div>
                                        <div class="file-select-name" id="noFile"></div> 
                                        <input type="file" name="fileToUpload1" class="form-control" id="fileToUpload1">
                                        <!-- <div class="valid-feedback">
                                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            * Marksheet file upload is required
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
              </div>
          </div>
          </div>
       </div>
    </div>
</div>