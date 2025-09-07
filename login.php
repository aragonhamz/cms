<?php
require('connection.inc.php');
require('functions.inc.php');
$msg='';
if(isset($_POST['submit'])){
	//$username=get_safe_value($con,$_POST['username']);
  $email=get_safe_value($con,$_POST['email']);
	$password=get_safe_value($con,$_POST['password']);
    // if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
    //   $msg = "Please Enter Valid Email ID";
    //   exit();
    // }
    // if(strlen($password) < 6) {
    //   $msg = "Password must be minimum of 6 characters";
    //   exit();
    // }
	//$sql="select * from admin_users where username='$username' and password='$password'";
  $sql="SELECT * FROM users WHERE email = '" . $email. "' and password = '" . md5($password). "'";
	$res=$con->query($sql);
	$count=$res->num_rows;
	if($count>0){
		$row= $res->fetch_assoc();
		if($row['status']=='0'){
			$msg="Account deactivated";	
		}else{
			// $_SESSION['ADMIN_LOGIN']='yes';
			// $_SESSION['ADMIN_ID']=$row['id'];
			// $_SESSION['ADMIN_USERNAME']=$email;
			// $_SESSION['ADMIN_ROLE']=$row['role'];
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['user_email'] = $row['email'];
      $_SESSION['user_mobile'] = $row['mobile'];
      $_SESSION['user_role'] = $row['role'];
			header('location:index.php');
			die();
		}
	}else{
		
        $msg = 'Incorrect Email or Password!!';
        // echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        //   <strong>Error Attention!</strong>  Error
        //   </div>';
          
	}
	
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <img src="images/NEHU_logo.png" alt="Logo">  
            </div>  
            <h2 class="text-center mb-4">Login</h2>  
                        <p class="mt-3">
                        <!-- <div class="field_error"><?php echo $msg?></div> -->
                        <?php if($msg!=''){?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong></strong>  <?php echo $msg?>
                        </div>
                        <?php }; ?>
                        </p>
                        <form method="post">
                            <div class="form-group mb-4">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="        " required>
                            </div>
                            <div class="form-group mb-4">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="          " required>
                            </div>
                           
                            <div class="form-group mt-3">
                                <div class="row mb-2">
                                <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-info">
                                    Login
                                </button>
                                </div>
                                <div class="col-md-6">
                                    New user? <a href="user_registration.php" class="btn btn-info">Register</a>
                                </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                  <a href="forgot_password.php" class="text-primary">Forgot password</a>
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
</body>
</html>