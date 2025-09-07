<?php
require('top.inc.php');
//isAdmin();

if (!isset($_SESSION['user_id']) || !isset($_GET['app_id']) || !isset($_GET['type'])){
    die("Unauthorized access.");
}

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
$ofyear= '';
$cgpa= '';
$grade= '';
$division ='';
$appliedon = '';
$payment = '';
$regcard_path = '';
$marksheet_path = '';
$doctype = '';
$decryptedid ='';
if(isset($_GET['app_id']) && $_GET['app_id']!=''){
	
	$type=get_safe_value($con,$_GET['type']);
    $db =($type=='DirectCertificate') ? 'dcer' : 'mdcer';
    //$db = ($_GET['type'] == 'ManualCertificate') ? 'mdcer' : 'dcer';
    $encrypted_id = get_safe_value($con, $_GET['app_id'] ?? '');
    $app_id = decryptAppId($encrypted_id);
    //$stmt = $con->prepare("SELECT * from $db  WHERE application_id = ?");
    $stmt = $con->prepare("SELECT * from ".$db."  WHERE application_id = ?");
        $stmt->bind_param("i", $app_id);
        $stmt->execute();
        $result = $stmt->get_result();
		    $row = $result->fetch_assoc();
        if($row){
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
            //$ofyear= $row['ofyear'];
            $cgpa= $row['cgpa'];
            $grade= $row['grade'];
            $division =$row['division'];
            $appliedon = $row['appliedon'];
            $payment = $row['payment'];
            $paymentOn = $row['paymentOn'];
            $amount = $row['amount'];
            $transactionID = $row['transactionID'];
            $bank_ref_no = $row['bank_ref_no'];
            $status = $row['status'];
        
        } else {
            echo "<div class='alert alert-danger'>Application not found.</div>"; 
            
        }
        $stmt->close();
  
		
	} else {
		echo "<div class='alert alert-danger'>No app ID provided</div>";
		exit;
	}
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
    $appid = mysqli_real_escape_string($con, $_POST['app_id']);
    $status = 'Approved';
    $msg = mysqli_real_escape_string($con, $_POST['approvemsg']);
    
    $stmt = $con->prepare("UPDATE ".$db." SET status=?, msgs=? WHERE application_id=?");
    $stmt->bind_param("ssi", $status, $msg, $appid);
    
    if($stmt->execute()) {
    echo  "
    <script>
        Swal.fire({
            title: 'Application approved successfully!',
            text: 'Successfully updated the application status.',
            icon: 'success',
        }).then(() => {
            window.location.href = '".$_SERVER['PHP_SELF']."?app_id=".$_GET['app_id']."&type=".$_GET['type']."';
        });
    </script>";
    exit();
    }
    else {
        $error = "Failed to update database: ".$stmt->error;
    }
    $stmt->close();

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject'])) {
    $appid = mysqli_real_escape_string($con, $_POST['app_id']);
    $status = 'Rejected';
    $msg = mysqli_real_escape_string($con, $_POST['rejectmsg']);

    $stmt = $con->prepare("UPDATE ".$db." SET status=?, msgs=? WHERE application_id=?");
    $stmt->bind_param("ssi", $status, $msg, $appid);

    if ($stmt->execute()) {
        echo "
        <script>
            Swal.fire({
                title: 'Application rejected successfully!',
                text: 'Rejection reason saved and applicant notified.',
                icon: 'warning'
            }).then(() => {
                window.location.href = '".$_SERVER['PHP_SELF']."?app_id=".$_GET['app_id']."&type=".$_GET['type']."';
            });
        </script>";
        exit();
    } else {
        $error = "Failed to update database: ".$stmt->error;
    }

    $stmt->close();
}

?>

<!-- <div class='mt-4 p-4 border rounded' id="details"> -->

            <div class="row">
             <div class="col-md-12">
                <div class="card mb-3" id="view-mode">
                    <div class="card-body">
                    <div class="h4 pb-2">    
                        <h3><?php echo $doctype; ?> Certificate Application Details</h3>
                        
                    </div>
                    <hr class="border border-primary border-3 opacity-75">
                    <h4>Application Details</h4>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mt-4">Application ID</h6>
                        </div>
                        <div class="col-sm-9 text-secondary mt-4">
                        <?php echo $app_id; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Roll No.</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
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
                        <?php echo $regno .''. $ofyear; ?>
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
                    <hr>
                    <?php }?>

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
                    <?php }?>
                    <hr class="border border-primary border-3 opacity-75">
                    <h4>B. Upload details</h4>                
                            <!-- Upload Registration Card -->
                        <div class="row">
                            <div class="col-sm-3 my-3"><h6 class="mb-0"> (i) Registration Card</h6></div>
                            <div class="col-sm-12 text-secondary">
                                <!-- View Registration Card Button -->
                                 <?php if (!empty($row['regcard_file'])): ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#regCardModal">View Registration Card</button>
                               
                                <?php endif; ?>

                            <!-- <?php if (!empty($row['regcard_file'])): ?>
                                
                                <iframe src="<?php echo htmlspecialchars($row['regcard_file']); ?>" width="100%" height="600px"></iframe>
                            <?php else: ?>
                                <span class="text-muted">Not uploaded</span>
                            <?php endif; ?> -->
                               
                            </div>
                            
                        </div>
                        <hr>

                        <!-- Upload Marksheet -->
                        <div class="row">
                        <div class="col-sm-3 my-3"><h6 class="mb-0"> (ii) Marksheet</h6></div>
                            <div class="col-sm-12 text-secondary">
                            <?php if (!empty($row['marksheet_file'])): ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#marksheetModal">View Marksheet</button>      
                                <?php endif; ?>

                            </div>
                        </div>
                        <hr class="border border-primary border-3 opacity-75">
                    <h4>C. Payment details</h4>                
                            <div class="row mt-4">
                                 <div class="col-sm-3">
                                <h6 class="mb-0">Amount :</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                <?php echo $amount; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Payment Date</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                <?php echo $paymentOn; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Payment</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                <span class="badge 
                                        <?php echo ($payment == 'Success') ? 'badge-success' : (($payment == 'Aborted') ? 'badge-danger' : (($payment == 'Failure') ? 'badge-danger' : 'badge-danger')); ?>">
                               <?php echo $payment; ?>
                                </span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Transaction ID</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                <?php echo $transactionID; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Bank reference no</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                <?php echo $bank_ref_no; ?>
                                </div>
                            </div>
                        
                <hr class="border border-primary border-3 opacity-75">   

                <?php if($status == 'Approved' && $payment == 'Success'): ?>            
                    <div class="row text-center">
                        <h3>Application Status</h3>
                        <div class="col-md-12 bg-warning">
                            <p class="display-5">Application has been approved.</p>
                        </div>
                        <div class="col-md-12 mt-3">
                        <!-- <?php if ($_SESSION['user_role'] === 'dispatch'): ?>
                            <form action="" method="post" class="mt-3">
                                <label for="dispatch_msg">Update Remarks/Message:</label>
                                <textarea name="dispatch_msg" id="dispatch_msg" rows="3" class="form-control" required><?= htmlspecialchars($row['msgs']) ?></textarea>
                                <input type="hidden" name="app_id" value="<?= $app_id ?>">
                                <button type="submit" name="update_msg" class="btn btn-primary mt-2">Update Message</button>
                            </form>
                        <?php else: ?>
                            <p><strong>Remarks:</strong> <?= htmlspecialchars($row['msgs']) ?></p>
                        <?php endif; ?> -->

                        <?php if (!empty($row['printed_date'])): ?>
                            <p><strong>Printed Date:</strong> <?php echo date("F j, Y", strtotime($row['printed_date'])); ?></p>
                        <?php else: ?>
                            <form action="update_printed_date.php?type=<?php echo $type; ?>" method="POST">
                                <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($app_id); ?>">
                                <label for="printed_date">Select Printed Date:</label>
                                <input type="date" name="printed_date" id="printed_date" class="form-control d-inline-block w-auto" required>
                                <button type="submit" class="btn btn-success mt-2">Update Printed Date</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    </div>
                <?php elseif($status == 'Rejected' && $payment == 'Success'): ?>
                    <div class="row text-center">
                        <h3>Application Status</h3>
                        <div class="col-md-12 bg-light">
                            <p class="display-5 text-danger">Application has been rejected. Reason: <?php echo $row['msgs']; ?></p>
                        </div>
                    </div>
                <?php endif?>

               <?php if($status != 'Approved' && $status != 'Rejected' && $payment == 'Success'): ?>
                <div class="row">
                    <h3>Approve/Reject Application</h3>
                    <div class="col-md-6 bg-light">
                        
                        
                        <!-- Display messages if any -->
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php elseif(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" id="approveForm">
                            <div class="form-group">
                                <label>Approval Message</label>
                                <select class="form-control" name="approvemsg" required>
                                    <option value="">--Select Message--</option>
                                    <option value="Application is approved - It is Under process">Application is approved - It is Under process</option>
                                </select>
                            </div>
                            <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
                            <button type="submit" name="approve" class="btn btn-info">Approve Application</button>
                        </form>
                    </div>
                       <div class="col-md-6 bg-light">
                        <form method="post" id="rejectForm">
                            <div class="form-group">
                             <label>Reject Message</label>
                            <select class="form-control" name="rejectmsg" required>
                                <option value="">--Select the Message--</option>
                                <option value="Registration card is invalid, please reupload">Your Registration card is invalid, please reupload</option>
                                <option value="Marksheet is invalid, please reupload">Your Marksheet is invalid, please reupload</option>
                                 <option value="Marksheet and Registration Card are invalid, please reupload both">Marksheet and Registration Card are invalid, please reupload both</option>
                            </select>
                            </div>
                            <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
                           
                            <button class="btn btn-info" type="submit" name="reject" id="rejectBtn">Reject</button>
                        </form>
                    </div>


                </div>
                <?php endif; ?>
                        <hr class="border border-primary border-3 opacity-75">   

                        </div>
                    </div>
                    </div>				
                </div>
            </div>
        <!-- </div>  -->
    
    </div>

<!-- Registration Card Modal -->
<div class="modal fade" id="regCardModal" tabindex="-1" aria-labelledby="regCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="regCardModalLabel">Registration Card</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe src="<?php echo htmlspecialchars($row['regcard_file']); ?>" width="100%" height="600px" style="border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

<!-- marksheet Card Modal -->
<div class="modal fade" id="marksheetModal" tabindex="-1" aria-labelledby="regCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="regCardModalLabel">Marksheet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe src="<?php echo htmlspecialchars($row['marksheet_file']); ?>" width="100%" height="600px" style="border:none;"></iframe>
      </div>
    </div>
  </div>
<!-- </div> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("approveForm").addEventListener("submit", function(e) {
    if (!this.approvemsg.value) {
        alert('Please select an approval message');
        e.preventDefault();
        return;
    }

    const confirmSubmit = confirm("Are you sure you want to approve this application? This action cannot be undone!");
    if (!confirmSubmit) {
        e.preventDefault(); // Cancel submission if not confirmed
    }
});
</script>



<?php
require('footer.inc.php');
?> 