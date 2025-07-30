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
$category = new Categories();
$banners = $common->getAllBanners();
$testimonials = $common->getAllTestimonials();
$newProducts = $common->getAllNewProduct();
$bestSellingProducts = $common->getAllBestSellingProduct();
$trendingProducts = $common->getAllTrendingProduct();
$categories = $common->getAllCategoriesWithProducts();
// var_dump($subCategories);
$Policy=$common->getPolicy('refund');

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
    <link rel="stylesheet" href="../../ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="icon" href="images/favicon.png" type="image/x-icon"> -->
    <!-- <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />


</head>

<body>
    <div id="pageWrapper">
        <?php include 'include/header.php'; ?>
        <div class="container">
            <div class="policy_box">
               <?php echo $Policy['description']; ?>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>

</body>

<script src="js/jquery.min.js" defer=""></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>

</html>