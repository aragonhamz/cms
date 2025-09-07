<?php
require('top.inc.php');
if (!isset($_SESSION['user_id'])){
    header('location: index.php');
    exit();
} 

$selectedYear = isset($_POST['year']) ? (int)$_POST['year'] : null;
$cerid='';
$name ='';
$dept='';
$regno='';
$gradyear='';
$cgpa='';
$grade='';
if(isset($_POST['searchrollno'])){
    $rollno = $_POST['rollno'];
    $regno = $_POST['regno'];
    if (empty($rollno) && empty($regno)) {
        echo '<script>
            swal("Error", "Please enter either Roll No or Reg No", "error")
            .then(() => {
                window.history.back(); // takes user back to the previous form page
            });
        </script>';
        exit;
    }
    if (!empty($rollno)) {
        $stmt = $con->prepare("SELECT * FROM `dcer` WHERE `rollno` = ?");
        $stmt->bind_param("s", $rollno);
    } elseif (!empty($regno)) {
        $stmt = $con->prepare("SELECT * FROM `dcer` WHERE `regno` = ?");
        $stmt->bind_param("s", $regno);
    } 
    
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_assoc();
    $stmt->close();
    if ($row) {  
        $cerid =$row['cer_id'];
        $name =$row['name'];
        $dept=$row['dept'];
        $regno= $row['regno'];
        $gradyear= $row['ofyear'];
        $cgpa= $row['cgpa'];
        $grade= $row['grade'];
    }else{
        echo '<script>  
        swal("Error", "No student record found", "error");  
        </script>';  
    }
}
if(isset($_POST['applynow'])){
    $cerid = $_POST['cerid'] ?? '';
    $currentDateTime = date('Y-m-d H:i:s');
    $payment = "Pending";
    $status = "Under process";
    $userid = $_SESSION['user_id'];
    $appid = $cerid.rand(10,9999); 
    $stmt = $con->prepare("UPDATE degree_cer SET appliedon = ?, payment = ?, status = ?, user_id=?, application_id=? WHERE cer_id = ?");
    $stmt->bind_param("sssisi", $currentDateTime, $payment, $status, $userid, $appid, $cerid);
    if($stmt->execute()){
        //echo '<script>swal("Success!", "You have been applied successfully! Please proceed with payment.", "success");</script>';
        // Add payment form redirect
        echo '<form method="post" id="paymentForm" action="ccavRequestHandler.php">'
            .'<input type="hidden" name="tid" value="76024495" />'
            .'<input type="hidden" name="merchant_id" value="213372" />'
            .'<input type="hidden" name="order_id" value="'.$cerid.'" />'
            .'<input type="hidden" name="amount" value="1.00" />'
            .'<input type="hidden" name="currency" value="INR" />'
            .'<input type="hidden" name="redirect_url" value="" />'
            .'<input type="hidden" name="cancel_url" value="" />'
            .'<input type="hidden" name="language" value="EN" />'
            .'<input type="hidden" name="merchant_param1" value="Degree Certificate" />'
            .'<input type="submit" value="Proceed to Pay" class="btn btn-success mt-3" />'
            .'</form>';
    } else {
        echo '<script>swal("Error", "Error saving data", "error");</script>';
    }
    $stmt->close();
}
?>

<div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Degree Certificate application
                    </h6>
                  </div>
    <div class="card-body">
                  <!-- <h3 class='mb-4'>Select Year</h3> -->
    <form method='POST'>
        <div class='mb-3'>
            <label for='year' class='form-label'>Choose a Graduation Year:</label>
            <select name='year' id='year' class='form-select'>
                <?php
                for ($year = 2024; $year >= 1980; $year--) {
                    echo "<option value='$year'" . ($selectedYear == $year ? ' selected' : '') . ">$year</option>";
                }
                ?>
            </select>
        </div>
        <button type='submit' class='btn btn-primary'>Go</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($selectedYear)): ?>
        <?php if ($selectedYear < 2020): ?>
            <!-- Form B: If Year is Before 2020 -->
            <div class='mt-4 p-4 border rounded'>
                <h4>New Application</h4>
                <form>
                    <div class='mb-3'>
                        <label class='form-label'>Degree applied for:</label>
                       
                        <select id="form_category" name="need" class="form-control" required="required" data-error="Please specify your Degree.">
                                                    <option value="" selected disabled>--Select Your Degree--</option>
                                                    <option >BAH</option>
                                                    <option >BA Pass</option>
                                                    <option >BSC</option>
                                                    <option >MA</option>
                                                    <option >MSC</option>
                        </select>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Mention Pass or Honours, with Class, Division:</label>
                        <input type='text' class='form-control' placeholder='Enter your address'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Name:</label>
                        <input type='text' class='form-control' placeholder='Enter your name'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Registration No.:</label>
                        <input type='text' class='form-control' placeholder='Enter your address'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Father name:</label>
                        <input type='text' class='form-control' placeholder='Enter your name'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Roll No.:</label>
                        <input type='text' class='form-control' placeholder='Enter your address'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Year of passing:</label>
                        <input type='text' class='form-control' placeholder='Enter your name'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Name of College /Dept.:</label>
                        <input type='text' class='form-control' placeholder='Enter your address'>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Complete Address:</label>
                        <input type='text' class='form-control' placeholder='Enter your name'>
                    </div>
                    
                    <button type='submit' class='btn btn-success'>Submit</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Form A: If Year is 2020 or Later -->
              
            <div class='mt-4 p-4 border rounded'>
                <h4>Academic Details</h4>
                <form method="post">
                    <div class='mb-3'>
                        <label class='form-label'>Roll Number:</label>
                        <!-- <input type='text' name="rollno" class='form-control' placeholder='Enter your roll number'> -->
                        <input type='text' name="rollno" class='form-control' placeholder='Enter your roll number' value="<?php echo isset($_POST['rollno']) ? htmlspecialchars($_POST['rollno']) : ''; ?>">

                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Registration No:</label>
                        <input type='text' name="regno" class='form-control' placeholder='Enter your degree' value="<?php echo isset($_POST['regno']) ? htmlspecialchars($_POST['regno']) : ''; ?>">
                    </div>
                    <button type='submit' name="searchrollno" class='btn btn-success'>Search</button>
                </form>

            </div>        
                    
                    
           
               
        <?php endif; ?>
    <?php endif; ?>
    <!-- <div class='mt-4 p-4 border rounded' id="details"> -->
    <div class='mt-4 p-4 border rounded' id="details" style="<?php echo ($name != '') ? '' : 'display:none;'; ?>">
    <form method="post">
            <div class="row">
             <div class="col-md-8">
                <div class="card mb-3" id="view-mode">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Roll No</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $cerid; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $name; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Department</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $dept; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Registration Number</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $regno; ?>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Year</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $gradyear; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">CGPA</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $cgpa; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Grade</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?php echo $grade; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                        <input type="hidden" name="cerid" value="<?php echo $cerid; ?>">  <!-- Preserve cer_id -->
                        <button class="btn btn-info mt-3" type="submit" name="applynow">Proceed to Payment</button>
                        </div>
                    </div>
                    </div>				
                </div>
            </div>
        </div> 
    </form>
    </div>
</div>  

<?php require('footer.inc.php') ?>