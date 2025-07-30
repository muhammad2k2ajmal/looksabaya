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
                                    <li class="breadcrumb-item">
                                        <a href="looksabaya-contact.php" class="text-decoration-none">Faq</a>
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="collapsiblesBlock w-100 position-relative overflow-hidden pt-6 pb-8 pb-lg-11 pb-xl-14">
                <div class="container">
                    <header class="headingHead text-center mb-8 mb-xl-12">
                        <h1 class="hhHeading HDii fw-normal">FAQ</h1>
                    </header>
                    <div class="row justify-content-center">

                        <div class="col-12 col-md-8 mt-n3">
                            <div class="accordion accordion-flush faqAccordion" id="shoppingAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                            aria-expanded="true" aria-controls="flush-collapseOne">What Shipping Methods
                                            Are Available?</button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#shoppingAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Oneself endless holiest society philosophy dept valuation Contradicts
                                                gains nobless end lose preju dice battle hope the battle philosophy
                                                Gains endless capitalize on low hanging fruit to identify a ballpark
                                                value added activity to beta test. Override the digital divide with
                                                additional clickthroughs from DevOps</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                            aria-expanded="false" aria-controls="flush-collapseTwo">Do You Ship
                                            Internationally?</button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#shoppingAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Oneself endless holiest society philosophy dept valuation Contradicts
                                                gains nobless end lose preju dice battle hope the battle philosophy
                                                Gains endless capitalize on low hanging fruit to identify a ballpark
                                                value added activity to beta test. Override the digital divide with
                                                additional clickthroughs from DevOps</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                            aria-expanded="false" aria-controls="flush-collapseThree">How long does it
                                            take for home delivery?</button>
                                    </h2>
                                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#shoppingAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Oneself endless holiest society philosophy dept valuation Contradicts
                                                gains nobless end lose preju dice battle hope the battle philosophy
                                                Gains endless capitalize on low hanging fruit to identify a ballpark
                                                value added activity to beta test. Override the digital divide with
                                                additional clickthroughs from DevOps</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
                                            aria-expanded="false" aria-controls="flush-collapseFour">How Long Will It
                                            Take To Get My Package?</button>
                                    </h2>
                                    <div id="flush-collapseFour" class="accordion-collapse collapse"
                                        data-bs-parent="#shoppingAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Oneself endless holiest society philosophy dept valuation Contradicts
                                                gains nobless end lose preju dice battle hope the battle philosophy
                                                Gains endless capitalize on low hanging fruit to identify a ballpark
                                                value added activity to beta test. Override the digital divide with
                                                additional clickthroughs from DevOps</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-7 my-md-9 my-xl-12">
                    <div class="row justify-content-center">

                        <div class="col-12 col-md-8 mt-n3">
                            <div class="accordion accordion-flush faqAccordion" id="paymentAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#payment-flush-collapseOne"
                                            aria-expanded="true" aria-controls="payment-flush-collapseOne">What Payment
                                            Methods Are Accepted?</button>
                                    </h2>
                                    <div id="payment-flush-collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#paymentAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo
                                                single-origin coffee Wes Anderson. Flexitarian Pitchfork forage,
                                                literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack
                                                meggings.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#payment-flush-collapseTwo"
                                            aria-expanded="false" aria-controls="payment-flush-collapseTwo">How Do I
                                            Track My Order?</button>
                                    </h2>
                                    <div id="payment-flush-collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#paymentAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo
                                                single-origin coffee Wes Anderson. Flexitarian Pitchfork forage,
                                                literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack
                                                meggings.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#payment-flush-collapseThree"
                                            aria-expanded="false" aria-controls="payment-flush-collapseThree">Can I use
                                            a different payment method?</button>
                                    </h2>
                                    <div id="payment-flush-collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#paymentAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo
                                                single-origin coffee Wes Anderson. Flexitarian Pitchfork forage,
                                                literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack
                                                meggings.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-7 my-md-9 my-xl-12">
                    <div class="row justify-content-center">

                        <div class="col-12 col-md-8 mt-n3">
                            <div class="accordion accordion-flush faqAccordion" id="ordersAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#orders-flush-collapseOne"
                                            aria-expanded="true" aria-controls="orders-flush-collapseOne">How do I place
                                            an Order?</button>
                                    </h2>
                                    <div id="orders-flush-collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#ordersAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd
                                                Future seitan normcore. Master cleanse American Apparel gentrify
                                                flexitarian beard slow-carb next level. Raw denim polaroid paleo
                                                farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest
                                                letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz
                                                photo booth master cleanse bespoke hashtag chillwave gentrify.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#orders-flush-collapseTwo"
                                            aria-expanded="false" aria-controls="orders-flush-collapseTwo">How Can I
                                            Cancel Or Change My Order?</button>
                                    </h2>
                                    <div id="orders-flush-collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#ordersAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd
                                                Future seitan normcore. Master cleanse American Apparel gentrify
                                                flexitarian beard slow-carb next level. Raw denim polaroid paleo
                                                farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest
                                                letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz
                                                photo booth master cleanse bespoke hashtag chillwave gentrify.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#orders-flush-collapseThree"
                                            aria-expanded="false" aria-controls="orders-flush-collapseThree">Do I need
                                            an account to place an order?</button>
                                    </h2>
                                    <div id="orders-flush-collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#ordersAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd
                                                Future seitan normcore. Master cleanse American Apparel gentrify
                                                flexitarian beard slow-carb next level. Raw denim polaroid paleo
                                                farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest
                                                letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz
                                                photo booth master cleanse bespoke hashtag chillwave gentrify.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button border-0 px-0 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#orders-flush-collapseFour"
                                            aria-expanded="false" aria-controls="orders-flush-collapseFour">How Can I
                                            Return a Product?</button>
                                    </h2>
                                    <div id="orders-flush-collapseFour" class="accordion-collapse collapse"
                                        data-bs-parent="#ordersAccordion">
                                        <div class="accordion-body p-0">
                                            <p>Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd
                                                Future seitan normcore. Master cleanse American Apparel gentrify
                                                flexitarian beard slow-carb next level. Raw denim polaroid paleo
                                                farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest
                                                letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz
                                                photo booth master cleanse bespoke hashtag chillwave gentrify.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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