<?php
require('top.inc.php');
?>

<div
              class="d-sm-flex align-items-center justify-content-between mb-4"
            >
              <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
              <a
                href="#"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                ><i class="fas fa-download fa-sm text-white-50"></i> Generate
                Report</a
              >
            </div>

            <!-- Content Row -->
            <div class="row">
              <!-- GET CERTIFICATES APPROVAL-->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                        >
                          Pending Approval Direct Certificate
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-danger-800">
                         <a href="certificateslist.php" class="text-danger"> <?php 
                         echo getApprovedDcer($con);
                          ?></a>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

               <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                        >
                          Pending Approval Manual Certificate
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-danger-800">
                         <a href="certificateslistmanual.php" class="text-danger"> <?php 
                         echo getApprovedMDcer($con);
                          ?></a>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-success text-uppercase mb-1"
                        >
                      
                          <a href="users.php" >Registered Users</a>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php 
                         echo getRegusers($con);
                          ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                        >
                          <a href="dashboard_news.php">Dashboard Message</a>
                        </div>
                        <div class="row no-gutters align-items-center">
                          <!-- <div class="col-auto">
                            <div
                              class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                            >
                              50%
                            </div>
                          </div> -->
                          <div class="col">

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

             

             <!-- Dashboard -->
             <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Admin Settings
                    </h6>
                  </div>
                  <div class="card-body">
                    <p>
                      <ul>
                        <li><a href="manage_templates.php">Certificate Templates</a></li>
                        <li><a href="admin-document-types.php">Manage Document type</a></li>
                      </ul>
                    </p>
                    <p class="mb-0">
                      
                    </p>
                  </div>
                </div>
<?php
require('footer.inc.php');
?>