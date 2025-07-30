<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');
require "config/authentication.php";

$conn = new dbClass();
$common = new CommProducts();
$auth = new Authentication();

// $banners=$common->getAllBanners();
// $testimonials=$common->getAllTestimonials();
// var_dump($banners);
$customer = new CommCustomers();

if (isset($_POST['login'])):
    $login_email = $conn->addStr($_POST['login_email']);
    $login_pass = $conn->addStr($_POST['login_pass']);

    if (!empty($login_email) && !empty($login_pass)):

        $sqlLogin = $auth->userLogin($login_email, $login_pass);
        if (!empty($sqlLogin['customer_id'])):
            $_SESSION['USER_LOGIN'] = $sqlLogin['customer_id'];
            $_SESSION['msg'] = 'Login successfull.';
            if (isset($_SESSION['USER_CHECKOUT']) && $_SESSION['USER_CHECKOUT'] == 'checkout') {
                unset($_SESSION['USER_CHECKOUT']);
                header("Location: looksabaya-cart.php");
                exit;
            } else {
                header("Location: abayalooks-dashboard.php");
                exit;
            }
        else:
            $_SESSION['errmsg'] = 'Wrong email id or password';
            header("Location: abayalooks-login.php");
            exit;
        endif;

    endif;

endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looksabaya</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="icon" href="images/favicon.png" type="image/x-icon"> -->
    <!-- <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="js/toastr/css/toastr.min.css">



</head>

<body>
    <div id="pageWrapper">
        <?php include 'include/header.php'; ?>


        <div class="container">
            <div class="row" style="margin: 80px auto;">

                <!-- Left Side - Slider -->
                <div class="col-md-6 d-none d-md-block p-0">
                    <div id="slider" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">
                            <div class="carousel-item active h-100">
                                <img src="images/background.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
                                <div class="carousel-caption d-none d-md-block">

                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="images/background1.jpg" class="d-block w-100 h-100 object-fit-cover"
                                    alt="...">
                                <div class="carousel-caption d-none d-md-block">

                                </div>
                            </div>
                            <div class="carousel-item h-100">
                                <img src="images/background2.jpg" class="d-block w-100 h-100 object-fit-cover"
                                    alt="...">
                                <div class="carousel-caption d-none d-md-block">

                                </div>
                            </div>
                        </div>
                        <div class="carousel-indicators mb-4">
                            <button type="button" data-bs-target="#slider" data-bs-slide-to="0" class="active"
                                aria-current="true"></button>
                            <button type="button" data-bs-target="#slider" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#slider" data-bs-slide-to="2"></button>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="col-md-6 d-flex align-items-center justify-content-center p-4 login-page">
                    <div class="w-100" style="max-width: 400px;">
                        <h2 class="mb-3">Login Account</h2>


                        <form id="loginForm" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="login_email" id="email" placeholder="you@example.com"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="login_pass"
                                    placeholder="Enter your password" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <a href="abayalooks-forgotpassword.php" class="text-decoration-none text-muted"
                                    style="font-size: 14px;">Forgot password?</a>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-theme"> <a href="abayalooks-dashboard.php"
                                        class="text-white">Login</a></button>
                            </div>

                            <p class="text-center mt-3 mb-0" style="font-size: 14px;">
                                New to Abayalooks? <a href="abayalooks-create-account.php" class="text-decoration-none"
                                    style="color: #222;">Create Account</a>
                            </p>
                        </form>

                    </div>
                </div>

            </div>
        </div>


    </div>
    <?php include 'include/footer.php'; ?>

</body>

<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>
<script src="js/toastr/js/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.js"></script>
<script src="assets/js/main.js"></script>
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
        $('#loginForm').validate({
            rules: {          
            login_email: {
                required: true,
                email: true,
                maxlength: 100
            },          
            login_pass: {
                required: true,
                maxlength: 15
            },
            },
            messages: {          
            login_email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
                maxlength: "Email cannot be more than 100 characters"
            },          
            login_pass: {
                required: "Please enter password",
                maxlength: "Password cannot be more than 15 characters"
            },
            },
        });                  
        });
  </script>
</html>