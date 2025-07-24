<?php

if (!isset($_SESSION)) {
    session_start();
}
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
         <link href="libs/simple-datatables/style.css" rel="stylesheet" type="text/css" />
         <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
         <link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
         <link href="css/app.min.css" rel="stylesheet" type="text/css" />
    </head>

    
    <!-- Top Bar Start -->
    <body>
        
    <?php include 'include/header.php'; ?>

    <?php include 'include/sidebar.php'; ?>

        <div class="page-wrapper">
            <!-- Page Content-->
            <div class="page-content">
                <div class="container-xxl"> 
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">View Orders</h4>                      
                                        </div>
                                    </div>                                
                                </div>
                                
                                <div class="card-body pt-0">                                    
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="datatable_1">
                                            <thead class="table-light">
                                              <tr>
                                                <th class="ps-0">S.No</th>                                                
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Order Amount</th>
                                                <th class="text-center">Order Number</th>
                                                <th class="text-center">Payment Status</th>
                                                <th class="text-center">Transaction Id</th>
                                                <th class="text-center">Created At</th>                                            
                                                <th class="text-end">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $i=1;
                                                $ordersQuery = $db->getAllData("SELECT * FROM `orders_table` ORDER BY `order_id` DESC");
                                                foreach($ordersQuery as $row):
                                                    $customer_id=$row['customer_id'];
                                                    $CustomerQuery = $db->getData("SELECT * FROM `customers` where `customer_id`= '$customer_id'");
                                                // $getProduct = $db->getData("SELECT * FROM `product` WHERE `product_id` = '".$row['product_id']."'");
                                                ?>
                                                    <tr>                                                        
                                                        <td><?php echo $i++; ?></td>                           
                                                        <td class="text-center"><?php echo $CustomerQuery['first_name'].' '.$CustomerQuery['surname']; ?></td>
                                                        <td class="text-center"><?php echo $row['total']; ?></td>                          
                                                        <td class="text-center"><?php echo $row['order_number']; ?></td>                          
                                                        <td class="text-center"><?php echo $row['payment_status']; ?></td>                          
                                                        <td class="text-center"><?php echo $row['transaction_id']; ?></td>                          
                                                        <td class="text-center"><?php echo date('d-m-Y',strtotime($row['created_at'])); ?></td>
                                                        <td class="text-end"> 
                                                            <a href="view-orders-details.php?payId=<?php echo base64_encode($row['order_id']); ?>">
                                                                <i class="las la-info-circle text-info fs-18"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>                                                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->                                     
                </div><!-- container -->
                
                <?php include 'include/footer.php'; ?>

            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- Javascript  -->  
        <!-- vendor js -->
        
        <script src="js/jquery-3.6.0.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="libs/simplebar/simplebar.min.js"></script>
        <script src="libs/simple-datatables/umd/simple-datatables.js"></script>
        <script src="js/pages/datatable.init.js"></script>
        <script src="js/pages/form-validation.js"></script>
        <script src="libs/toastr/js/toastr.min.js"></script>
        <script src="js/toastr-init.js"></script>
        <script src="js/app.js"></script>

        <script>
            <?php if (isset($_SESSION['msg'])): ?>
                toastr.success("<?php echo $_SESSION['msg']; ?>");
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['errmsg'])): ?>
                toastr.error("<?php echo $_SESSION['errmsg']; ?>");
                <?php unset($_SESSION['errmsg']); ?>
            <?php endif; ?>
        </script>

    </body>
    <!--end body-->
</html>