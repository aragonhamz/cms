<?php require('top.inc.php');
$msg='';
if(isset($_POST['submit'])){
	$username=get_safe_value($con,$_POST['username']);
	$password=get_safe_value($con,$_POST['password']);
	$sql="select * from admin_users where username='$username' and password='$password'";
	$res=mysqli_query($con,$sql);
	$count=mysqli_num_rows($res);
	if($count>0){
		$row=mysqli_fetch_assoc($res);
		if($row['status']=='0'){
			$msg="Account deactivated";	
		}else{
			$_SESSION['ADMIN_LOGIN']='yes';
			$_SESSION['ADMIN_ID']=$row['id'];
			$_SESSION['ADMIN_USERNAME']=$username;
			$_SESSION['ADMIN_ROLE']=$row['role'];
			header('location:index.php');
			exit();
		}
	}else{
		$msg= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error Attention!</strong> "PLEASE ENTER CORRECT LOGIN DETAILS"	
    
    </div>';
        
	}
	
}
?>
<div class="row justify-content-center">
  <div class="col">
    <div class="container d-flex align-items-center">  

      <div class="card shadow mb-4" style="width:400px;">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Login
                    </h6>
                  </div>
                  <div class="card-body">
                  <img src="images/NEHU_logo.png" width="200px;" alt="Logo"> 
                        <p class="mt-3">
                        <div class="field_error"><?php echo $msg?></div>

                        </p>
                        <form method="post">
                            <div class="form-group mb-4">
                            <label>USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="        " required>
                            </div>
                            <div class="form-group mb-4">
                            <label>PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="          " required>
                            </div>
                           
                            <div class="form-group mt-3">
                                <div class="row">
                                <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-info">
                                    Login
                                </button>
                                </div>
                                <div class="col-md-6">
                                    New user? <a href="user_registration.php" class="btn btn-info">Register</a>
                                </div>
                                </div>
                                
                            </div>
                        </form>
                  </div>
      </div>
</div>
  </div>
</div>
<?php require('footer.inc.php');  ?>
