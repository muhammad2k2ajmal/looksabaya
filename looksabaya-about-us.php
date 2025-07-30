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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abayalooks</title>
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
                                        <a href="index.html" class="text-decoration-none">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="looksabaya-about-us.html" class="text-decoration-none">About Us</a>
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <article class="visualBlock position-relative w-100 overflow-hidden py-6">
                <div class="container">
                    <header class="headingHead text-center">
                        <h1 class="hhHeading HDii fw-normal">About Our Online Store</h1>

                    </header>

                </div>
            </article>
            <article class="richtextBlock position-relative w-100 overflow-hidden pb-6 pb-md-9 pb-lg-12">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-5">
                            <img src="images/WhatsApp Image 2025-07-18 at 7.32.28 AM-Photoroom.png" alt=""
                                class="img-fluid">
                        </div>
                        <div class="col-12 col-md-6">
                            <p>Ready to start your own business with your own brand with abayas that your customers
                                already love?
                                Join our exclusive Abayalooks Reseller Club and bring premium abayas to women in your
                                city.</p>
                            <div>
                                <h2> Why Partner With Us?</h2>
                                <ul style="list-style-type: none; padding-left: 20px; line-height: 1.8;">
                                    <li>• Create your own brand and earn huge profits</li>
                                    <li>• Stunning new collections released regularly</li>
                                    <li>• Full marketing and training support</li>
                                    <li>• Fast shipping with trusted quality</li>
                                </ul>

                            </div>
                            <p> Whether you own a boutique or sell from home, <strong>Abayalooks</strong> makes it easy
                                to grow your business with confidence.</p>

                            <div class="mt-5 pt-5">
                                <a class="club__button button button--secondary" href="join-as-a-reseller-now.html">Join
                                    as a Reseller Now</a>
                            </div>


                        </div>
                    </div>
                </div>
            </article>


            <div class="container">
                <hr class="my-0">
            </div>
            <section class="position-relative w-100 overflow-hidden pt-20 pb-20">
                <div class="container">

                    <div class="slidersColsHolder">
                        <div class="reviewsSlider">
                            <?php foreach($testimonials as $testimonialRow):?>
                                <div>
                                    <div class="schCol">
                                        <blockquote class="quoteColumn overflow-hidden bg-white p-8">
                                            <i
                                                class="qcIcn icomoon-quotes bg-black text-white rounded-circle d-flex align-items-center justify-content-center mb-4"><span
                                                    class="visually-hidden">"</span></i>
                                            <h3 class="qcHeading fw-medium mb-2"><?= $testimonialRow['heading']??''?></h3>
                                            <p><?= $testimonialRow['testimonial']??''?></p>
                                            <div class="d-flex gap_1 mt-5">
                                                <cite class="flex-grow-1 qcCite fw-normal"><?= $testimonialRow['name']??''?></cite>
                                                <ul class="list-unstyled ratingStaticList d-flex mb-0">
                                                    <?php for($i=1;$i<=$testimonialRow['rating'];$i++):?>
                                                    <li><i class="icomoon-star"><span class="visually-hidden">rated star
                                                                1</span></i></li>
                                                    <?php endfor;
                                                    $dullstars=5-$testimonialRow['rating'];
                                                    for($i=1;$i<=$dullstars;$i++):
                                                        
                                                    ?>

                                                        <li><i class="icomoon-star dull"><span class="visually-hidden">rated
                                                                    star 5</span></i></li>
                                                    <?php endfor;?>
                                                </ul>
                                            </div>
                                        </blockquote>
                                    </div>
                                </div>
							<?php endforeach;?>
                        </div>
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