<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';

$db = new dbClass();
$auth = new Authentication();
$password = new ChangePassword();

$auth->checkSession();

if (isset($_POST['change'])) {
  
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
  
    // Validate if new password and confirm password match
    if ($new_pass !== $confirm_pass) {
      $_SESSION['errmsg'] = " New password and Confirm password do not match ..!!";
    } else {
      // Validate the old password
      if ($password->verifyPassword($_SESSION['ADMIN_USER_ID'], $current_pass)) {
        // Change the password
        if ($password->changePassword1($_SESSION['ADMIN_USER_ID'], $new_pass)) {
          $_SESSION['msg'] = " Password updated successfully ..!!";
        } else {
          $_SESSION['errmsg'] = " Failed to update password ..!!";
        }
      } else {
        $_SESSION['errmsg'] = " Incorrect old password.";
      }
    }
    header('Location: change-password.php');
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
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header p-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">         
                                            <h4 class="card-title">Change Password</h4>                                 
                                        </div>
                                    </div>                                 
                                </div>
                                <div class="card-body">                                    
                                    <form id="pass-form" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                        <div class="row my-3">
                                            <div class="col-md-5">
                                            <label class="form-label" for="current">Current Password</label>
                                            <input name="current_pass" type="password" class="form-control" placeholder="Enter Current Password" id="current" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                            <label class="form-label" for="new">New Password</label>
                                            <input name="new_pass" type="password" class="form-control" placeholder="Enter New Password" id="new" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                            <label class="form-label" for="confirm">Confirm Password</label>
                                            <input name="confirm_pass" type="password" class="form-control" placeholder="Enter Confirm Password" id="confirm" required>
                                            </div>
                                        </div>  

                                        <div class="col-12 m-0">
                                            <input type="submit" class="btn btn-primary login-btn" name="change" value="Change Password">
                                        </div>
                                    </form>                                                
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
        
        <script src="js/jquery-3.6.0.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="libs/simplebar/simplebar.min.js"></script>
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

        <script>
            $(document).ready(function () {
                $("#pass-form").validate({
                    rules: {
                        current_pass: {
                            required: true,
                            maxlength: 15
                        },
                        new_pass: {
                            required: true,
                            maxlength: 15
                        },
                        confirm_pass: {
                            required: true,
                            maxlength: 15,
                            equalTo: "#new"
                        }
                    },
                    messages: {
                        current_pass: {
                            required: "Please enter current password.",
                            maxlength: "Current Password must not exceed 15 characters."
                        },
                        new_pass: {
                            required: "Please enter new password.",
                            maxlength: "New Password must not exceed 15 characters."
                        },
                        confirm_pass: {
                            required: "Please enter confirm password.",
                            maxlength: "Confirm Password must not exceed 15 characters.",
                            equalTo: "New password and confirm password do not match."
                        }
                    },
                    errorClass: "is-invalid", // Class for invalid fields
                    validClass: "is-valid", // Class for valid fields
                    highlight: function (element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function (error, element) {
                        // Remove any existing error message for this element
                        $(element).siblings(".invalid-feedback").remove();
                        // Append the error message to the element's parent
                        $(element).after(error);
                        // Apply red color to error message
                        $(error).css('color', 'red'); // Set error message color to red
                    },
                    submitHandler: function (form) {
                        form.submit(); // Submit the form when valid
                    }
                });

                // Instant validation for dropdowns
                $("select").change(function () {
                    if ($(this).valid()) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        $(this).siblings(".invalid-feedback").remove();
                    } else {
                        $(this).addClass("is-invalid");
                    }
                });
            });
        </script>

    </body>
    <!--end body-->
</html>