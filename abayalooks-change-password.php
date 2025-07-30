<?php
if (!isset($_SESSION)) {
	session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$conn = new dbClass();
$common = new CommProducts();

// $banners=$common->getAllBanners();
// $testimonials=$common->getAllTestimonials();
// var_dump($banners);
$customer = new CommCustomers();

$customer->checkSession($_SESSION['USER_LOGIN'] ?? null );

if (isset($_POST['btn_pass'])) {
  $current_pass = $conn->addStr(trim($_POST['current_pass']));
  $new_pass = $conn->addStr(trim($_POST['new_pass']));
  $cnf_pass = $conn->addStr(trim($_POST['cnf_pass']));

  $sqlPassword = $customer->userDetails($_SESSION['USER_LOGIN']);

  $Password = $sqlPassword['password'];

  if ($current_pass == $Password):
    $sql = $customer->passwordChange($new_pass, $_SESSION['USER_LOGIN']);
    $_SESSION['msg'] = "Password has been changed successfully !!";
    header("Location: abayalooks-change-password.php");
    exit;
  else:
    $_SESSION['errmsg'] = "You have entered wrong Current Password.!";
    header("Location: abayalooks-change-password.php");
    exit;
  endif;
}

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
            <div class="wrapper">
                <?php include 'include/sidebar.php'; ?>


                <div class="content">
                    

                    <div class="password-form-card" id="changePasswordSection" style="display: block;">
                        <h4>Change Password</h4>
                        								<form method="post" id="password" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_pass">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="new_pass">
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmNewPassword" name="cnf_pass">
                            </div>
                            <button type="submit" name="btn_pass" class="btn btn-primary">Update Password</button>
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
		$("#password").validate({
			rules: {
			current_pass: {
				required: true,
				maxlength: 15
			},
			new_pass: {
				required: true,
				maxlength: 15
			},
			cnf_pass: {
				required: true,
				maxlength: 15,
				equalTo: "#newPassword"
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
			cnf_pass: {
				required: "Please enter confirm password.",
				maxlength: "Confirm Password must not exceed 15 characters.",
				equalTo: "New password and confirm password do not match."
			}
			}
		});
		});
  </script>

</html>