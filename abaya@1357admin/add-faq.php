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
$admin = new Faqs();

$auth->checkSession();

if (isset($_REQUEST['id'])) {
    $id = base64_decode($_REQUEST['id']);
    $editval = $admin->getFaqs($id);
}

// Insert record query
if (isset($_REQUEST['submit'])) {
    $question = $db->addStr($_POST['question']);
    $answer = $db->addStr($_POST['answer']);
    $status = $db->addStr($_POST['status']);

    $checkFaq = $admin->checkFaqs($question, 'faq');

    if ($checkFaq == 0) {
      $result = $admin->addFaqs($question, $answer, $status);

      if ($result === true) {
        $_SESSION['msg'] = 'FAQ has been created successfully ..!!';
        header("Location: view-faq.php");
        exit;
      } else {
        $_SESSION['errmsg'] = "Sorry !! Some Error Occurred .. Try Again";
        header("Location: add-faq.php");
        exit;
      }
    } else {
      $_SESSION['errmsg'] = "Sorry !! FAQ Already Exists .. !!";
      header("Location: add-faq.php");
      exit;
    }
}

// Update record query
if (isset($_REQUEST['update'])) {
    $question = $db->addStr($_POST['question']);
    $answer = $db->addStr($_POST['answer']);
    $status = $db->addStr($_POST['status']);

    $result = $admin->updateFaqs($question, $answer, $status, $id);

    if ($result === true) {
      $_SESSION['msg'] = "FAQ has been updated successfully ..!!";
    } else {
      $_SESSION['errmsg'] = "Sorry !! Some Error Occurred .. Try Again";
    }

    header("Location: view-faq.php");
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
                                                <h4 class="card-title">Add FAQ</h4>
                                            <?php else: ?>
                                                <h4 class="card-title">Update FAQ</h4>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($id)): ?>
                                        <form id="faq-form" method="post" class="row g-3 needs-validation" novalidate>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom01">Question</label>
                                                    <input name="question" type="text" class="form-control" placeholder="Enter Question" id="validationCustom01" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom03">Answer</label>
                                                    <textarea name="answer" class="form-control" placeholder="Enter Answer" id="validationCustom03" rows="4" required></textarea>
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
                                        <form id="faq-form" method="post" class="row g-3 needs-validation" novalidate>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom01">Question</label>
                                                    <input name="question" type="text" class="form-control" placeholder="Enter Question" id="validationCustom01" value="<?php echo $editval['question']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom03">Answer</label>
                                                    <textarea name="answer" class="form-control" placeholder="Enter Answer" id="validationCustom03" rows="4" required><?php echo $editval['answer']; ?></textarea>
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
            $(document).ready(function () {
                $("#faq-form").validate({
                    rules: {
                        question: {
                            required: true,
                            maxlength: 200
                        },
                        answer: {
                            required: true,
                            maxlength: 1000
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        question: {
                            required: "Please enter question.",
                            maxlength: "Question cannot be more than 200 characters."
                        },
                        answer: {
                            required: "Please enter answer.",
                            maxlength: "Answer cannot be more than 1000 characters."
                        },
                        status: {
                            required: "Please select status."
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