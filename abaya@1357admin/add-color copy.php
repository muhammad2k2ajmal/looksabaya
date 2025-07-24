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
$color = new Color();

$auth->checkSession();

$id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
$editval = $color->getColor($id);

// Insert record
if (isset($_REQUEST['submit'])) {
    $name = $db->addStr($_POST['name'] ?? '');
    $color_code = $db->addStr($_POST['color_code'] ?? '');
    $status = $db->addStr($_POST['status'] ?? '1');

    $result = $color->addColor($name, $color_code, $status);

    if ($result === true) {
        $_SESSION['msg'] = 'Color has been created successfully.';
        header("Location: view-color.php");
        exit;
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred.';
        header("Location: add-color.php");
        exit;
    }
}

// Update record
if (isset($_REQUEST['update'])) {
    $id = $_REQUEST['eId'];
    $name = $db->addStr($_POST['name'] ?? '');
    $color_code = $db->addStr($_POST['color_code'] ?? '');
    $status = $db->addStr($_POST['status'] ?? '1');

    $result = $color->updateColor($id, $name, $color_code, $status);

    if ($result === true) {
        $_SESSION['msg'] = 'Color has been updated successfully.';
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred. Try again.';
    }

    header("Location: view-color.php");
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
    <link rel="icon" type="image/x-icon" sizes="20x20" href="../assets/img/logo.png">
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
                                        <h4 class="card-title">Add Color</h4>
                                    <?php else: ?>
                                        <h4 class="card-title">Update Color</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($id)): ?>
                                <form id="color-form1" method="post" class="row g-3 needs-validation" novalidate>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="color_code">Color Code (Hex)</label>
                                            <input name="color_code" type="text" class="form-control" id="color_code" value="" placeholder="#FFFFFF" required pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="status">Status</label>
                                            <select name="status" class="form-select" id="status" required>
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
                                <form id="color-form2" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="eId" value="<?php echo $id; ?>">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($editval['name'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="color_code">Color Code (Hex)</label>
                                            <input name="color_code" type="text" class="form-control" id="color_code" value="<?php echo htmlspecialchars($editval['color_code'] ?? ''); ?>" required pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="status">Status</label>
                                            <select name="status" class="form-select" id="status" required>
                                                <option value="">Select Status</option>
                                                <option value="1" <?php if ($editval['status'] == "1") echo 'selected'; ?>>Active</option>
                                                <option value="0" <?php if ($editval['status'] == "0") echo 'selected'; ?>>Inactive</option>
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
        </div>
        <?php include 'include/footer.php'; ?>
    </div>
</div>

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
        $("#color-form1, #color-form2").validate({
            rules: {
                name: {
                    required: true
                },
                color_code: {
                    required: true,
                    pattern: /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter a color name."
                },
                color_code: {
                    required: "Please enter a color code.",
                    pattern: "Please enter a valid hex color code (e.g., #FFFFFF or #FFF)."
                },
                status: {
                    required: "Please select a status."
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

        $("select, input").change(function () {
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