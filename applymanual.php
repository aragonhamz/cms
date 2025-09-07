<?php
require('top.inc.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
date_default_timezone_set('Asia/Kolkata');
if (!isset($_SESSION['user_id'])){
    header('location: index.php');
    exit();
} 
$doctypeOptions = fetchDistinctValues($con, 'doctype', 'document');
$rollno         = '';
//$user_id        = $_SESSION['user_id'];
$doctype        = '';
$name           = '';
$dept           = '';
$school         = '';
$college        = '';
$degree         = '';
$degreetype     = '';
$honours        = '';
$regno          = '';
$gradyear       = '';
$heldin         = '';
$cgpa           = '';
$grade          = '';
$division       = '';
$appliedon = '';
$appid = '';
$payment = '';
$status = '';
$msgs = null;

// Handle file uploads
$regcard_path = '';
$marksheet_path = '';
$showPaymentDiv = false;
$doctype = '';
if (isset($_GET['rollno']) && !empty($_GET['rollno'])) {
    $rollno = mysqli_real_escape_string($con, $_GET['rollno']);
    if(isset($_GET['doctype']) && $_GET['doctype']=="DuplicateDegree"){
    $query = "SELECT * FROM dcer WHERE rollno = ? LIMIT 1"; 
    $doctype = mysqli_real_escape_string($con, $_GET['doctype']);
    }

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Fill your variables from the fetched data
        $name = $row['name'] ?? '';
        $rollno = $row['rollno'] ?? '';
        $regno = $row['regno'] ?? '';
        $dept = $row['dept'] ?? '';
        $school = $row['school'] ?? '';
        $college = $row['college'] ?? '';
        $degree = $row['degree'] ?? '';
        $degreetype = $row['degreetype'] ?? '';
        $honours = $row['honours'] ?? '';
        $gradyear = $row['gradyear'] ?? '';
        $heldin = $row['examheld'] ?? '';
        $cgpa = $row['cgpa'] ?? '';
        $grade = $row['grade'] ?? '';
        $division = $row['division'] ?? '';
    }
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values
    
    $rollno         = mysqli_real_escape_string($con, $_POST['manual_rollno']);
    $user_id        = $_SESSION['user_id'];
    $doctype        = mysqli_real_escape_string($con, $_POST['manual_doctype']) ?? '';
    $name           = mysqli_real_escape_string($con, $_POST['manual_name']);
    $dept           = mysqli_real_escape_string($con, $_POST['manual_dept']) ?? '';
    $school         = mysqli_real_escape_string($con, $_POST['manual_school'])?? '';
    $college        = mysqli_real_escape_string($con, $_POST['manual_college'])?? '';
    $degree         = mysqli_real_escape_string($con, $_POST['degree']);
    $degreetype     = mysqli_real_escape_string($con, $_POST['degreetype']);
    $honours        = mysqli_real_escape_string($con, $_POST['manual_honours'])?? '';
    $regno          = mysqli_real_escape_string($con, $_POST['manual_regno']);
    $gradyear       = mysqli_real_escape_string($con, $_POST['manual_gradyear']);
    $heldin       = mysqli_real_escape_string($con, $_POST['manual_heldin'])?? '';
    $cgpa           = mysqli_real_escape_string($con, $_POST['manual_cgpa'])?? '';
    $grade          = mysqli_real_escape_string($con, $_POST['manual_grade'])?? '';
    $division       = mysqli_real_escape_string($con, $_POST['manual_division'])?? '';
    //$printed_date = null;
    $selected_degree = $_POST['degree'] ?? '';
    $selected_degreetype = $_POST['degreetype'] ?? '';

    $appliedon = date('Y-m-d H:i:s');
    $appid = date('YmdHi').rand(1,999);//date('Ymd').rand(10,99999); 
    $payment = "Pending";
    $status = "Applied";
    $msgs = null;

    if(!isset($_POST['manual_doctype']) || $_POST['manual_doctype']== '' ){
        echo "<div class='alert alert-warning'>No Document type is selected.</div>";
        exit();
    }
    if(!isset($_POST['degreetype']) || $_POST['degreetype'] == ''){
        echo "<div class='alert alert-warning'>No Degree Type is selected.</div>";
        exit();
    }
    
    // Handle file uploads
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $max_file_size = 2 * 1024 * 1024; // 2MB

        // Start a transaction to keep consistency
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

                // Generate secure login token
        $login_token = bin2hex(random_bytes(16));
        $_SESSION['login_token'] = $login_token;
        $stmt = $con->prepare("UPDATE users SET login_token = ? WHERE id = ?");
        $stmt->bind_param("si", $login_token, $userid);
        $stmt->execute();
        $stmt->close();

        $regcard_path = handle_file_upload($_FILES['fileToUpload'], 'regcard'. '_'. $regno);
        $marksheet_path = handle_file_upload($_FILES['fileToUpload1'], 'marksheet'. '_'. $regno);

        $sql = "INSERT INTO mdcer (
                    user_id, doctype, rollno, name, dept, school, college, degree, degreetype,
                    honours, gradyear, examheld, regno, cgpa, grade, division,
                    appliedon, payment, status, msgs, application_id, regcard_file, marksheet_file
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssssssssssss",
            $user_id, $doctype, $rollno, $name, $dept, $school, $college, $degree, $degreetype,
            $honours, $gradyear, $heldin, $regno, $cgpa, $grade, $division, $appliedon, $payment, $status, $msgs, $appid,
            $regcard_path, $marksheet_path
        );

        //Mail send
        $subject = "Your Registration in Examination NEHU for Certificates";
    
      $mailmessage = "
              <!DOCTYPE html>
                <html lang='en'>
                <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>New Certificate Application with NEHU Certificate</title>
                  <link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css' rel='stylesheet'>
                  <style>
                    body {
                      font-family: Arial, sans-serif;
                      background-color: #f8f9fa;
                      color: #212529;
                      margin: 0;
                      padding: 0;
                    }
                    .container {
                      max-width: 600px;
                      margin: 30px auto;
                      padding: 20px;
                      background-color: #ffffff;
                      border-radius: 8px;
                      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                      border: 1px solid #e0e0e0;
                    }
                    .header {
                      text-align: center;
                      padding-bottom: 20px;
                      border-bottom: 1px solid #e0e0e0;
                    }
                    .content {
                      padding: 20px 0;
                      line-height: 1.6;
                    }
                    .btn-primary {
                      display: inline-block;
                      padding: 10px 20px;
                      color: #ffffff;
                      background-color: #007bff;
                      border-radius: 5px;
                      text-decoration: none;
                      font-weight: 500;
                    }
                    .footer {
                      text-align: center;
                      padding-top: 20px;
                      font-size: 14px;
                      color: #6c757d;
                      border-top: 1px solid #e0e0e0;
                    }
                  </style>
                </head>
                <body>

                <div class='container'>
                  <!-- Header -->
                  <div class='header'>
                    <img src='http://cc.nehu.ac.in/assets/img/logo.jpeg' width='50px' height='50px'/>
                    <h2>Welcome to NEHU Certificate Online management</h2>
                    
                  </div>

                  <!-- Content -->
                  <div class='content'>
                    <p>Hello <strong>" .$_SESSION['user_name']. "</strong>,</p>
                    <p>You have successfully applied for the certificate Application No: " .$appid." </p>
                    <p>Kindly make the payment, if not already, so that your applicaiton gets processed.</p>
                 
                    
                  </div>

                  <!-- Footer -->
                  <div class='footer'>
                    <p>&copy; 2025 Computer Center, NEHU. All rights reserved.</p>
                    
                  </div>
                </div>

                </body>
                </html>";
          $mail = new PHPMailer(true);

        if ($stmt->execute()) {
           
            // $_SESSION['appid'] = $appid; // Store app ID in session for payment
            // $_SESSION['name'] = $name;
            // $_SESSION['doctype'] = $doctype;
            // $_SESSION['amount'] = 1; // or dynamically set it based on doc type
              $mail->isSMTP();
              
                $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                $mail->Username   = 'aragon.hamz@gmail.com'; // SMTP username (your Gmail email)
                $mail->Password   = 'wxxaxvtzodjhilqf'; // SMTP password (your Gmail password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('aragon.hamz@gmail.com', 'Examination NEHU');
                $mail->addAddress($email); // Add a recipient
                  $mail->addReplyTo('aragon.hamz@gmail.com', 'Support');
                  $mail->addBcc("hamar.kharshiing@gmail.com");
                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $mailmessage;

                $mail->send();

            $showPaymentDiv = true;
             echo "<div class='alert alert-success'>Your application is submitted Successfully, <a href='myapplications.php'>View your application</a> and please check your email.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error submitting your application.</div>";
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
                      Manual Certificate application
                    </h6>
                  </div>
    <div class="card-body">
            <?php if ($showPaymentDiv): ?>
                    <?php if (isset($_SESSION['user_email'])): ?>
                <!-- <div id="paymentSection" class="mt-4 p-4 border rounded bg-light"> -->
                    <h4 class="mb-3 text-success">You have been applied successfully! Please proceed with payment. If you do not make the payment your form will not be submitted</h4>
                    <form method="post" id="paymentForm" action="ccavRequestHandler.php">
                        <input type="hidden" name="tid" value="<?php echo $appid. rand(100,999); ?>" />
                        <input type="hidden" name="merchant_id" value="213372" />
                        <input type="hidden" name="order_id" value="<?php echo $appid; ?>" />
                        <input type="hidden" name="amount" value="<?php echo getamount($row['doctype']); ?>" />
                        <input type="hidden" name="currency" value="INR" />
                        <input type="hidden" name="redirect_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                        <input type="hidden" name="cancel_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                        <input type="hidden" name="language" value="EN" />
                        <input type="hidden" name="merchant_param1" value="<?php echo $row['doctype']?> Certificate" />
                        <input type="hidden" name="merchant_param2" value="ManualCertificate" />
                        <input type="hidden" name="merchant_param5" value="<?php echo $login_token; ?>" />
                        <button type="button" class="btn btn-success" onclick="checkLoginBeforePayment()">Pay Now</button>

                    </form>
                <!-- </div> -->
                 <?php else: ?>
                    <a href="login.php" class="btn btn-primary profile-button">Login to apply</a>
                <?php endif; ?>
            <?php endif; ?>
    <?php if (!$showPaymentDiv): ?>
    <div id="manualForm" class="mt-4 p-4 border rounded">

    <?php


    ?>
    <h4>Manual Certificate application (Students passed out before 2020)</h4>
     <div class='alert alert-info' role="alert">
                    <h5>Attention </h5>
                    <hr class="border border-primary border-3 opacity-75">
                <ul>
                    <li><h5>Who can apply? : Students passed out before 2020.</h5></li>
                    <li><h5>Once applied, the form can be downloaded and to be forwarded by the head of the dept/college</h5></li>
                    <!-- <li><h5>Provisional Certificate is not available at the moment</h5></li> -->
                </ul>    
               
                </div>
    <hr class="border-primary border-3 opacity-75">
    <form method="post" id="myForm" enctype="multipart/form-data" class="was-validated">
    <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Select the Certificate Type</h6></div>
            <div class="col-sm-9 text-secondary">
        <select id="manual_doctype" name="manual_doctype" class="form-control" required <?php if(!empty($doctype)) echo 'disabled'; ?>>
            <!-- <option value="">--Select Document Type--</option>
            <option value="Degree" <?php if($doctype == 'Degree') echo 'selected'; ?>>Degree Certificate</option>
            <option value="DuplicateDegree" <?php if($doctype == 'DuplicateDegree') echo 'selected'; ?>>Duplicate Degree Certificate</option>
           
        </select>
        <select name="doctype" id="filter_doctype" class="form-select" required> -->
                                <?= generateOptions($doctypeOptions, $selectedDoctype); ?>
         </select>

        <div class="valid-feedback">
            <i class="fa-solid fa-thumbs-up"></i> Looks good!
        </div>
        <div class="invalid-feedback">
            * Document Type is required
        </div>
    </div>
        </div>
    <hr>
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Degree</h6></div>
            <div class="col-sm-9 text-secondary">
       

            <select id="form_category" name="degree" class="form-control" required>
            <option value="" selected disabled>--Select Your Degree--</option>
                <?php
             
                // Example query to fetch degrees
                $degree_query = "SELECT degree, degreetype FROM degreetype ORDER BY degree ASC";
                $degree_result = mysqli_query($con, $degree_query);
                if(mysqli_num_rows($degree_result) > 0) {
                    while($row = mysqli_fetch_assoc($degree_result)) {
                        $degree_val = htmlspecialchars($row['degree']);
                        $degreetype_val = htmlspecialchars($row['degreetype']);
                        $selected = ($selected_degree == $row['degree']) ? 'selected' : '';
                        echo '<option value="'. $degree_val .'" data-degreetype="'. $degreetype_val .'" '.$selected.'>'
                            . $degree_val . 
                            '</option>';
                    }
                } else {
                    echo '<option disabled>No degrees available</option>';
                }
                ?>
            </select>

            <input type="hidden" name="degreetype" id="degreetype" value="<?= htmlspecialchars($selected_degreetype) ?>" placeholder="<?= htmlspecialchars($selected_degreetype) ?>"/>

            <div class="valid-feedback">
            <i class="fa-solid fa-thumbs-up"></i>Looks good!
                </div>
                <div class="invalid-feedback">
                    * Degree is required
                </div>

            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Roll No.</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" placeholder="Enter your Roll No." name="manual_rollno" id="rollno" value="<?php echo htmlspecialchars($rollno); ?>" required>
                <div class="valid-feedback">
                <i class="fa-solid fa-thumbs-up"></i>Looks good!
                </div>
                <div class="invalid-feedback">
                    * Roll No. is required
                </div>
            </div>
        </div>
        <hr>

        <!-- Full Name -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Full Name</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" placeholder="Enter your FULL NAME (In Capital Letters)" value="<?php echo htmlspecialchars($name); ?>" name="manual_name" required>
                <div class="valid-feedback">
                <i class="fa-solid fa-thumbs-up"></i>Looks good!
                </div>
                <div class="invalid-feedback">
                    * Full Name is required
                </div>
            </div>
        </div>
        <hr>

        <!-- Department -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Department</h6></div>
            <div class="col-sm-9 text-secondary">
                <select class="form-control" name="manual_dept" id="dept">
                    <option value="" selected>--Select your Department name in the Campus, if applicable--</option>
                    <option value="Architecture" <?php if($dept == 'Architecture') echo 'selected'; ?>>Architecture</option>
                    <option value="Electronics And Communication Engineering" <?php if($dept == 'Electronics And Communication Engineering') echo 'selected'; ?>>Electronics And Communication Engineering</option>
                </select>
            </div>
        </div>
        <hr>

        <!-- School of -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">School of</h6></div>
            <div class="col-sm-9 text-secondary">
                <select class="form-control" name="manual_school" id="school">
                    <option value="" selected >--Select your School of name in the Campus, if applicable --</option>
                    <option value="Basic Sciences" <?php if($school == 'Basic Sciences') echo 'selected'; ?>>Basic Sciences</option>
                    <option value="Life Sciences" <?php if($school == 'Life Sciences') echo 'selected'; ?>>Life Sciences</option>
                </select>
            </div>
        </div>
        <hr>

        <!-- College -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">College</h6></div>
            <div class="col-sm-9 text-secondary">
                
                <select class="form-control" name="manual_college" id="college">
                    <option value="" selected >--Select your Affiliated college name, if applicable --</option>
                    <option value="Synod" <?php if($college == 'Synod') echo 'selected'; ?>>Synod</option>
                    <option value="Loyola" <?php if($college == 'Loyola') echo ''; ?>>Loyola</option>
                </select>
            </div>
        </div>
        <hr>

        <!-- Honours/Specialization -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Honours/Specialization</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="manual_honours" placeholder="Enter your Honours/Specialization, if applicable" value="<?php echo htmlspecialchars($honours); ?>">
            </div>
        </div>
        <hr>

        <!-- Registration Number -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Registration Number</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="manual_regno"  placeholder="Enter your Registration Number" value="<?php echo htmlspecialchars($regno); ?>" required>
                <div class="valid-feedback">
                <i class="fa-solid fa-thumbs-up"></i>Looks good!
                </div>
                <div class="invalid-feedback">
                    * Registration No. is required
                </div>
            </div>
        </div>
        <hr>

        <!-- Year of Passing -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Year of Passing</h6></div>
            <div class="col-sm-9 text-secondary">
                <!-- <input type="text" class="form-control" name="manual_gradyear" placeholder="Enter degree passing year" value="<?php echo htmlspecialchars($gradyear); ?>" required> -->
                <select class="form-control" name="manual_gradyear" id="gradyear" required>
                    <option value="" selected disabled>--Select your degree passing year --</option>
                    <option value="2025" <?php if($gradyear == '2025') echo 'selected'; ?>>2025</option>
                    <option value="2020" <?php if($gradyear == '2020') echo 'selected'; ?>>2020</option>
                </select>
                <div class="valid-feedback">
                <i class="fa-solid fa-thumbs-up"></i>Looks good!
                </div>
                <div class="invalid-feedback">
                    * Year of passing is required
                </div>
            </div>
        </div>
        <hr>

         <!-- Exam held in -->
         <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Exam held in</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="manual_heldin" placeholder="Enter the Exam held in e.g March, 2025, if applicable" value="<?php echo htmlspecialchars($heldin); ?>">
            </div>
        </div>
        <hr>

        <!-- CGPA -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">CGPA</h6></div>
            <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" placeholder="Enter your CGPA, if applicable" name="manual_cgpa" value="<?php echo htmlspecialchars($cgpa); ?>">
            </div>
        </div>
        <hr>

        <!-- Grade -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Grade</h6></div>
            <div class="col-sm-9 text-secondary">
                
                <select class="form-control" name="manual_grade" id="grade">
                    <option value="" selected >--Select your Grade, if applicable --</option>
                    <option value="O" <?php if($grade == 'O') echo 'selected'; ?>>O</option>
                    <option value="A" <?php if($grade == 'A') echo 'selected'; ?>>A</option>
                </select>
            </div>
        </div>
        <hr>

        <!-- Division -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Division</h6></div>
            <div class="col-sm-9 text-secondary">
              
                <select class="form-control" name="manual_division" id="division">
                    <option value="" selected >--Select your Division, if applicable --</option>
                    <option value="First Division" <?php if($division == 'First Division') echo 'selected'; ?>>First Division</option>
                    <option value="Pass" <?php if($division == 'Pass') echo 'selected'; ?>>Pass</option>
                </select>
            </div>
        </div>
        <hr>

        <!-- Upload Registration Card -->
        <div class="row">
            <div class="col-sm-3"><h6 class="mb-0">Upload Registration Card</h6></div>
            <div class="col-sm-9 text-secondary">
            <div class="file-upload">
                    <div class="file-select">
                        <div class="file-select-button"></div>
                        <div class="file-select-name" id="noFile"></div> 
                        <input type="file" name="fileToUpload" class="form-control" id="fileToUpload" required>
                        <div class="valid-feedback">
                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                        </div>
                        <div class="invalid-feedback">
                            * Registration Card file upload is required
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
                        <div class="file-select-button"></div>
                        <div class="file-select-name" id="noFile"></div> 
                        <input type="file" name="fileToUpload1" class="form-control" id="fileToUpload1" required>
                        <div class="valid-feedback">
                        <i class="fa-solid fa-thumbs-up"></i>Looks good!
                        </div>
                        <div class="invalid-feedback">
                            * Marksheet file upload is required
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <hr>

        <!-- Submit -->
        <button type="submit" id="submitBtn" class="btn btn-success" name="manualapply">Apply and proceed for payment</button>
    </form>
</div>
<?php endif; ?>
    </div>
 </div>
 <script>

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

document.getElementById("submitBtn").addEventListener("click", function(e) {
  e.preventDefault();

  const form = document.getElementById("myForm");

  // First, run built-in validation
  if (!form.checkValidity()) {
    form.reportValidity(); // show the native validation
    return;
  }

  // Then show SweetAlert confirmation
  Swal.fire({
    title: 'Please verify your data before submission. Once submitted it is final',
    text: 'Are you sure?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, submit it!',
    cancelButtonText: 'No, Let me verify'
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit(); // now actually submit the form
    }
  });
});

$(document).ready(function() {
    $('#manual_doctype').select2({
        placeholder: "--Select Document Type--",
        allowClear: true,
        width: '100%' // ensures it fits well in form-control layout
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('form_category');
    const hidden = document.getElementById('degreetype');
    const form = select.closest('form'); // Get the parent form

    function updateDegreetype() {
        const selectedOption = select.options[select.selectedIndex];
        const degreetype = selectedOption.getAttribute('data-degreetype') || '';
        hidden.value = degreetype;
    }

    // Update on load
    updateDegreetype();

    // Update on change
    select.addEventListener('change', updateDegreetype);

    // 🔒 Ensure value is set before submit
    form.addEventListener('submit', updateDegreetype);
});


</script>
<?php require('footer.inc.php') ?>