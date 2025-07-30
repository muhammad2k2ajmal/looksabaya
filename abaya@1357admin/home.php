<?php

if(!isset($_SESSION)){session_start();}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';

$db = new dbClass();
$auth = new Authentication();
$auth->checkSession();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Looksabaya | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="icon" type="image/x-icon" sizes="20x20" href="../assets/img/logo.png">  
     <!-- App css -->
     <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <?php include 'include/header.php'; ?>

    <?php include 'include/sidebar.php'; ?>

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Orders</p>
                                        <?php
                                            $totalOrdersCount = $db->getRowCount("SELECT order_id FROM `orders_table`");
                                        ?>
                                        <h3 class="mt-2 mb-0 fw-bold"><?php echo $totalOrdersCount; ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto ho-or">
                                            <i class="fas fa-receipt h1 align-self-center mb-0 text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 align-self-center text-center mt-3 ho-or-btn">
                                    <a href="view-orders.php" class="btn btn-outline-warning btn-sm px-2">More Info</a> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">User Registrations</p>
                                        <?php
                                            $totalUsersCount = $db->getRowCount("SELECT customer_id FROM `customers`");
                                        ?>
                                        <h3 class="mt-2 mb-0 fw-bold"><?php echo $totalUsersCount; ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto ho-ur">
                                            <i class="far fa-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 align-self-center text-center mt-3 ho-ur-btn">
                                    <a href="view-customers.php" class="btn btn-outline-secondary btn-sm px-2">More Info</a>  
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Category</p>
                                        <?php
                                            $totalCategory = $db->getRowCount("SELECT id FROM `category`");
                                        ?>
                                        <h3 class="mt-2 mb-0 fw-bold"><?php echo $totalCategory; ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto ho-c">
                                            <i class="iconoir-grid-minus h1 align-self-center mb-0 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 align-self-center text-center mt-3 ho-c-btn">
                                    <a href="view-category.php" class="btn btn-outline-success btn-sm px-2">More Info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Products</p>
                                        <?php
                                            $totalProducts = $db->getRowCount("SELECT product_id FROM `product`");
                                        ?>
                                        <h3 class="mt-2 mb-0 fw-bold"><?php echo $totalProducts; ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto ho-pt">
                                            <i class="fas fa-chart-pie h1 align-self-center mb-0 text-info"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 align-self-center text-center mt-3 ho-pt-btn">
                                    <a href="view-products.php" class="btn btn-outline-info btn-sm px-2">More Info</a>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div><!-- container -->
            
            <?php include 'include/footer.php'; ?>

        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->
    
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="libs/simplebar/simplebar.min.js"></script>

    <script src="libs/apexcharts/apexcharts.min.js"></script>
    <script src="data/stock-prices.js"></script>
    <script src="libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="libs/jsvectormap/maps/world.js"></script>
    <script src="js/pages/index.init.js"></script>
    <script src="js/app.js"></script>
</body>
<!--end body-->

</html>