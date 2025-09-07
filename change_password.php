<?php
include('top.inc.php'); // Database connection

checkIfLogin();

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = mysqli_real_escape_string($con, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Fetch user record
    $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (md5($current_password) == $user['password']) {
        if ($new_password === $confirm_password) {
            $hashed_password = md5($new_password);

            // Update password
            $stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
            $stmt->close();

            echo "<div class='alert alert-success'>Password changed successfully! </div>";
        } else {
            echo "<div class='alert alert-danger'>New passwords do not match!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Current password is incorrect!</div>";
    }
}
?>
 <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Password Reset
                    </h6>
                  </div>
                  <div class="card-body"> 
                  <form method="post">
                    <div class="row mb-3">
                      <div class="col-lg-4">
                        <label>Current Password</label>
                      <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-lg-4">
                      <input type="password" name="new_password" placeholder="New Password" class="form-control" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-lg-4">
                      <input type="password" name="confirm_password" placeholder="Confirm New Password" class="form-control" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                      <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
                      </div>
                    </div>
                    
                    </form>
                  </div>
    </div>   
   <?php require('footer.inc.php');?> 

