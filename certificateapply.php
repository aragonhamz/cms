<?php
require('top.inc.php');
date_default_timezone_set('Asia/Kolkata');
if (!isset($_SESSION['user_id'])){
    header('location: index.php');
    exit();
} 
$doctypeOptions = fetchDistinctValues($con, 'doctype', 'document');
$showPaymentDiv = false;
$selectedYear = isset($_POST['year']) ? (int)$_POST['year'] : null;
$cerid='';
$cerno = '';
$rollno ='';
$name ='';
$degree ='';
$dept='';
$college ='';
$honours ='';
$regno= '';
$gradyear ='';
$cgpa= '';
$grade= '';
$division ='';
$appliedon = '';
$payment = '';
$regcard_path = '';
$marksheet_path = '';
$doctype = '';

$scrollToDetails = false;
if(isset($_POST['searchrollno'])){
    $rollno = mysqli_escape_string($con,$_POST['rollno']);
    $regno = mysqli_escape_string($con,$_POST['regno']);
    $doctype = mysqli_real_escape_string($con, $_POST['doctype']) ?? '';
    
    if (!empty($rollno)) {
        $stmt = $con->prepare("SELECT * FROM `dcer` WHERE `rollno` = ? and doctype = ?");
        $stmt->bind_param("ss", $rollno, $doctype);
    } elseif (!empty($regno)) {
        $stmt = $con->prepare("SELECT * FROM `dcer` WHERE `regno` = ? and doctype = ?");
        $stmt->bind_param("ss", $regno, $doctype);
    } 
    
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_assoc();
    $stmt->close();
    if ($row) {  
        $userid =$row['user_id'];
        $doctype =$row['doctype'];
        $cerid =$row['cer_id'];
        $cerno =$row['cerno'];
        $rollno =$row['rollno'];
        $name =$row['name'];
        $degree =$row['degree'];
        $dept=$row['dept'];
        $college =$row['college'];
        $honours =$row['honours'];
        $regno= $row['regno'];
        $gradyear =$row['gradyear'];
        $school = $row['school'];
        $cgpa= $row['cgpa'];
        $grade= $row['grade'];
        $division =$row['division'];
        $appliedon = $row['appliedon'];
        $payment = $row['payment'];
        $scrollToDetails = true;
    }else{
        // echo '<script>  
        // swal("Error", "No Record found, Please enter correct Roll No. or Registration No.", "error");  
        // </script>';  
        echo '<script>
            Swal.fire({
                icon: "warning",
                title: "No Record Found",
                html: `<div style="text-align: left;">
                        Reason:<br>
                        1. Maybe incorrect RollNo or RegNo<br>
                        2. Data is available only from 2021 passing year. For passing year before 2020, please apply manually<br>
                        3. Results issue — please visit examination section to update and rectify<br>
                        4. Certificate already received earlier.<br>
                        5. Data not uploaded, try again later.
                    </div>`,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Apply Manually",
                denyButtonText: "Search Again",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show the manual application form
                    //document.getElementById("manualForm").style.display = "block";
                    //window.scrollTo({ top: document.getElementById("manualForm").offsetTop, behavior: "smooth" });
                    window.location.href = "applymanual.php";
                } else if (result.isDenied) {
                    // Clear form fields and focus on roll no input
                    document.querySelector("input[name=rollno]").value = "";
                    document.querySelector("input[name=regno]").value = "";
                    document.querySelector("input[name=rollno]").focus();
                }
            });
            </script>';
    }
}
if(isset($_POST['applynow'])){
    $cerid = mysqli_escape_string($con,$_POST['cerid']) ?? '';
    $regno= mysqli_escape_string($con,$_POST['regno']) ?? '';
    $currentDateTime = date('Y-m-d H:i:s');
    $payment = "Pending";
    $status = "Applied";
    $userid = $_SESSION['user_id'];
    $appid = $appid = date('YmdHi').$cerid;
        if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE) {
            echo "<div class='alert alert-warning'>No Registration file is selected to upload.</div>";
            exit();
        }

        if (!isset($_FILES['fileToUpload1']) || $_FILES['fileToUpload1']['error'] === UPLOAD_ERR_NO_FILE) {
            echo "<div class='alert alert-warning'>No Marksheet file is selected to upload.</div>";
            exit();
        }


     // Handle file uploads
            // File upload settings
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $max_file_size = 2 * 1024 * 1024; // 2MB
            
            $con->begin_transaction();

        try {
            function handle_file_upload($file, $prefix) {
                global $allowed_types, $max_file_size;
    
                if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return '';
    
                if (!in_array($file['type'], $allowed_types)) return '';
    
                if ($file['size'] > $max_file_size) return '';
    
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $unique_name = $prefix . '_' . uniqid() . '.' . $ext;
                $upload_dir = 'uploads/';
                $target_path = $upload_dir . basename($unique_name);
    
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    return $target_path;
                }
    
                return '';
            }

            $login_token = bin2hex(random_bytes(16));
            $_SESSION['login_token'] = $login_token;
            $stmt = $con->prepare("UPDATE users SET login_token = ? WHERE id = ?");
            $stmt->bind_param("si", $login_token, $userid);
            $stmt->execute();
            $stmt->close();
                    
            $regcard_path = handle_file_upload($_FILES['fileToUpload'], 'regcard'. '_'. $regno);
            $marksheet_path = handle_file_upload($_FILES['fileToUpload1'], 'marksheet'. '_'. $regno);
            $stmt = $con->prepare("UPDATE dcer SET appliedon = ?, payment = ?, status = ?, user_id=?, application_id=?, regcard_file=?, marksheet_file=? WHERE cer_id = ?");
            $stmt->bind_param("sssisssi", $currentDateTime, $payment, $status, $userid, $appid, $regcard_path, $marksheet_path, $cerid);
            if($stmt->execute())
            {     
                //echo '<script>swal("Success!", "You have been applied successfully! Please proceed with payment.", "success");</script>';
                // Add payment form redirect
                $showPaymentDiv = true;
            } else {
                echo '<script>swal("Error", "Error saving data", "error");</script>';
                
            }
            $stmt->close();
            $con->commit();
     } catch (Exception $e) {
         // 6. Rollback on error
         $con->rollback();
         echo "<div class='alert alert-danger'>Error updating: " . $e->getMessage() . "</div>";
     }
 }

?>

    <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Certificate application
                    </h6>
                  </div>
    <div class="card-body">
    
        <div class='mt-4 p-4 border rounded'>
                <?php if ($showPaymentDiv): ?>
                    <?php if (isset($_SESSION['user_email'])): ?>
                <!-- <div id="paymentSection" class="mt-4 p-4 border rounded bg-light"> -->
                    <h4 class="mb-3 text-success">You have been applied successfully! Please proceed with payment. If you do not make the payment your form will not be submitted/processed</h4>
                    <form method="post" id="paymentForm" action="ccavRequestHandler.php">
                        <input type="hidden" name="tid" value="<?php echo $appid. rand(100,999); ?>" />
                        <input type="hidden" name="merchant_id" value="213372" />
                        <input type="hidden" name="order_id" value="<?php echo $cerid; ?>" />
                        <input type="hidden" name="amount" value="<?php echo getamount($row['doctype']); ?>" />
                        <input type="hidden" name="currency" value="INR" />
                        <input type="hidden" name="redirect_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                        <input type="hidden" name="cancel_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                        <input type="hidden" name="language" value="EN" />
                        <input type="hidden" name="merchant_param1" value="Degree Certificate" />
                        <input type="hidden" name="merchant_param2" value="DirectCertificate" />
                        <input type="hidden" name="merchant_param5" value="<?php echo $login_token; ?>" />
                        <!-- <input type="submit" value="Pay Now" class="btn btn-success" /> -->
                        <button type="button" class="btn btn-success" onclick="checkLoginBeforePayment()">Pay Now</button>

                    </form>
                <!-- </div> -->
                 <?php else: ?>
                    <a href="login.php" class="btn btn-primary profile-button">Login to apply</a>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!$showPaymentDiv): ?>
                <div class="mt-0 p-5 bg-info text-white rounded">
                <h4>Attention </h4>
                    <hr class="border border-primary border-3 opacity-75">
                <ul>
                    <li><h5>Students passed out before 2019 can  <a href="applymanual.php">apply manually here</h5></a></li>
                    <li><h5>Record of students passed out from 2020 only are available (only those who have not applied earlier)</h5></li>
                    <li><h5>Provisional Certificate is not available at the moment</h5></li>
                </ul>    
                </div>
                <!-- <div class='alert alert-info' role="alert">
                    <h5>Attention </h5>
                    <hr class="border border-primary border-3 opacity-75">
                <ul>
                    <li><h5>Students passed out before 2020 can  <a href="applymanual.php">apply manually here</h5></a></li>
                    <li><h5>Record of students passed out from 2021 only are available (only those who have not applied earlier)</h5></li>
                    <li><h5>Provisional Certificate is not available at the moment</h5></li>
                </ul>    
               
                </div> -->
            <!-- <div class='mt-4 p-4 border rounded' id="details"> -->
                    
            <hr class="border border-primary border-3 opacity-75">
                    <form method="post" class="was-validated">
                    <div class="row mt-4 mb-3">
                            <div class="col-sm-3"><h4 class="mb-0">Select Document Type</h4></div>
                            <div class="col-sm-9 text-secondary">
                           <select name="doctype" id="filter_doctype" class="form-select" required>
                                <?= generateOptions($doctypeOptions, $selectedDoctype); ?>
                            </select>
                            <div class="valid-feedback">
                            <i class="fa-solid fa-thumbs-up"></i>Looks good!
                            </div>
                            <div class="invalid-feedback">
                                * Document Type is required
                            </div>
                            </div>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Roll Number:</label>
                            <!-- <input type='text' name="rollno" class='form-control' placeholder='Enter your roll number'> -->
                            <input type='text' name="rollno" class='form-control' placeholder='Enter your Roll number' value="<?php echo isset($_POST['rollno']) ? htmlspecialchars($_POST['rollno']) : ''; ?>">
                    
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Registration No:</label>
                            <input type='text' name="regno" class='form-control' placeholder='Enter your Registration No' value="<?php echo isset($_POST['regno']) ? htmlspecialchars($_POST['regno']) : ''; ?>">
                        </div>
                        <button type='submit' name="searchrollno" class='btn btn-success'  onclick="return validateSearchForm()">Search</button>
                    </form>
            <!-- </div> -->
            <?php endif; ?>
        </div>
     </div>       
   
 
   
    <!-- Retrieve the data from the database-->
     <?php if($payment != '' && $payment == 'Success' && !$showPaymentDiv && $userid==$_SESSION['user_id']){
        echo '<script>
   
            Swal.fire({
            icon: "info",
            title: "This '.$doctype.' Certificate already Applied, you can only apply for Duplicate",
            html: "This Roll No had already applied on <b>' . date('d-M-Y, h:i A', strtotime($appliedon)) . ' by </b><br><br>Do you want to apply a duplicate?",
            showCancelButton: true,
            confirmButtonText: "Apply Duplicate",
            cancelButtonText: "Close"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "applymanual.php?rollno='.$rollno.'&&doctype=Duplicate'.$doctype.'";
            }
        });
        </script>';
     ?>
     
     <?php }elseif($payment != '' && $payment != 'Success' && !$showPaymentDiv && $userid==$_SESSION['user_id']){
        echo '<script>
   
            Swal.fire({
            icon: "info",
            title: "This '.$doctype.' Certificate already Applied, but payment is pending",
            html: "This Roll No had already applied on <b>' . date('d-M-Y, h:i A', strtotime($appliedon)) . '</b><br><br>Proceed to view your application and make the payment?",
            showCancelButton: true,
            confirmButtonText: "Go",
            cancelButtonText: "Close"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "mydegreeapplications.php";
            }
        });
        </script>';
    }else{ ?>

    <div class='mt-4 p-4 border rounded' id="details" style="<?php echo ($name != '') ? '' : 'display:none;'; ?>">
    <form method="post" id="myForm" enctype="multipart/form-data"  class="needs-validation" novalidate>
            <div class="row">
             <div class="col-md-12">
                <div class="card mb-3" id="view-mode">
                    <div class="card-body">
                    <div class="h4 pb-2">    
                        <h3 class="display-5 text-danger">Record found:</h3>
                        <h5>Please verify your record before applying</h5>
                    </div>
                    <hr class="border border-primary border-3 opacity-75">
                    <div class="row">
                        <div class="col-sm-12">
                        <h4 class="mt-1">Certificate Applicable to apply : <?php echo $doctype; ?> Certificate</h4>
                        </div>
                        <!-- <div class="col-sm-9 text-secondary mt-4">
                        
                        </div> -->
                    </div>
                    <!-- <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mt-4">Certificate No.</h6>
                        </div>
                        <div class="col-sm-9 text-secondary mt-4">
                        <?php echo $cerno; ?><input type="hidden" value="<?php echo $cerid; ?>">
                        </div>
                    </div>
                    <hr> -->
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mt-4">Roll No.</h6>
                        </div>
                        <div class="col-sm-9 text-secondary mt-4">
                        <?php echo $rollno; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $name; ?>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Degree</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $degree; ?>
                        </div>
                    </div>
                    <hr>
                    
                    <?php if($dept != '' || $dept != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Department</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $dept; ?>
                        </div>
                    </div>
                    <hr>
                    <?php }?>
                    <?php if($college != '' || $college != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">College</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $college; ?>
                        </div>
                    </div>
                    <hr>
                    <?php }?>
                    <?php if($honours != '' || $honours != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Honours/Specialization</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $honours; ?>
                        </div>
                    </div>
                    <hr>
                     <?php }?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Registration Number</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $regno; ?>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Year of Passing</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $gradyear; ?>
                        </div>
                    </div>
                    <hr>

                    <?php if($cgpa != '' || $cgpa != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">CGPA</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $cgpa; ?>
                        </div>
                    </div>
                    <hr> <?php }?>

                    <?php if($grade != '' || $grade != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Grade</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $grade; ?>
                        </div>
                    </div>
                    <hr>
                    <?php }?>
                    <?php if($division != '' || $division != null){ ?>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Division.</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $division; ?>
                        </div>
                    </div>
                    <hr>
                    <?php }?>
                            <!-- Upload Registration Card -->
                        <div class="row">
                            <div class="col-sm-3"><h6 class="mb-0">Upload Registration Card</h6></div>
                            <div class="col-sm-9 text-secondary">
                            <div class="file-upload">
                                    <div class="file-select">
                                        <div class="file-select-button" id="fileName"></div>
                                        <div class="file-select-name" id="noFile"></div> 
                                        <input type="file" name="fileToUpload" class="form-control" id="fileToUpload" required>
                                        <div class="valid-feedback">
                                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select a file to upload
                                        </div>
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
                                        <div class="file-select-button" id="fileName"></div>
                                        <div class="file-select-name" id="noFile"></div> 
                                        <input type="file" name="fileToUpload1" class="form-control" id="fileToUpload1" required>
                                        <div class="valid-feedback">
                                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select a file to upload
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-12">
                        <input type="hidden" name="cerid" value="<?php echo $cerid; ?>">  <!-- Preserve cer_id -->
                        <input type="hidden" name="regno" value="<?php echo $regno; ?>">  <!-- Preserve cer_id -->
                        <button class="btn btn-info mt-3" type="submit" name="applynow">Apply Now</button>
                        </div>
                    </div>
                    </div>				
                </div>
            </div>
        </div> 
    </form>
    </div>
          <?php }?>
</div>  
<script>
function validateSearchForm() {
    const roll = document.querySelector("input[name='rollno']").value.trim();
    const reg = document.querySelector("input[name='regno']").value.trim();

    if (roll === "" && reg === "") {
        Swal.fire({
            icon: "error",
            title: "Roll No Or Registration No cannot be empty",
            text: "Please enter either Roll No or Reg No"
        }).then(() => {
            document.querySelector("input[name='rollno']").focus();
        });
        return false; // 🛑 prevent form from submitting
    }

    return true; // ✅ allow form to submit
}

function checkLoginBeforePayment() {
    fetch("check_session.php")
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // Submit the payment form
                document.getElementById("paymentForm").submit();
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Session Expired",
                    text: "Your session has expired. Please log in again to proceed with payment.",
                    confirmButtonText: "Login"
                }).then(() => {
                    window.location.href = "login.php";
                });
            }
        })
        .catch(err => {
            console.error("Error checking session:", err);
        });
}

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

</script>
<?php if ($scrollToDetails): ?>
<script>
    // Wait for the page to fully load
    window.onload = function() {
        const details = document.getElementById("details");
        if (details) {
            details.scrollIntoView({ behavior: 'smooth' });
        }
    };
</script>
<?php endif; ?>
<?php require('footer.inc.php') ?>