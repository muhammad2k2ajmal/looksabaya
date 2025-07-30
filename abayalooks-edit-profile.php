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

$customer->checkSession($_SESSION['USER_LOGIN'] ?? null);
$userDetail = $customer->userDetails($_SESSION['USER_LOGIN']);

if (isset($_REQUEST['update'])) {
  // Get form data and sanitize it
  $firstName = $conn->addStr(trim($_POST['firstName']));
  $surname = $conn->addStr(trim($_POST['surname']));
  $phone = $conn->addStr(trim($_POST['phone']));
  $old_phone = $conn->addStr(trim($_POST['old_phone']));
  $email = $conn->addStr(trim($_POST['email']));
  $old_email = $conn->addStr(trim($_POST['old_email']));
  $country = $conn->addStr(trim($_POST['country']));
  $postalCode = $conn->addStr(trim($_POST['postalCode']));
  $houseNo = $conn->addStr(trim($_POST['houseNo']));
  $addition = $conn->addStr(trim($_POST['addition']));
  $streetName = $conn->addStr(trim($_POST['streetName']));
  $placeName = $conn->addStr(trim($_POST['placeName']));
  $checkPhoneExists = false;
  $checkEmailExists = false;

  // Check if the phone number was changed and if the new one already exists
  if ($old_phone !== $phone) {
    $checkPhoneExists = $customer->checkCustomerPhone($phone);
  }

  // Check if the email was changed and if the new one already exists
  if ($old_email !== $email) {
    $checkEmailExists = $customer->checkCustomer($email);
  }

  // Only proceed if neither phone nor email already exists
  if ($checkPhoneExists == 0 && $checkEmailExists == 0) {
    $sqlQuery = $auth->updateuserProfile(
      $firstName,
      $surname,
      $phone,
      $email,
      $country,
      $postalCode,
      $houseNo,
      $addition,
      $streetName,
      $placeName,
      $_SESSION['USER_LOGIN']
    );

    if ($sqlQuery == true) {
      $shippingCount = $customer->getCustomerAddressCountByType('shipping');
      $billingCount = $customer->getCustomerAddressCountByType('billing');

      if ($shippingCount == 0) {
        $auth->addCustomerAddress($_SESSION['USER_LOGIN'], 'shipping', $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $firstName, $surname, $phone, $email);
      }

      if ($billingCount == 0) {
        $auth->addCustomerAddress($_SESSION['USER_LOGIN'], 'billing', $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $firstName, $surname, $phone, $email);
      }

      $_SESSION['msg'] = "Your Profile Updated Successfully ..";
      header("Location: abayalooks-dashboard.php");
      exit;
    } else {
      $_SESSION['errmsg'] = "Sorry !! Some Error ..";
    }
  } else {
    $_SESSION['errmsg'] = "Email or phone number already in use.";
  }
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
                    <div class="profile-card active" id="profileSection" >
                        <div class="profile-edit-form active" id="profileEditForm" style="display: block;">
                            <form id="updateprofile" method="post" enctype="multipart/form-data">

                                <input type="hidden" name="old_phone" value="<?= $userDetail['phone'] ?>">
                                <input type="hidden" name="old_email" value="<?= $userDetail['email'] ?>">
                                    <h4>Edit Profile</h4>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="editName" class="form-label">Firstname*</label>
                                            <input type="text" class="form-control" id="editName" name="firstName"
                      value="<?= $userDetail['first_name'] ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="editPhone" class="form-label">Surname*</label>
                                            <input type="text" class="form-control" id="editName" name="surname"
                      value="<?= $userDetail['surname'] ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="editPhone" class="form-label">Phone no*</label>
                                            <input type="text" class="form-control" id="editPhone" name="phone"
                      value="<?= $userDetail['phone'] ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="editName" class="form-label">Email*</label>
                                            <input type="email" class="form-control" id="email" name="email"
                      value="<?= $userDetail['email'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <h2> Address</h2>
                                        <label for="editName" class="form-label">Country*</label>
                                        <input type="text" class="form-control" id="country" name="country"
                      value="<?= $userDetail['country'] ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="editPhone" class="form-label">Postal Code*</label>
                                            <input type="text" class="form-control" id="postalCode" name="postalCode"
                      value="<?= $userDetail['postal_code'] ?>">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="editPhone" class="form-label">House no*</label>
                                            <input type="text" class="form-control" id="houseNo" name="houseNo"
                      value="<?= $userDetail['house_no'] ?>">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="editPhone" class="form-label">Landmark</label>
                                            <input type="text" class="form-control" value="<?= $userDetail['addition'] ?>" id="addition"
                      name="addition" placeholder="Enter Landmark">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">

                                        <label for="editName" class="form-label">Street Name & Number*</label>
                                        <input type="text" class="form-control" id="email" value="<?= $userDetail['street_name'] ?>"
                      name="streetName">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="editName" class="form-label">Town/City*</label>
                                        <input type="text" class="form-control" id="placeName" value="<?= $userDetail['place_name'] ?>"
                      name="placeName">

                                    </div>
                                    <button type="submit" name="update" class="btn_hover_color me-2" id="saveProfileBtn">Update
                                        Profile</button>
                                    <a href="abayalooks-dashboard.php">
                                            <button type="button" class="btn_hover_color" id="cancelEditBtn">Cancel</button>
                                    </a>
                            </form>
                        </div>

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
      $('#updateprofile').validate({
        rules: {
          firstName: {
            required: true,
            maxlength: 50
          },
          surname: {
            required: true,
            maxlength: 50
          },
          phone: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
          },
          email: {
            required: true,
            email: true,
            maxlength: 100
          },
          country: {
            required: true
          },
          postalCode: {
            required: true,
            maxlength: 30
          },
          houseNo: {
            required: true,
            maxlength: 50
          },
          addition: {
            maxlength: 200
          },
          streetName: {
            required: true,
            maxlength: 100
          },
          placeName: {
            required: true,
            maxlength: 100
          }
        },
        messages: {
          firstName: {
            required: "Please enter your first name",
            maxlength: "First Name cannot be more than 50 characters"
          },
          surname: {
            required: "Please enter your surname",
            maxlength: "Surname cannot be more than 50 characters"
          },
          phone: {
            required: "Please enter your phone number",
            digits: "Please enter only digits",
            minlength: "Please enter exactly 10 digits",
            maxlength: "Please enter exactly 10 digits"
          },
          email: {
            required: "Please enter your email",
            email: "Please enter a valid email address",
            maxlength: "Email cannot be more than 100 characters"
          },
          country: {
            required: "Please select your country"
          },
          postalCode: {
            required: "Please enter your postal code",
            maxlength: "Postal Code cannot be more than 30 characters"
          },
          houseNo: {
            required: "Please enter your house number",
            maxlength: "House Number cannot be more than 50 characters"
          },
          addition: {
            maxlength: "Landmark cannot be more than 200 characters"
          },
          streetName: {
            required: "Please enter your street name",
            maxlength: "Street Name cannot be more than 100 characters"
          },
          placeName: {
            required: "Please enter your city or town",
            maxlength: "City/Town cannot be more than 100 characters"
          }
        }
      });
    });
  </script>


</html>