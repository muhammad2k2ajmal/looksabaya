<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';
require 'functions/operations.php';

$db = new dbClass();
$auth = new Authentication();
$customer = new Customers();

$auth->checkSession();

if(isset($_REQUEST['delete']) && $_REQUEST['delete']=='y'){
    $id = $_REQUEST['id'];
      $sqlDelete = $db->execute("DELETE FROM `customers` WHERE `customer_id` = '$id'");
      if($sqlDelete==true):
          $_SESSION["msg"] = 'Record Successfully Deleted ..!!';
      else:
          $_SESSION["errmsg"] = 'Sorry !! Some Error Accurd .. Try Again';
      endif;
    header("Location: view-customers.php");
    exit();
  }

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
                                            <h4 class="card-title">View Customers</h4>                      
                                        </div>
                                    </div>                                
                                </div>
                                
                                <div class="card-body pt-0">                                    
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="datatable_1">
                                            <thead class="table-light">
                                              <tr>
                                                <th class="ps-0">S.No</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Address</th>
                                                <th>Created At</th>                                            
                                                <th class="text-end">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $query = $customer->allCustomers();
                                                foreach ($query as $row):
                                                ?>
                                                    <tr>                                                        
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $row['first_name'].' '.$row['surname']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>                            
                                                        <td><?php echo $row['password']; ?></td>
                                                        <td>
                                                            <?php if (!empty($row['house_no']) || !empty($row['place_name']) ||!empty($row['street_name']) || !empty($row['addition']) || !empty($row['postal_code']) || !empty($row['country'])): ?>
                                                                <?php echo $row['house_no'] . ', ' . $row['place_name'] .', ' . $row['street_name'] . ', ' . $row['addition'] . ', ' . $row['postal_code'] . ' - ' . $row['country']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                        <td class="text-end"> 
                                                            <a href="?id=<?php echo $row['customer_id']; ?>&delete=y" 
                                                                onClick="return confirm('Are you sure !! Record will be delete parmanently ..!!')">
                                                                <i class="las la-trash-alt text-danger fs-18"></i>
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