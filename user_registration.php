<?php 
include_once('connection.inc.php'); 
require('functions.inc.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if (isset($_SESSION['user_id'])){
  
            header('Location: index.php');
            exit();
}

$message = '';
$color = '';
if(isset($_POST['password-reset-token']) && $_POST['email']){
  $name = mysqli_escape_string($con, $_POST['name']);
  $email = mysqli_escape_string($con, $_POST['email']);
  $password = md5(mysqli_escape_string($con, $_POST['password']));
  $status= '1';
  $role = 'user';
 
  $sqlselect = "select * from users where email= ?";
  $stmt = $con->prepare($sqlselect);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result= $stmt->get_result();
  //$result= $con->query($sqlselect);
  $exists = $result->fetch_assoc();
  $stmt->close();
  if($exists){
    $message = 'User already exist';
    $color = 'danger';
  }else{
  
    $con->begin_transaction();
    try {       
      $token = md5($_POST['email']).rand(10,9999); 
      //$sqlinsert = "INSERT INTO users(name, email, email_verification_link ,password, status, role) VALUES('" . $name . "', '" . $email . "', '" . $token . "', '" . $password . "', '" . $status . "', '" . $role . "')";
      $stmt = $con->prepare("
      INSERT INTO users(name, email, email_verification_link ,password, status, role)
      VALUES (?, ?, ?, ?, ?, ?)
      ");
      $stmt->bind_param("ssssis", $name, $email, $token, $password, $status, $role);
    
      $link = "<a href=". WEBSITE_NAME. "/verify-email.php?key=".$email."&token=".$token.">Verify Email</a>";   
      $subject = "Your Registration in Examination NEHU for Certificates";
    
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
                    <h2>Welcome to NEHU Certificate Online management</h2>
                    
                  </div>

                  <!-- Content -->
                  <div class='content'>
                    <p>Hello <strong>" .$name. "</strong>,</p>
                    <p>Thank you for signing up with us. We are thrilled to have you on board!</p>
                    <p>Click the button below to confirm your email address and get started:</p>
                    <p class='text-center'>
                      
                      " . $link . "
                    </p>
                    <p>If you did not create an account, please ignore this email.</p>
                  </div>

                  <!-- Footer -->
                  <div class='footer'>
                    <p>&copy; 2025 Computer Center, NEHU. All rights reserved.</p>
                    
                  </div>
                </div>

                </body>
                </html>";
          $mail = new PHPMailer(true);
                
            if( $stmt->execute()){
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
                $message = 'Registered Successfully, please check your email';
                echo '<script>  
                swal("Success!", "You have been registered successfully!, please check your email", "success");  
                </script>';  
                $stmt->close();

                $con->commit();
              }else{
                $color = 'danger';
                $message = 'Error registering ' . $con->error;
            }
        } catch (Exception $e) {
            echo '<script>  
            swal("Error!", "Cannot register user Error", "danger");  
            </script>';  
            $message= "Message could not be sent. Mailer Error:" . $mail->ErrorInfo;
            $con->rollback();
            echo "<div class='alert alert-danger'>Error Registering : " . $e->getMessage() . "</div>";
        }         
      
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Sweetalert -->

        <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css
    " rel="stylesheet">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        body {
      background-color: #f8f9fa;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      width: 400px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo-container img {
      max-width: 150px; /* Adjust the size as needed */
      height: auto;
    }

    </style>
</head>
<body>
<div class="row justify-content-center">
  <div class="col">
    <div class="container">  
        <div class="login-container">  
            <div class="logo-container">  
                <img src="images/NEHU_logo.png" alt="Logo">  <!-- Replace 'your-logo.png' with the path to your logo -->  
            </div>  
            <h2 class="text-center mb-4">Registration</h2>  
            <div class="alert alert-<?php echo $color; ?>" role="alert">
                                      <?php echo $message; ?>
                                  </div>
         
              <form action="" method="post">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="name" class="form-control" id="name" required="">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" required="">
                  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Password</label>
                  <input type="password" name="password" class="form-control" id="password" required="">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Confirm Password</label>
                  <input type="password" name="cpassword" class="form-control" id="cpassword" required="">
                </div>
               <div class="form-group mt-1">
                <div class="row">
                  <div class="col-md-6">
                  <button type="submit" name="password-reset-token" class="btn btn-primary">Register</button>
                  </div>
                  
                </div>
                
               </div>
              </form>
            
          </div>  
    </div>
  </div>
</div>
</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js
    "></script>
</body>
</html>
