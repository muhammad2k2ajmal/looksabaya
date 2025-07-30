<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$category=isset($_GET['cid'])?base64_decode($_GET['cid']):'';
$new_arrivals=isset($_GET['new'])?base64_decode($_GET['new']):'';
$searchQuery = $_GET['searchQuery'] ?? '';

// var_dump($searchQuery);

$conn = new dbClass();
$common = new CommProducts();
if($category){

    $products = $common->getAllProductsByCategory($category);
}else if($new_arrivals){
    $products = $common->getAllNewProduct();
}else if($searchQuery){
    $products = $common->getAllProductsBySearchQuery($searchQuery);
}
// $banners = $common->getAllBanners();
// $testimonials = $common->getAllTestimonials();
// $newProducts = $common->getAllNewProduct();
// $bestSellingProducts = $common->getAllBestSellingProduct();
// $trendingProducts = $common->getAllTrendingProduct();
// $categories=$common->getAllCategoriesWithProducts();

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

        <main>
            <header class="d-flex text-center breadCrumbHeader">
                <div class="alignHolder w-100 d-flex">
                    <div class="align py-2 w-100">
                        <div class="container">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php" class="text-decoration-none">Home</a>
                                    </li>

                                    <li class="breadcrumb-item active" aria-current="page">Products </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="collectionBlock position-relative w-100 overflow-hidden py-6">
                <div class="container">

                    <div class="row">
                        <?php foreach($products as $productRow):?>
                            <div class="col-lg-3 mb-5">
                                <article
                                    class="productColumn text-center text-decoration-none position-relative d-block overflow-hidden">
                                    <div class="imgHolder mb-2">
                                        <a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>">
                                            <img src="adminUploads/products/<?= $productRow['image'];?>" class="w-100 img-fluid"
                                                alt="image description">
                                        </a>

                                    </div>

                                    <h3 class="fw-light pcHeading mb-1">
                                        <a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>"
                                            class="text-decoration-none">
                                            <?= $productRow['name'];?>
                                        </a>
                                    </h3>
                                    <h4 class="fw-normal  mb-0">
                                        <span class="regPrice">Rs. <?= number_format($productRow['price'],2);?></span>
                                    </h4>
                                    <button class=" position-absolute fw-medium p-0 border-0">ADD TO
                                        CART</button>
                                </article>
                            </div>
                        <?php endforeach;?>
                       
                    </div>
                </div>
            </section>

        </main>
        <?php include 'include/footer.php'; ?>

    </div>
</body>

<script src="js/jquery.min.js" defer=""></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>
<!-- Script -->



</html>