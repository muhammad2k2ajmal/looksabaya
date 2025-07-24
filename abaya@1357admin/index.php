<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';

$db = new dbClass();
$auth = new Authentication();

// user login check
if (isset($_REQUEST['btn_login']) && $_REQUEST['btn_login'] == 'Login') {
    $email = $db->addStr($_POST['email']);
    $pass = $db->addStr($_POST['password']);

    $result = $auth->adminLogin($email,$pass);

    if ($result == true) {
        header('Location: home.php');
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid email or password.";
        header('Location: index.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

    <head>     
        <meta charset="utf-8" />
        <title>Login - Looksabaya | Admin</title>
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
    <body class="bg-login">
        <div class="container-xxl">
            <div class="row vh-100 d-flex justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 mx-auto">
                                <div class="card">
                                    <div class="card-body p-0 auth-header-box rounded-top">
                                        <div class="text-center p-3 d-flex justify-content-center align-items-center">
                                            <div class="logo logo-admin">
                                                <img src="../assets/img/logo.png" width="110px" alt="logo" class="auth-logo">
                                            </div>
                                            <!-- <div class="logo logo-admin">
                                                <img src="images/logo-text.jpg" width="270px" alt="logo" class="auth-logo">
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="card-body p-4 pt-0">    
                                        <form method="post" class="row g-3 needs-validation" novalidate="">
                                            <div class="form-group mb-2">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>   
                                                <div class="invalid-feedback">
                                                    Please Enter Your Email.
                                                </div>                            
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="userpassword">Password</label>                                            
                                                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" required>  
                                                <div class="invalid-feedback">
                                                    Please Enter Your Password.
                                                </div>                          
                                            </div>
                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                    <div class="d-grid mt-3">
                                                        <button class="btn btn-primary login-btn" type="submit" name="btn_login" value="Login">
                                                            Log In <i class="fas fa-sign-in-alt ms-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>                                 
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-body-->
                </div><!--end col-->
            </div><!--end row-->                                        
        </div><!-- container -->

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
            (function () {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
                })
            })()
        </script>
    </body>
    <!--end body-->
</html>