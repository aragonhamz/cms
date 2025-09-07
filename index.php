<?php
require('top.inc.php');
$stmt = $con->prepare("SELECT * FROM posts ORDER BY post_date DESC");
//$stmt->bind_param("s", $email);
$stmt->execute();
$posts = $stmt->get_result();
//$poss = $res->fetch_assoc();
$stmt->close();

?>

	<!-- <div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">DASHBOARD </h4>
				</div>
			</div>
		  </div>

		  
		

	   </div>
	</div> -->

<section class="mt-8 text-center">
      		<img class="img-responsive" style="max-height:150px; width: auto;" src="images/NEHU_logo.png">
            <h1>Welcome to NEHU Online Certificates System</h1>
            <!-- <p>This is an introduction to our website. We provide various services and information to help you.</p> -->
            
        </section>

        <section class="mt-4 mb-5">
            <div class="card " id="guidelinesCard">
                <div class="card-body">
                    <h5 class="card-title">Guidelines</h5>
                    <p class="card-text "><i class="fa-regular fa-hand-point-right"></i> Candidates need to register to apply</p>
                    <p class="card-text "><i class="fa-regular fa-hand-point-right"></i> Fee will not be refunded in case of wrong information provided by the candidate</p>
                    <p class="card-text "><i class="fa-regular fa-hand-point-right"></i> Candidates information is final</p>
                    <?php if(isset($_SESSION['user_email'])){ ?>
                        <p class="card-text "><a href="" class="btn btn-primary">Click to apply</a></p>
                        <?php }else{ ?>
                        <a href="login.php" class="btn btn-primary profile-button">Login to apply</a>
                    <?php }?>
                    
                </div>
            </div>
        </section>
        
         <!-- Content Row -->
         <div class="row">
         <!-- Profile progress -->
        <?php if(isset($_SESSION['user_id'])){ 
          $stmt = $con->prepare("Select * from user_profile where userid = ?");
          $id = intval($_SESSION['user_id']);
          $stmt->bind_param("i", $id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          if($row){
          }else{
          ?>
         <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-info text-uppercase mb-1"
                        >
                          Profile
                        </div>
                        <div class="row no-gutters align-items-center mb-2">
                          <div class="col-auto">
                            <div
                              class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                            >
                              Incomplete
                            </div>
                          </div>
                          <div class="col">
                            <div class="progress progress-sm mr-2">
                              <div
                                class="progress-bar bg-info"
                                role="progressbar"
                                style="width: 50%"
                                aria-valuenow="50"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              ></div>
                            </div>
                          </div>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                            <a href="profile.php?type=view&id=self" class="btn btn-danger">Update Now</a>
                            </div>
                            
                    </div>
                      </div>
                      <div class="col-auto">
                        <i
                          class="fas fa-clipboard-list fa-2x text-gray-300"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php }
            }?>

          <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                        >
                          My Application
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php 
                        //  echo getRegcard($con);
                          ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
         </div>
              <!-- Approach -->
              <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Latest updates:
                    </h6>
                  </div>
                  <div class="card-body">
                    <p>
                    <div class='list-group'>
                      <?php foreach ($posts as $post): ?>
                          <?php
                              // Calculate the number of days since the post date
                              $post_date = strtotime($post['post_date']);
                              $current_date = time();
                              $days_since_post = ($current_date - $post_date) / (60 * 60 * 24);
                          ?>
                          <div class='post-item'>
                              <h5>
                                  
                                  <?php if ($days_since_post <= NEW_POST_DAYS): ?>
                                      <!-- <span class=''>NEW</span> -->
                                       <img src='images/new.png' width="40px">
                                  <?php endif; ?>
                                  <?php echo htmlspecialchars($post['title']); ?>
                              </h5>
                              <p class='post-date'>Posted on: <?php echo date('F d, Y', $post_date); ?></p>
                              <hr>
                          </div>
                      <?php endforeach; ?>
                  </div>
                    </p>
                   
                  </div>
                </div>

             
<?php
require('footer.inc.php');
?>