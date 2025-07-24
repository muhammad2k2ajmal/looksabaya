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

$payId = (isset($_REQUEST['payId']) ? base64_decode($_REQUEST['payId']) : '');
$row = $db->getData("SELECT * FROM `orders_table` WHERE `order_id` = '$payId'");
$customer_id=$row['customer_id'];
$address_id=$row['address_id'];

$getCustomer = $db->getData("SELECT * FROM `customers` WHERE `customer_id` = '".$customer_id."'");
// var_dump($row[0]);
$getShipping = $db->getData("SELECT * FROM `order_ship_address` WHERE `id` = '".$address_id."' AND `customer_id` = '".$customer_id."'");
// var_dump($getCustomer);
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
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title text-center">Customer Details</h4>                      
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="card-body pt-0">
                                    <div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Name :
                                            </p>
                                          <p class="text-body-emphasis fw-semibold"><?php echo $getCustomer['firt_name'] ?? ''; ?> <?php echo $getCustomer['surname'] ?? ''; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>Phone :
                                            </p>
                                            <p class="text-body-emphasis fw-semibold"><?php echo $getCustomer['phone'] ?? ''; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold"><i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                                            <p class="text-body-emphasis fw-semibold"><?php echo $getCustomer['email'] ?? ''; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-map-pin text-secondary fs-20 align-middle me-1"></i>Address :
                                            </p>
                                            <p class="text-body-emphasis fw-semibold">
                                                <?php if (!empty($getCustomer['house_no']) || !empty($getCustomer['place_name']) ||!empty($getCustomer['street_name']) || !empty($getCustomer['addition']) || !empty($getCustomer['postal_code']) || !empty($getCustomer['country'])): ?>
                                                    <?php echo $getCustomer['house_no'] . ', ' . $getCustomer['place_name'] .', ' . $getCustomer['street_name'] . ', ' . $getCustomer['addition'] . ', ' . $getCustomer['postal_code'] . ' - ' . $getCustomer['country']; ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title text-center">Shipping Details</h4>                      
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="card-body pt-0">
                                    <div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Name :
                                            </p>
                                          <p class="text-body-emphasis fw-semibold"><?php echo $getCustomer['firt_name'] ?? ''; ?> <?php echo $getCustomer['surname'] ?? ''; ?></p>
                                            
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-phone text-secondary fs-20 align-middle me-1"></i>Phone :
                                            </p>
                                            <p class="text-body-emphasis fw-semibold"><?php echo $getShipping['phone'] ?? ''; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :
                                            </p>
                                            <p class="text-body-emphasis fw-semibold"><?php echo $getShipping['email'] ?? ''; ?></p>
                                        </div>                                        
                                        <div class="d-flex justify-content-between">
                                            <p class="text-body fw-semibold">
                                                <i class="iconoir-map-pin text-secondary fs-20 align-middle me-1"></i>Address :
                                            </p>
                                            <p class="text-body-emphasis fw-semibold">
                                                <?php if (!empty($getCustomer['house_no']) || !empty($getCustomer['place_name']) ||!empty($getCustomer['street_name']) || !empty($getCustomer['addition']) || !empty($getCustomer['postal_code']) || !empty($getCustomer['country'])): ?>
                                                    <?php echo $getCustomer['house_no'] . ', ' . $getCustomer['place_name'] .', ' . $getCustomer['street_name'] . ', ' . $getCustomer['addition'] . ', ' . $getCustomer['postal_code'] . ' - ' . $getCustomer['country']; ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12"> 
                                                       
                            <div class="card">
                                
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">View Order Details</h4>                     
                                        </div>
                                    </div>                                 
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="table-light">
                                              <tr>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th class="text-end">Order Date</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $totalAmount=0;
                                                
                                                // foreach($row as $productRow):
                                                    
                                                    $getProductros = $db->getAllData("SELECT * FROM `order_product_details` WHERE `order_id` = '".$row['order_id']."'");
                                                foreach($getProductros as $productRow):
                                                    $Product = $db->getData("SELECT * FROM `product` WHERE `product_id` = '".$productRow['product_id']."'");
                                                
                                                    $totalAmount= $totalAmount + $productRow['product_total_price'];
                                                ?>
                                                    
                                                <tr>
                                                    <td>
                                                        <img src="../adminuploads/products/<?php echo $Product['image']; ?>" alt="" height="40">
                                                        <p class="d-inline-block align-middle mb-0">
                                                            <span class="d-block align-middle mb-0 product-name text-body">
                                                                <?php echo $productRow['product_name']; ?>
                                                            </span>
                                                        </p>
                                                    </td>
                                                    <td>₹<?php echo $productRow['product_price']; ?></td>
                                                    <td>
                                                        <?php echo $productRow['product_quantity']; ?>
                                                    </td>                                                    
                                                    <td>₹<?php echo $productRow['product_total_price']; ?></td>
                                                    <td class="text-end"><?php echo date('d-m-Y',strtotime($productRow['created_at'])); ?></td>
                                                </tr> 
                                                <?php endforeach;?>                                               
                                            </tbody>
                                        </table>
                                        <h3 style="text-align: right;">Sub Total: ₹<?= $row['subtotal']; ?></h3>
<br>
<h3 style="text-align: right;">Coupon Code (<?= $row['coupon_code']; ?>, <?= $row['couponDiscountPercentage']; ?>%): ₹<?= $row['coupon_discount']; ?></h3>
<h3 style="text-align: right;">Shipping Charge : ₹<?= $row['shipping_charge']; ?></h3>
<h2 style="text-align: right;">Total Amount: ₹<?= $row['total']; ?></h2>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>                                      
                </div>
                
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