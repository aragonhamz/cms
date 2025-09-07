<?php
require('top.inc.php');

$color ='';
$msg = '';

if (isset($_SESSION['user_id'])){ 
    $stmt = $con->prepare("select * from dcer where user_id = ? order by appliedon desc");
    $userid= $_SESSION['user_id'];
    $stmt->bind_param("i", $userid);
    if (!$stmt->execute()) {
        die("Query Error: " . $stmt->error);
    }
    
    $result= $stmt->get_result();
    //$exists = $result->fetch_assoc();
    $stmt->close();

    // $login_token = bin2hex(random_bytes(16));
	// $_SESSION['login_token'] = $login_token;
	// $stmt = $con->prepare("UPDATE users SET login_token = ? WHERE id = ?");
	// $stmt->bind_param("si", $login_token, $userid);
	// $stmt->execute();
	// $stmt->close();
}else{
    header('location: index.php');
    exit();
}
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
          <?php if ($result->num_rows > 0) { ?>
		  <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                   Certificates Application
                </h6>
              </div>
              <div class="card-body">
			  	
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0"
                  >
						 <thead>
							<tr>
							   <th class="serial">#</th>
							   <th width="10%">Application ID</th>
                               <th width="10%">Document Type</th>
                               <th width="10%">Roll No</th>
                               <th width="25%">Name</th>
							   <th width="20%">Applied on</th>
							   <th width="8%">Payment</th>
							   <th width="8%">Status</th>
							   <th width="26%">Response</th>
                               <th></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while ($row = $result->fetch_assoc()) {?>
							<tr>
							   <td class="serial"><?php echo $i?></td>
                               <td><?php echo $row['application_id']?></td>
                               <td><?php echo $row['doctype'] . ' Certificate'?></td>
                               <td><?php echo $row['rollno']?></td>
                               <td><?php echo $row['name']?></td>
							   <td><?php echo date("d-m-Y h:i A", strtotime($row['appliedon']))?></td>
							   <td>
                                <span class="badge 
                                        <?php echo ($row['payment'] == 'Success') ? 'badge-success' : (($row['payment'] == 'Aborted') ? 'badge-danger' : (($row['payment'] == 'Failure') ? 'badge-danger' : 'badge-danger')); ?>">
                               <?php echo $row['payment']; ?>
                                </span>
                               </td>
							   <td>
                               <span class="badge 
                                        <?php echo ($row['status'] == 'Approved') ? 'badge-success' : (($row['status'] == 'Pending') ? 'badge-warning' : (($row['status'] == 'Applied') ? 'badge-info' : 'badge-danger')); ?>">
                                        <?php echo $row['status']; ?>
                                </span>
                               </td>
							   <td><?php echo $row['msgs']?></td>
                               <td>
                               <?php
                                $payment = strtolower($row['payment']);
                                $status = strtolower($row['status']);
                                $app_id = $row['application_id'];
                                ?>
                               <!-- if ($payment == 'pending' || $payment == 'failure' || $payment == 'aborted') { -->
                                <?php if ($payment == 'pending' || $payment == 'failure' || $payment == 'aborted'){ ?>
                                    <form method="post" id="paymentForm" action="ccavRequestHandler.php">
                                        <input type="hidden" name="tid" value="<?php echo $app_id.rand(100,999); ?>" />
                                        <input type="hidden" name="merchant_id" value="213372" />
                                        <input type="hidden" name="order_id" value="<?php echo $row['cer_id']; ?>" />
                                        <input type="hidden" name="amount" value="<?php echo getamount($row['doctype']); ?>" />
                                        <input type="hidden" name="currency" value="INR" />
                                        <input type="hidden" name="redirect_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                                        <input type="hidden" name="cancel_url" value="<?php echo WEBSITE_NAME; ?>/ccavResponseHandler.php" />
                                        <input type="hidden" name="language" value="EN" />
                                        <input type="hidden" name="merchant_param1" value="<?php echo $row['doctype'] ?> Certificate" />
                                        <input type="hidden" name="merchant_param2" value="DirectCertificate" />
                                        <input type="hidden" name="merchant_param5" value="<?php echo $login_token; ?>" />
                                        <!-- <input type="submit" value="Pay Now" class="btn btn-success" /> -->
                                        <button type="button" class="btn btn-sm btn-warning" onclick="checkLoginBeforePayment()" data-bs-toggle="tooltip" title="Click to proceed to payment"><i class="fa fa-credit-card"></i> Pay Now</button>

                                    </form>
                                    <!-- echo '<a href="paynow.php?app_id=' . $app_id . '" class="btn btn-sm btn-warning"><i class="fa fa-credit-card"></i>Pay Now</a>'; -->
                                <?php   
                                } elseif (strtolower($payment) == 'success' && strtolower($status) == 'approved') {
                                //$encrypted_id = urlencode(encryptAppId($app_id));
                                $encrypted_id = urlencode(encryptAppId($row['application_id']));
                                echo '<a href="downloadmypdf.php?app_id=' . $encrypted_id . '&type=DirectCertificate" class="btn btn-lg" target="_blank" data-bs-toggle="tooltip" title="Download the Form and Receipt"><i class="fa fa-file-pdf" style="color: red;"></i></a>';
                            }  elseif (strtolower($payment) == 'success' && strtolower($status) == 'rejected') {
                                //$encrypted_id = urlencode(encryptAppId($app_id));
                                $encrypted_id = urlencode(encryptAppId($row['application_id']));
                                echo '<a href="reupload_documents.php?app_id=' . $encrypted_id . '&type=DirectCertificate" class="btn btn-warning btn-sm">Re-Upload Documents</a>';
                            }
                          
                                ?>
                               
                               </td>
							   
							</tr>
							<?php $i++; } ?>
						 </tbody>
					  </table>
				   </div>
				</div>
			 </div>
           <?php } else {
        echo "<div class='alert alert-warning'>No applications found.</div>";
    }?>
		  </div>
	   </div>
	</div>
</div>
<script>
function checkLoginBeforePayment() {
    fetch("check_session.php")
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // Now fetch the token securely
                fetch("generate_token.php")
                    .then(res => res.json())
                    .then(tokenData => {
                        if (tokenData.status === 'success') {
                            // Add token to form
                            const form = document.getElementById("paymentForm");
                            const tokenInput = document.createElement("input");
                            tokenInput.type = "hidden";
                            tokenInput.name = "merchant_param5";
                            tokenInput.value = tokenData.token;
                            form.appendChild(tokenInput);

                            form.submit();
                        } else {
                            alert("Error generating login token");
                        }
                    });
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

function confirmAction(url) {
    Swal.fire({
        title: 'Are you sure you want to Activate/Deactivate this user?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function confirmDelete(url) {
    Swal.fire({
        title: 'Are you sure you want to delete this user?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}



</script>
<?php
require('footer.inc.php');
?>