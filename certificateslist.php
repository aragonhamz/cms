<?php
require('top.inc.php');
isAdmin();
$color ='';
$msg = '';

/// Fetch dropdown values
$doctypeOptions = fetchDistinctValues($con, 'doctype', 'document');
$degreetypeOptions = fetchDistinctValues($con, 'degreetype', 'degreetype');

// Handle form input or set default on page load
if (isset($_POST['search'])) {
    $selectedDoctype = mysqli_escape_string($con, $_POST['doctype']);
    $selectedDegreetype = mysqli_escape_string($con, $_POST['degreetype']);
    $selectedStatus = mysqli_escape_string($con, $_POST['status']);
    $selectedPayment = mysqli_escape_string($con, $_POST['payment']);
} else {
    $selectedDoctype = $doctypeOptions[0] ?? ''; // default to first value
    $selectedDegreetype = ''; // show all degree types
    $selectedStatus = '';
    $selectedPayment = '';
}

$status = ''; // exclude records with this status

// Prepare and run the query
// $sql = "SELECT * FROM dcer 
//         WHERE status <> ? 
//         AND doctype = ? 
//         AND (? IS NULL OR ? = '' OR degreetype = ?) 
//         AND (? IS NULL OR ? = '' OR status = ?)
//         AND (? IS NULL OR ? = '' OR payment = ?)
//         ORDER BY appliedon DESC";
$sql = "
SELECT d.*, u.mobile 
FROM dcer d
JOIN users u ON d.user_id = u.id
WHERE d.status <> ? 
  AND d.doctype = ? 
  AND (? IS NULL OR ? = '' OR d.degreetype = ?) 
  AND (? IS NULL OR ? = '' OR d.status = ?)
  AND (? IS NULL OR ? = '' OR d.payment = ?)
ORDER BY d.appliedon DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("sssssssssss", $status, $selectedDoctype,
    $selectedDegreetype, $selectedDegreetype, $selectedDegreetype,
    $selectedStatus, $selectedStatus, $selectedStatus,
    $selectedPayment, $selectedPayment, $selectedPayment);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>



<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
         
		  <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  Degree Certificates
                </h6>
              </div>
              <div class="card-body">
                  <form method="POST" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="filter_doctype" class="form-label">Document Type</label>
                    <select name="doctype" id="filter_doctype" class="form-select">
                        <?= generateOptions($doctypeOptions, $selectedDoctype); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filter_degreetype" class="form-label">Degree Type</label>
                    <select name="degreetype" id="filter_degreetype" class="form-select">
                        <?= generateOptions($degreetypeOptions, $selectedDegreetype); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filter_status" class="form-label">Status</label>
                    <select name="status" id="filter_status" class="form-select">
                        <option value="">All</option>
                        <option value="Applied" <?= ($selectedStatus == 'Applied') ? 'selected' : '' ?>>Applied</option>
                        <option value="Approved" <?= ($selectedStatus == 'Approved') ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= ($selectedStatus == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filter_payment" class="form-label">Payment</label>
                    <select name="payment" id="filter_payment" class="form-select">
                        <option value="">All</option>
                        <option value="Success" <?= ($selectedPayment == 'Success') ? 'selected' : '' ?>>Success</option>
                        <option value="Failure" <?= ($selectedPayment == 'Failure') ? 'selected' : '' ?>>Failure</option>
                        <option value="Aborted" <?= ($selectedPayment == 'Aborted') ? 'selected' : '' ?>>Aborted</option>
                    </select>
                </div>

                   <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" name="search" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
 <hr class="border border-primary border-3 opacity-75">  
              <?php if ($result->num_rows > 0) { ?>
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
                <th width="20%">Name</th>
                <th width="10%">Roll No</th>
							   <th width="10%">Applied on</th>
							   <th width="10%">Payment</th>
							   <th width="10%">Status</th>
							   <th width="26%">Messages</th>
                               <th width="10%">Mobile</th>
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
							   <td>
                  <?php 
                  
                  if (!empty($row['application_id'])) {
                    //$encrypted_id = encryptAppId($row['application_id']);
                     $encrypted_id = urlencode(encryptAppId($row['application_id']));
                } else {
                    // Handle or log the error appropriately
                    $encrypted_id = '';
                }
                  $encrypted_id = urlencode(encryptAppId($row['application_id']));
                  ?>
                  
                  <a href="applicationdetails.php?app_id=<?php echo $encrypted_id ?>&type=DirectCertificate" class="btn btn-md text-primary" target="_blank" data-bs-toggle="tooltip" title="Click to view application details"><?php echo $row['name']; ?></a>
                </td>
                 <td><?php echo $row['rollno']?></td>
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
                                <?php echo $row['mobile']?>
							   </td>
							   <td>
								<?php
								if ($row['status'] == 'Approved') {
                                   $encrypted_id = urlencode(encryptAppId($row['application_id']));
                                  echo '<a href="generatecertificate.php?app_id=' . $encrypted_id . '&type=DirectCertificate" class="btn btn-lg" target="_blank" data-bs-toggle="tooltip" title="View Certificate"><i class="fa fa-file-pdf" style="color: red;"></i>View Certificate</a>';                                    
                                }
								?>
							   </td>
							   
							</tr>
							<?php $i++; } ?>
						 </tbody>
					  </table>
				   </div>
           <?php } else {
        echo "<div class='alert alert-warning'>No applications found.</div>";
    }?>
				</div>
			 </div>
          
		  </div>
	   </div>
	</div>
</div>
<script>
// function confirmAction(url) {
//     if (confirm("Are you sure you want to Activate/Deactivate this user?")) {
//         window.location.href = url;
//     }
// }
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