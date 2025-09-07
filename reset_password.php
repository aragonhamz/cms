<?php
include('top.inc.php');
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $stmt = $con->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        $email = $data['email'];

        if (isset($_POST['submit'])) {
            $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
            $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                $hashed_password = md5(mysqli_escape_string($con, $_POST['new_password']));

                // Update user password
                $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashed_password, $email);
                $stmt->execute();
                $stmt->close();

                // Delete token after use
                $stmt = $con->prepare("DELETE FROM password_resets WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->close();

                echo "<div class='alert alert-success'>Password updated successfully! <a href='login.php'>Login now</a></div>";
            } else {
                echo "<div class='alert alert-danger'>Passwords do not match!</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid or expired token!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No token provided!</div>";
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
                      <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-lg-4">
                      <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                      <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
                      </div>
                    </div>
                    
                    </form>
                  </div>
    </div>   
   <?php require('footer.inc.php');?> 