<?php
include('connection.inc.php');
include('functions.inc.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NEHU - Online Certificate</title>

    
    <!-- Bootstrap JS and Popper.js (required for tooltips) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>


    <!-- Sweetalert -->

    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css
    " rel="stylesheet">

    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
      href="vendor/datatables/dataTables.bootstrap4.min.css"
      rel="stylesheet"
    />
    
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
        .hidden {
            display: none;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <img class="img-responsive" style="max-height:40px; width: auto;" src="images/NEHU_logo.png">
                <div class="sidebar-brand-text mx-3">Examination <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Home</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Apply for
            </div>
            <li class="nav-item">
                <a class="nav-link" href="certificateapply.php?type=degree">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Online Certificate</span></a>
            </li>

              <!-- Divider -->
              <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    My application
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="mydegreeapplications.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>My Certificates</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myapplications.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Manual Certificates</span></a>
                </li>
            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Degree Certificate</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"></h6>
                        <a class="collapse-item" href="regcardapply.php">Original</a>
                        <a class="collapse-item" href="cards.html">Duplicate</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Utilities Collapse Menu
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Migration</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Degree</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Re-Evaluation</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Migration</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Migration</span></a>
            </li> -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Admin
                </div>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Dashboard</span></a>
                </li>

                <!-- Nav Item - Charts -->
                <li class="nav-item">
                    <a class="nav-link" href="certificateslist.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Direct Certificates</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="certificateslistmanual.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Manual Certificates</span></a>
                </li>

                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="uploaddata.php">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Upload data</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

                <!-- Sidebar Message
                <div class="sidebar-card d-none d-lg-flex">
                    <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                    <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                    <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
                </div> -->
            <?php endif; ?>    
      
        <!-- End of Sidebar -->

                 <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'dispatch'): ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Admin
                </div>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link" href="dispatch_update.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Dispatch</span></a>
                </li>
                <?php endif; ?>
          </ul>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <!-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->
                    <!-- <div class="container">
                     <h2>Online Certificates services</h2>
                    </div> -->
                    <div
                          class="text-lg font-weight-bold text-success text-uppercase mb-1"
                        >
                        NEHU Online Certificates services
                        </div>

                    <?php if (isset($_SESSION['user_id'])){ ?>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                       
                        <div class="topbar-divider d-none d-sm-block"></div>
                       
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome, <?php echo $_SESSION['user_email']; ?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                           
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                -->
                                <a class="dropdown-item" href="profile.php?type=view&id=self">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a> 
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="change_password.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                    
                                </a>
                            </div>
                           
                        </li>
                      
                    </ul>
                <?php }?>           
                </nav>
                <!-- End of Topbar -->
                <div class="container-fluid">

                