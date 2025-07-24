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
$products = new Products();

$auth->checkSession();

// Update status
if (isset($_REQUEST['status'])) {
    $id = $_REQUEST['id'];
    $status = $_REQUEST['status'];
    $sqlStatus = $db->execute("UPDATE `product` SET `status` = '$status' WHERE `product_id` = '$id'");
    if ($sqlStatus) {
        $_SESSION['msg'] = 'Status has been changed successfully.';
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred.';
    }
    header("Location: view-products.php");
    exit;
}

// Delete product
if (isset($_REQUEST['delete']) && $_REQUEST['delete'] == 'y') {
    $did = $_REQUEST['id'];
    $product_Delete = $db->getData("SELECT * FROM `product` WHERE `product_id` = '$did'");
    if ($product_Delete) {
        $productImages = $db->getAllData("SELECT image FROM `product_images` WHERE `product_id` = '$did'");
        foreach ($productImages as $image) {
            if ($image['image']) {
                unlink("../adminUploads/products/" . $image['image']);
            }
        }
        $sqlDeleteImages = $db->execute("DELETE FROM `product_images` WHERE `product_id` = '$did'");
        $sqlDeleteSizes = $db->execute("DELETE FROM `product_sizes` WHERE `product_id` = '$did'");
        $sqlDeleteLists = $db->execute("DELETE FROM `product_lists` WHERE `product_id` = '$did'");
        $sqlDeleteColors = $db->execute("DELETE FROM `product_colors` WHERE `product_id` = '$did'");
        $sqlDeleteProduct = $db->execute("DELETE FROM `product` WHERE `product_id` = '$did'");
        if ($sqlDeleteProduct || $sqlDeleteImages) {
            $_SESSION['msg'] = 'Product and associated data successfully deleted.';
        } else {
            $_SESSION['errmsg'] = 'Sorry, some error occurred.';
        }
    } else {
        $_SESSION['errmsg'] = 'Product not found.';
    }
    header("Location: view-products.php");
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
                                    <h4 class="card-title">View Products</h4>
                                </div>
                                <div class="col-auto">
                                    <a href="add-products.php" class="btn btn-primary login-btn">
                                        <i class="fa-solid fa-plus me-1"></i> Add Product
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table mb-0" id="datatable_1">
                                    <thead class="table-light">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Category</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Stock</th>
                                            <th>Sizes</th>
                                            <th>Colors</th>
                                            <th>Trending</th>
                                            <th>New Arrivals</th>
                                            <th>Best Selling</th>
                                            <th>Created At</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $productQuery = $products->allProducts();
                                        foreach ($productQuery as $row):
                                            $category = $db->getData("SELECT name FROM category WHERE id = '" . $row['category_id'] . "'");
                                            $colors = $db->getAllData("SELECT c.name, c.color_code FROM product_colors pc JOIN color c ON pc.color_id = c.id WHERE pc.product_id = '" . $row['product_id'] . "'");
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo htmlspecialchars($category['name'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                                <td><?php echo htmlspecialchars($row['discount']); ?>%</td>
                                                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                                <td><?php echo implode(', ', $row['sizes']); ?></td>
                                                <td>
                                                    <?php foreach ($colors as $color): ?>
                                                        <span>
                                                            <?php echo htmlspecialchars($color['name']); ?>
                                                            <span style="background-color: <?php echo htmlspecialchars($color['color_code']); ?>; width: 20px; height: 20px; display: inline-block; vertical-align: middle; border: 1px solid #ccc;"></span>
                                                        </span><br>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td><?php echo $row['trending'] ? 'Yes' : 'No'; ?></td>
                                                <td><?php echo $row['new_arrivals'] ? 'Yes' : 'No'; ?></td>
                                                <td><?php echo $row['best_selling'] ? 'Yes' : 'No'; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 1): ?>
                                                        <a href="?status=0&id=<?php echo $row['product_id']; ?>">
                                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?status=1&id=<?php echo $row['product_id']; ?>">
                                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <a href="add-products.php?id=<?php echo base64_encode($row['product_id']); ?>">
                                                        <i class="las la-pen text-warning fs-18"></i>
                                                    </a>
                                                    <a href="?id=<?php echo $row['product_id']; ?>&delete=y" onclick="return confirm('Are you sure? Record will be deleted permanently.')">
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