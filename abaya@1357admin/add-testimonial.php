<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';
require 'functions/image-resize.php';
require 'functions/image-resize2.php';
require 'functions/operations.php';

$db = new dbClass();
$auth = new Authentication();
$admin = new Testimonials();

$auth->checkSession();

if (isset($_REQUEST['id'])) {
    $id = base64_decode($_REQUEST['id']);
    $editval = $admin->getTestimonials($id);
}

// Insert record query
if (isset($_REQUEST['submit'])) {
    $name = $db->addStr($_POST['name']);
    $testimonial = $db->addStr($_POST['testimonial']);
    $heading = $db->addStr($_POST['heading']); // Added heading
    $status = $db->addStr($_POST['status']);
    $rating = $db->addStr($_POST['rating']);


    $checkTestimonial = $admin->checkTestimonials($name, 'testimonial');

    if ($checkTestimonial == 0) {
      $result = $admin->addTestimonials($name, $testimonial,  $status, $rating, $heading);

      if ($result === true) {
        $_SESSION['msg'] = 'Testimonial has been created successfully ..!!';
        header("Location: view-testimonial.php");
        exit;
      } else {
        $_SESSION['errmsg'] = "Sorry !! Some Error Occurred .. Try Again";
        header("Location: add-testimonial.php");
        exit;
      }
    } else {
      $_SESSION['errmsg'] = "Sorry !! Testimonial Already Exists .. !!";
      header("Location: add-testimonial.php");
      exit;
    }
}

// Update record query
if (isset($_REQUEST['update'])) {
    $name = $db->addStr($_POST['name']);
    $testimonial = $db->addStr($_POST['testimonial']);
    $heading = $db->addStr($_POST['heading']); // Added heading
    $status = $db->addStr($_POST['status']);
    $rating = $db->addStr($_POST['rating']);

    

    $result = $admin->updateTestimonials($name, $testimonial, $status, $rating, $heading, $id);

    if ($result === true) {
      $_SESSION['msg'] = "Testimonial has been updated successfully ..!!";
    } else {
      $_SESSION['errmsg'] = "Sorry !! Some Error Occurred .. Try Again";
    }

    header("Location: view-testimonial.php");
    exit;
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

    <body>
        <?php include 'include/header.php'; ?>
        <?php include 'include/sidebar.php'; ?>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="container-xxl">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header p-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <?php if (empty($id)): ?>
                                                <h4 class="card-title">Add Testimonial</h4>
                                            <?php else: ?>
                                                <h4 class="card-title">Update Testimonial</h4>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($id)): ?>
                                        <form id="testimonial-form" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom01">Name</label>
                                                    <input name="name" type="text" class="form-control" placeholder="Enter Name" id="validationCustom01" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom05">Heading</label>
                                                    <input name="heading" type="text" class="form-control" placeholder="Enter Heading" id="validationCustom05" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom03">Testimonial</label>
                                                    <textarea name="testimonial" class="form-control" placeholder="Enter Testimonial" id="validationCustom03" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom04">Rating</label>
                                                    <select name="rating" class="form-select" id="validationCustom04" required>
                                                        <option value="">Select Rating</option>
                                                        <option value="1">1 Star</option>
                                                        <option value="2">2 Stars</option>
                                                        <option value="3">3 Stars</option>
                                                        <option value="4">4 Stars</option>
                                                        <option value="5">5 Stars</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom02">Status</label>
                                                    <select name="status" class="form-select" id="validationCustom02" required>
                                                        <option selected value="">Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 m-0">
                                                <input type="submit" class="btn btn-primary login-btn" name="submit" value="Submit">
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <form id="testimonial-form" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom01">Name</label>
                                                    <input name="name" type="text" class="form-control" placeholder="Enter Name" id="validationCustom01" value="<?php echo $editval['name']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom05">Heading</label>
                                                    <input name="heading" type="text" class="form-control" placeholder="Enter Heading" id="validationCustom05" value="<?php echo $editval['heading']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom03">Testimonial</label>
                                                    <textarea name="testimonial" class="form-control" placeholder="Enter Testimonial" id="validationCustom03" rows="4" required><?php echo $editval['testimonial']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom04">Rating</label>
                                                    <select name="rating" class="form-select" id="validationCustom04" required>
                                                        <option value="">Select Rating</option>
                                                        <option value="1" <?php if (isset($editval['rating']) && $editval['rating'] == "1") echo 'selected'; ?>>1 Star</option>
                                                        <option value="2" <?php if (isset($editval['rating']) && $editval['rating'] == "2") echo 'selected'; ?>>2 Stars</option>
                                                        <option value="3" <?php if (isset($editval['rating']) && $editval['rating'] == "3") echo 'selected'; ?>>3 Stars</option>
                                                        <option value="4" <?php if (isset($editval['rating']) && $editval['rating'] == "4") echo 'selected'; ?>>4 Stars</option>
                                                        <option value="5" <?php if (isset($editval['rating']) && $editval['rating'] == "5") echo 'selected'; ?>>5 Stars</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom02">Status</label>
                                                    <select name="status" class="form-select" id="validationCustom02" required>
                                                        <option value="">Select Status</option>
                                                        <option value="1" <?php if ($editval['status'] == "1") echo 'selected' ?>>Active</option>
                                                        <option value="0" <?php if ($editval['status'] == "0") echo 'selected' ?>>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 m-0">
                                                <input type="submit" class="btn btn-primary login-btn" name="update" value="Update">
                                            </div>
                                        </form>
                                    <?php endif; ?>
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

        <!-- Javascript -->
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
            function PreviewImage() 
            {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("image").files[0]);
                oFReader.onload = function(oFREvent) {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                };
            };    
        </script>

        <script>
            $(document).ready(function () {
                $("#testimonial-form").validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 200
                        },
                        heading: { // Added validation for heading
                            required: true,
                            maxlength: 200
                        },
                        testimonial: {
                            required: true,
                            maxlength: 1000
                        },
                        status: {
                            required: true
                        },
                        rating: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter name.",
                            maxlength: "Name cannot be more than 200 characters."
                        },
                        heading: { // Added error messages for heading
                            required: "Please enter heading.",
                            maxlength: "Heading cannot be more than 200 characters."
                        },
                        testimonial: {
                            required: "Please enter testimonial text.",
                            maxlength: "Testimonial cannot be more than 1000 characters."
                        },
                        status: {
                            required: "Please select status."
                        },
                        rating: {
                            required: "Please select a rating."
                        }
                    },
                    errorClass: "is-invalid",
                    validClass: "is-valid",
                    highlight: function (element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function (error, element) {
                        $(element).siblings(".invalid-feedback").remove();
                        $(element).after(error);
                        $(error).css('color', 'red');
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

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
</html>