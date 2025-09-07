<?php require('top.inc.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
LoggedinUserNotAllowed();
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    // Check if user exists
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $token = bin2hex(random_bytes(32)); // Generate a secure token
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save the token in the database
        $stmt = $con->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
        $stmt->bind_param("sss", $email, $token, $expires_at);
        $stmt->execute();
        

        $subject = "Password Reset Request from NEHU";
        $link = WEBSITE_NAME."/reset_password.php?token=" . $token;   
        $mailmessage = "
             <!DOCTYPE html>
              <html lang='en'>
              <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>New Registration with NEHU Certificate</title>
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
                  <h2>Your password request</h2>
                  
                </div>

                <!-- Content -->
                <div class='content'>
                  <p>Hello <strong>" .$email. "</strong>,</p>
                  <p>We have received a request on password reset!</p>
                  <p>Click the link below to reset your password:</p>
                  <p class='text-center'>
                      " . $link . "
                  </p>
                  <p>If you did not request this, please ignore this email.</p>
                </div>

                <!-- Footer -->
                <div class='footer'>
                  <p>&copy; 2025 Computer Center, NEHU. All rights reserved.</p>
                  
                </div>
              </div>

              </body>
              </html>";
     $mail = new PHPMailer(true);
        try {
          if($stmt->execute()==true){
              //Server settings
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
              $color = 'success';
              //$lastid = $con->insert_id;
              //$message = 'Saved Successfully, with the ID: '. $lastid;
              $message = 'Please check your email';
              echo '<script>  
              swal("Success!", "Your password reset request is successful!, please check your email", "success");  
              </script>';  
              $stmt->close();
            }else{
              $color = 'danger';
              $message = 'Error Saving ' . $con->error;
          }
      } catch (Exception $e) {
          echo '<script>  
          swal("Error!", "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";, "danger");  
          </script>';  
          $message= "Message could not be sent. Mailer Error:" . $mail->ErrorInfo;
      }         
    
  }else {
        echo "<div class='alert alert-danger'>No account found with that email.</div>";
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
                      <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                      <button type="submit" name="submit" class="btn btn-primary">Send Reset Link</button>
                      </div>
                    </div>
                    
                    </form>
                  </div>
    </div>   
   <?php require('footer.inc.php');?>