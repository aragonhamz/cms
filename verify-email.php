<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <title>NEHU CMS - User Account Activation</title>
       <!-- CSS -->
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   </head>
   <body>
          <?php
            if($_GET['key'] && $_GET['token'])
            {
              include "connection.inc.php";
                $login = "";
              $email = $_GET['key'];
              $token = $_GET['token'];
              //$stmt->prepare("SELECT * FROM `users` WHERE `email_verification_link`='".$token."' and `email`='".$email."';"
              $stmt =$con->prepare("SELECT * FROM `users` WHERE `email_verification_link`= ? and `email`= ?");
              $stmt->bind_param("ss",$token, $email);
              $stmt->execute();
              $results = $stmt->get_result();
              $row = $results->fetch_assoc();
              $stmt->close();
              $d = date('Y-m-d H:i:s');
                if ($row) {                
                   if($row['email_verified_at'] == NULL){
                     //mysqli_query($con,"UPDATE users set email_verified_at ='" . $d . "' WHERE email='" . $email . "'");
                     $stmt = $con->prepare("UPDATE users set email_verified_at = ? WHERE email= ?");
                     $stmt->bind_param("ss",$d, $email);
                     $stmt->execute();
                     $stmt->close();
                     $msg = "Congratulations! Your email has been verified. <a href='login.php'>Login Now</a>";
                     $color = "success";
                     $login = "Login";
                     //echo "<div class='alert alert-success'>Congratulations! Your email has been verified.</div>"; 
                   }else{
                      $msg = "You have already verified your account with us. <a href='login.php'>Login Now</a>";
                      $color = "warning";
                      
                      //echo "<div class='alert alert-warning'>You have already verified your account with us.</div>"; 
                   }

                } else {
                  $msg = "Incorrect email or token";
                  $color = "warning";
                  //echo "<div class='alert alert-warning'>Incorrect email or token.</div>"; 
                }
               
              }
              else
              {
              $msg = "Something goes to wrong.";
              $color = "danger";
              //echo "<div class='alert alert-danger'>Danger! Your something goes to wrong.</div>"; 
            }
            ?>
      <div class="container mt-3">
          <div class="card">
            <div class="card-header text-center">
              User Account Activation
            </div>
            <div class="card-body">
             <p><div class="alert alert-<?php echo $color; ?>" role="alert"> <?php echo $msg; ?></div></p><br>
                <?php if($login == 'login'){ ?>
                <a href="login.php" class="btn btn-primary">Login Now</a>
                <?php } ?>
            </div>
          </div>
      </div>
   </body>
</html>