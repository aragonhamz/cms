<?php
require('top.inc.php');
//isAdmin();
$color ='';
$msg = '';
$decryptedid ='';
if(isset($_GET['type']) && $_GET['type']!=''){
	
	$type=get_safe_value($con,$_GET['type']);
  if($_GET['id'] == 'self'){
    $decryptedid = intval($_SESSION['user_id']);
  }else{
    $decryptedid = decrypt($_GET['id']);
  }
    $stmt = $con->prepare("SELECT users.name, users.mobile, user_profile.dob, user_profile.fathername, user_profile.address, user_profile.nationality FROM users LEFT JOIN user_profile on users.id=user_profile.userid WHERE users.id = ?");
        $stmt->bind_param("i", $decryptedid);
        $stmt->execute();
        $result = $stmt->get_result();
		    $row = $result->fetch_assoc();
        if($row){
            $fullname = $row['name'] ?? '';
            $mobile = $row['mobile'] ?? '';
            $dob = $row['dob'];
            $fathername = $row['fathername'];
            $address = $row['address'];
            $nationality = $row['nationality'];
        } else {
            echo "<div class='alert alert-danger'>Profile not found.</div>"; 
            $fullname = '';
			      $address = '';
            $dob = '';
            $fathername = '';
            $mobile ='';
            $nationality = '';  
            //exit;
        }
        $stmt->close();
  
		
	} else {
		echo "<div class='alert alert-danger'>No User ID provided</div>";
		exit;
	}

  if(isset($_POST['update_profile'])){
    
    $id = $decryptedid;

    $fullname = mysqli_real_escape_string($con, $_POST["name"]);
    $mobile = mysqli_real_escape_string($con, $_POST["mobile"]);
    $dob = mysqli_real_escape_string($con, $_POST["dob"]);
    $fathername = mysqli_real_escape_string($con, $_POST["fathername"]);
    $address = mysqli_real_escape_string($con, $_POST["address"]);
    $nationality = mysqli_real_escape_string($con, $_POST["nationality"]);

    // Start a transaction to keep consistency
    $con->begin_transaction();

    try {
        // 1. Update the `users` table
        $stmt = $con->prepare("UPDATE users SET name = ?, mobile = ? WHERE id = ?");
        $stmt->bind_param("ssi", $fullname, $mobile, $id);
        $stmt->execute();
        $stmt->close();

        // 2. Check if a record exists in `user_profile`
        $stmt = $con->prepare("SELECT userid FROM user_profile WHERE userid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->fetch_assoc();
        $stmt->close();

        if ($exists) {
            // 3. If record exists, update it
            $stmt = $con->prepare("
                UPDATE user_profile 
                SET dob = ?, fathername = ?, address = ?, nationality = ?
                WHERE userid = ?
            ");
            $stmt->bind_param("ssssi", $dob, $fathername, $address, $nationality, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // 4. If record doesn't exist, insert a new one
            $stmt = $con->prepare("
                INSERT INTO user_profile (userid, dob, fathername, address, nationality) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("issss", $id, $dob, $fathername, $address, $nationality);
            $stmt->execute();
            $stmt->close();
        }

        // 5. Commit the transaction
        $con->commit();

        echo "<div class='alert alert-success'>Profile updated successfully</div>";
    } catch (Exception $e) {
        // 6. Rollback on error
        $con->rollback();
        echo "<div class='alert alert-danger'>Error updating: " . $e->getMessage() . "</div>";
    }
}

?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
	   <div class="col-md-8">
              <div class="card mb-3" id="view-mode">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo is_null(htmlspecialchars($fullname)) ? '' : htmlspecialchars($fullname); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo htmlspecialchars($mobile); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">DOB</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo is_null(htmlspecialchars($dob)) ? '' : htmlspecialchars($dob); ?>
                    </div>
                  </div>
                  <hr>
                  
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo htmlspecialchars($address); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Father's Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo htmlspecialchars($fathername); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Nationality</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
					<?php echo htmlspecialchars($nationality); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
					<button class="btn btn-info mt-3" onclick="toggleEditMode()">Edit</button>
                    </div>
                  </div>
                </div>				
	   </div>

	     <!-- EDIT MODE -->
		 <div id="edit-mode" class="d-none">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $decryptedid; ?>">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact No</label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($dob); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Father's Name</label>
                        <input type="text" name="fathername" class="form-control" value="<?php echo htmlspecialchars($fathername); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" value="<?php echo htmlspecialchars($nationality); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Cancel</button>
                </form>
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

function toggleEditMode() {
            document.getElementById('view-mode').classList.toggle('d-none');
            document.getElementById('edit-mode').classList.toggle('d-none');
        }
</script>
<?php
require('footer.inc.php');
?>