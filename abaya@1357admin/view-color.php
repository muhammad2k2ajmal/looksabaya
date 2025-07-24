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

// Update status
if (isset($_REQUEST['status'])) {
    $id = $_REQUEST['id'];
    $status = $_REQUEST['status'];

    $sqlStatus = $db->execute("UPDATE `color` SET `status` = '$status' WHERE `id` = '$id'");

    if ($sqlStatus === true) {
        $_SESSION['msg'] = 'Status has been changed successfully.';
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred. Try again.';
    }

    header("Location: view-color.php");
    exit;
}

// Delete color
if (isset($_REQUEST['delete']) && $_REQUEST['delete'] == 'y') {
    $id = $_REQUEST['id'];

    $sqlDelete = $db->execute("DELETE FROM `color` WHERE `id` = '$id'");

    if ($sqlDelete === true) {
        $_SESSION['msg'] = 'Color successfully deleted.';
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
    <link href="libs/simple-datatables/style.css" rel="stylesheet" type="text/css" />
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">View Colors</h4>
                                </div>
                                <div class="col-auto">
                                    <form class="row g-2">
                                        <div class="col-auto">
                                            <a href="add-color.php" class="btn btn-primary login-btn">
                                                <i class="fa-solid fa-plus me-1"></i> Add Color
                                            </a>
                                        </div>
                                    </form>
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
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $colorQuery = $color->allColor();
                                        foreach ($colorQuery as $row) :
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['updated_at'])); ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 1) : ?>
                                                        <a href="?status=0&id=<?php echo $row['id']; ?>">
                                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                                        </a>
                                                    <?php else : ?>
                                                        <a href="?status=1&id=<?php echo $row['id']; ?>">
                                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <a href="add-color.php?id=<?php echo base64_encode($row['id']); ?>">
                                                        <i class="las la-pen text-warning fs-18"></i>
                                                    </a>
                                                    <a href="?id=<?php echo $row['id']; ?>&delete=y"
                                                       onclick="return confirm('Are you sure? Record will be deleted permanently.')">
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
</html>