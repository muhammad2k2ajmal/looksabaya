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
$customer = new CommCustomers();
$auth = new Authentication();

// $banners=$common->getAllBanners();
// $testimonials=$common->getAllTestimonials();
// var_dump($banners);

if (isset($_POST['submit'])) {
    $firstName = $conn->addStr($_POST['first_name']);
    $surname = $conn->addStr($_POST['surname']);
    $email = $conn->addStr($_POST['email']);
    $password = $conn->addStr($_POST['password']);
    $confirmPassword = $conn->addStr($_POST['confirm_password']);

    // Validate password match
    if ($password !== $confirmPassword) {
        $_SESSION['errmsg'] = 'Passwords do not match.';
        header("Location: abayalooks-create-account.php");
        exit;
    }

    // Check if user already exists
    $checkUserExist = $auth->checkCustomer($email);
    if ($checkUserExist == 0) {
        // Assuming register method returns customer_id or true on success
        $sqlRegister = $auth->register($firstName, $surname, $email, $password);
        if ($sqlRegister) {
            // If register returns customer_id, use it; otherwise assume true
            $_SESSION['USER_LOGIN'] = is_numeric($sqlRegister) ? $sqlRegister : $auth->getCustomerId($email); // Adjust based on your register method
            $_SESSION['msg'] = 'Sign Up successful.';
            header("Location: abayalooks-dashboard.php");
            exit;
        } else {
            $_SESSION['errmsg'] = 'Sorry, some error occurred in register.';
            header("Location: abayalooks-create-account.php");
            exit;
        }
    } else {
        $_SESSION['errmsg'] = 'This email already exists.';
        header("Location: abayalooks-create-account.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account - Abayalooks</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/plugin.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="js/toastr/css/toastr.min.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

    <div class="container">
        <div class="row" style="margin: 80px auto;">

            <!-- Left Side - Slider -->
            <div class="col-md-6 d-none d-md-block p-0">
                <div id="slider" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                        <div class="carousel-item active h-100">
                            <img src="images/background.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
                            <div class="carousel-caption d-none d-md-block"></div>
                        </div>
                        <div class="carousel-item h-100">
                            <img src="images/background1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
                            <div class="carousel-caption d-none d-md-block"></div>
                        </div>
                        <div class="carousel-item h-100">
                            <img src="images/background2.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
                            <div class="carousel-caption d-none d-md-block"></div>
                        </div>
                    </div>
                    <div class="carousel-indicators mb-4">
                        <button type="button" data-bs-target="#slider" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#slider" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#slider" data-bs-slide-to="2"></button>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center p-4 login-page">
                <div class="w-100" style="max-width: 400px;">
                    <h2 class="mb-3">Create an account</h2>
                    <p class="text-dark-50 mb-4">Already have an account? <a href="abayalooks-login.php" class="text-decoration-none text-dark">Log in</a></p>

                    <form id="signupForm" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" placeholder="First name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" placeholder="Last name" name="surname" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                        </div>

                        <!-- Password Field: Create Password -->
                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" placeholder="Create Password" id="createPassword" name="password" required>
                            <button type="button" onclick="togglePassword('createPassword', this)" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-3 text-dark-50">
                                <i class="ri-eye-off-line"></i>
                            </button>
                        </div>

                        <!-- Password Field: Confirm Password -->
                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" placeholder="Confirm Password" id="confirmPassword" name="confirm_password" required>
                            <button type="button" onclick="togglePassword('confirmPassword', this)" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-3 text-dark-50">
                                <i class="ri-eye-off-line"></i>
                            </button>
                        </div>

                        <div class="mb-3 form-check custom-checkbox">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label ms-2" for="terms"> I agree to the <a href="abayalooks-terms-&-conditions.php" class="text-dark">Terms & Conditions</a></label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" name="submit" class="btn btn-dark">Create account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'include/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/popper.js" defer=""></script>
    <script src="js/bootstrap.js" defer=""></script>
    <script src="js/custom.js" defer=""></script>
    <script src="js/toastr/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('ri-eye-line', isHidden);
            icon.classList.toggle('ri-eye-off-line', !isHidden);
        }

        $(document).ready(function () {
            $('#signupForm').validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 50
                    },
                    surname: {
                        required: true,
                        maxlength: 50
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 15
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#createPassword"
                    },
                    terms: {
                        required: true
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter your first name",
                        maxlength: "First Name cannot be more than 50 characters"
                    },
                    surname: {
                        required: "Please enter your surname",
                        maxlength: "Surname cannot be more than 50 characters"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot be more than 100 terms"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Password must be at least 5 characters long",
                        maxlength: "Password cannot be more than 15 characters"
                    },
                    confirm_password: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    terms: {
                        required: "You must agree to the Terms & Conditions"
                    }
                },
                submitHandler: function (form) {
                    form.submit(); // Ensure form submits to PHP
                }
            });
        });
    </script>
</body>

</html>