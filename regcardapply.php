<?php
require('top.inc.php') ;
isAdmin();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$name = "";
$address = "";
$contact = "";
$gender = "";
$category = "";
$dob = "";
$state = "";
$email = "";
$father = "";
$file = "";
$errormessage = "";
$successmessage = "";
// Check if image file is a actual image or fake image
if(isset($_POST["savereg"])) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  // if($check !== false) {
  //   echo "File is an image - " . $check["mime"] . ".";
  //   $uploadOk = 1;
  // } else {
  //   echo "File is not an image.";
  //   $uploadOk = 0;
  // }
  $name = mysqli_real_escape_string($con, $_POST["form_name"]);
  //$address = mysqli_real_escape_string($con, $_POST["address"]);
  $contact = mysqli_real_escape_string($con, $_POST["form_contact"]);
  $gender = mysqli_real_escape_string($con, $_POST["form_gender"]);
  //$category = mysqli_real_escape_string($con, $_POST["category"]);
  $date_field = date('Y-m-d',strtotime($_POST["dob"]));
  //$state = mysqli_real_escape_string($con, $_POST["state"]);
  $email = mysqli_real_escape_string($con, $_POST["form_email"]);
  $father = mysqli_real_escape_string($con, $_POST["form_father"]);
  //$filename = $_FILES['fileToUpload']['name'];
  $original_filename = basename($_FILES["fileToUpload"]["name"]);
  $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
  $filename = mysqli_real_escape_string($con,$_POST["form_email"]) . 'RegCard' . '.' . $file_extension;

    // get last record id
    $sql = 'select max(reg_id) as id from regcard';
    $result = mysqli_query($con, $sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0)
    {
        $row = mysqli_fetch_array($result);
        $filename = ($row['id']+1) . '-' . $filename;
    }
    else
        $filename = '1' . '-' . $filename;

    // Check if file already exists
    if (file_exists($target_file)) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error Attention!</strong> Sorry, file already exists
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error Attention!</strong> Sorry, your file is too large.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "pdf" ) {

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error Attention!</strong> Sorry, only JPG, JPEG, PNG & PDF files are allowed
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error Attention!</strong> Sorry, your file was not uploaded.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    // if everything is ok, try to upload file
    } else {
      $sql = "INSERT INTO regcard(name, gender, email, contact, father, dob, file)
               VALUES ('$name','$gender','$email','$contact', '$father', '$date_field', '$filename')";
        // if ($con->query($sql) === TRUE) {
        $query_result = mysqli_query($con, $sql);
        if ($query_result) {
            echo '<script>  
            swal("Success!", "Your form has been submitted!, please check your email", "success");  
            </script>';  

          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $filename)) {
            //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Congratulations!</strong> Your application for Registration Card is successful, please check your mail.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
          } else {
            
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Attention!</strong> Sorry, there was an error applying for Reg card
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                exit();
          }

        //   header("Location: regcardapply.php?st=success");
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Attention!</strong> Sorry, there was an error.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
}

} $con->close();
?>
 <?php if(isset($_GET['st'])) { ?>
                <div class="alert alert-danger text-center">
                <?php if ($_GET['st'] == 'success') {
                        echo "File Uploaded Successfully!";
                    }
                    else
                    {
                        echo 'Invalid File Extension!';
                    } ?>
                </div>
            <?php } ?>

            <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Registration Card Application
                    </h6>
                  </div>
                  <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="form_name">Full Name (Capital Letters) *</label>
                                                <input id="form_name" type="text" name="form_name" id="form_name" class="form-control" placeholder="Please enter your firstname *" required data-error="Firstname is required.">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="form_gender">Gender</label>                            
                                                <select id="form_gender" name="form_gender" class="form-control" required="required" data-error="Please specify your gender.">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label for="form_email">Email *</label>
                                                <input id="form_email" type="email" name="form_email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                                                
                                            </div>
                                        </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                            <label for="form_contact" class="form-label" >Contact</label>
                                            <input type="text" class="form-control" id="form_contact" name="form_contact" placeholder="Enter your contact number" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">                            
                                            <label for="form_father" class="form-label" >Father's Name</label>
                                            <input type="text" class="form-control" id="form_father" name="form_father" placeholder="Enter your father's number" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label class="form-label" for="">Date of Birth</label>
                                                <input type="date" id="dob" name="dob" placeholder="yyyy-mm-dd" class="form-control" required>

                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">             
                                            <label for="form_category" class="form-label">Category</label>
                                                <select id="form_category" name="need" class="form-control" required="required" data-error="Please specify your category.">
                                                    <option value="" selected disabled>--Select Your Category--</option>
                                                    <option >ST</option>
                                                    <option >SC</option>
                                                    <option >OBC</option>
                                                    <option >General</option>
                                                    <option >Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">                           
                                            <label for="form_address" class="form-label" >Address for Correspondence</label>
                                            <input type="text" class="form-control" id="form_address" placeholder="Enter your correspondence address" required>
                                        </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label class="form-label" for="form_nationality">Nationality</label>
                                                <input type="text" id="form_nationality" class="form-control" placeholder="Enter your Nationality" required>
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                    <!-- <label class="form-label">Upload Original</label>
                                    <input type="file" name="fileToUpload" id="fileToUpload"> -->
                                    <div class="file-upload">
                                        <div class="file-select">
                                        <div class="file-select-button" id="fileName">Choose File</div>
                                        <div class="file-select-name" id="noFile">No file chosen...</div> 
                                        <input type="file" name="fileToUpload" id="fileToUpload">
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                    <div class="row">
                                        <!-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="form_message">Message *</label>
                                                <textarea id="form_message" name="message" class="form-control" placeholder="Write your message here." rows="4" required="required" data-error="Please, leave us a message."></textarea
                                                    >
                                                </div>

                                            </div> -->


                                        <div class="col-md-12">                       
                                        
                                                <button type="submit" class="btn btn-success btn-send" id="savereg" name="savereg">Save</button>                    
                                        </div>
                            
                                        
                    </div>
                                

                    </form>

                  </div>
            </div>   
<?php require('footer.inc.php') ?>