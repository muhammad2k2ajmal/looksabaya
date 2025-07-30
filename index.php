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
$category=new Categories();
$banners = $common->getAllBanners();
$testimonials = $common->getAllTestimonials();
$newProducts = $common->getAllNewProduct();
$videoProducts = $common->getAllVideoProduct();
$bestSellingProducts = $common->getAllBestSellingProduct();
$trendingProducts = $common->getAllTrendingProduct();
$categories=$common->getAllCategoriesWithProducts();
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
		<?php include 'include/header.php';?>
		<main>
			<section class="introBlock position-relative overflow-hidden w-100">
				<div class="ibSlider">
					<?php foreach ($banners as $bannerRow): ?>

						<div>
							<article class="ibsColumn w-100 d-flex overflow-hidden position-relative">
								<div class="d-flex alignHolder w-100">
									<div class="ahAlign w-100 py-7 my-auto px-sm-10 px-lg-20">
										<div class="container">
											<div class="ibsAnim ibsAnim1">
												<h1><?= $bannerRow['heading']??''; ?></h1>
											</div>
											<div class="ibsAnim ibsAnim2">
												<p><?= $bannerRow['subheading']; ?></p>
											</div>
											<div class="ibsAnim ibsAnim3">
												<a href="<?= $bannerRow['button_link']; ?>" class="btn btnThemeOutlined" data-hover="Shop Now">
													<span class="btnText">Shop Now</span>
												</a>
											</div>
										</div>
									</div>
								</div>
								<span class="position-absolute w-100 h-100 bgCover ibBgImage"
									style="background-image: url('adminuploads/banner/<?= $bannerRow['image']; ?>');"></span>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
			<aside class="position-relative bannersAsideBlock overflow-hidden w-100 py-6">
				<div class="container">
					<div class="row justify-content-center ">
						<?php
						
						foreach ($categories as $categorieRow): ?>
							<div class="col-xl-4 col-md-6 col-sm-12 mb-5">
								<a href="looksabaya-products.php?cid=<?= base64_encode($categorieRow['id'])?>">
									<img src="adminuploads/products/<?= $categorieRow['image']; ?>" alt="" class="img-fluid">
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</aside>


<!-- best selling products -->
			<section class="collectionBlock position-relative w-100 overflow-hidden pb-6">
				<div class="container">
					<header class="headingHead text-center mb-5 mt-1">
						<h2
							class="position-relative fw-normal hhHeading patternActive d-flex justify-content-center align-items-center gap-4 mb-2">
							Best Selling Abayas</h2>
					</header>
					<div class="slidersColsHolder">
						<div class="cbSlider">
							<?php foreach($bestSellingProducts as $productRow):?>
								<div class="schCol">
									<article
										class="productColumn text-center text-decoration-none position-relative d-block overflow-hidden">
										<div class="imgHolder mb-2">
											<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>">
												<img src="adminUploads/products/<?= $productRow['image'];?>" class="w-100 img-fluid"
													alt="image description">
											</a>

										</div>

										<h3 class="fw-light pcHeading mb-1">
											<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>" class="text-decoration-none">
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
				</div>
			</section>
			<!-- new arrivals -->
			<section class="club collectionBlock position-relative w-100 overflow-hidden py-6">
				<div class="container">
					<header class="headingHead text-center mb-5 mt-1">
						<h2
							class="position-relative fw-normal hhHeading patternActive d-flex justify-content-center align-items-center gap-4 mb-2">
							New Arrivals</h2>
					</header>
					<div class="slidersColsHolder">
						<div class="cbSlider">
							<?php foreach($newProducts as $productRow):?>
								<div class="schCol">
									<article
										class="productColumn text-center text-decoration-none position-relative d-block overflow-hidden">
										<div class="imgHolder mb-2">
											<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>">
												<img src="adminUploads/products/<?= $productRow['image'];?>" class="w-100 img-fluid"
													alt="image description">
											</a>

										</div>

										<h3 class="fw-light pcHeading mb-1">
											<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>" class="text-decoration-none">
												<?= $productRow['name']??'';?>
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
				</div>
			</section>

			<!-- trending -->
			<section class="gallery-heade py-6">
				<header class="headingHead text-center  mt-1">

					<h2
						class="position-relative fw-normal hhHeading patternActive d-flex justify-content-center align-items-center gap-4 mb-1">
						Trending Abayas
					</h2>
				</header>
				<div class="gallery-grid">
					<?php foreach($trendingProducts as $productRow):?>
						<div class="gallery-item">
							<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>">

								<img src="adminUploads/products/<?= $productRow['image'];?>" alt="Occasion Abayas">
							</a>
							<h5 class="fw-light pcHeading mt-2">
								<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>" class="text-decoration-none"><?= $productRow['name'];?></a>
							</h5>
							<h6 class="fw-normal  mb-2">
								<span class="regPrice">Rs. <?= number_format($productRow['price'],2);?></span>
							</h6>

						</div>
					<?php endforeach;?>
				</div>
			</section>


			<!-- remains static -->
			<section class="club-1 section ">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6">
							<img src="images/about-png.png" alt="" width="100%">
						</div>
						<div class="col-lg-6 py_50">
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

							<div class=" mt-5 pt-5">
								<a class="club__button button button--secondary" href="join-as-a-reseller-now.html">Join as a Reseller Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>



			<section class="collectionBlock position-relative w-100 overflow-hidden pt-20 pb-20">
				<div class="container-flauid ">

					<div class="slidersColsHolder">
						<div class="nlSlider">
							<?php foreach($videoProducts as $productRow):?>
								<div class="schCol">
									<article
										class="productColumn text-center text-decoration-none position-relative d-block overflow-hidden">
										<div class="imgHolder mb-2">
											<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>">

												<video class="x1lliihq x5yr21d xh8yej3 " playsinline="" muted loop autoplay
													src="adminUploads/videos/<?= $productRow['video']??'';?>"
													style="width: 260px; height: 458px; border-radius:10px;"></video>
											</a>

										</div>
										<div>
											<h3 class="fw-light pcHeading mb-1">
												<a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']);?>" class="text-decoration-none">
													<?= $productRow['name'];?>
												</a>
											</h3>
											<h4 class="fw-normal  mb-0">
												<span class="regPrice">Rs. <?= number_format($productRow['price'],2);?></span>
											</h4>

										</div>
									</article>
								</div>
							<?php endforeach;?>
						</div>
					</div>
				</div>
			</section>
		
		<!-- testimonials  -->
			<section class="club position-relative w-100 overflow-hidden py-6 ">
				<div class="container">
					<header class="headingHead text-center mb-5">
						<h2 class="hhHeading fw-normal">Testimonials</h2>
					</header>
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
							<!-- <div>
								<div class="schCol">
									<blockquote class="quoteColumn overflow-hidden bg-white p-8">
										<i
											class="qcIcn icomoon-quotes bg-black text-white rounded-circle d-flex align-items-center justify-content-center mb-4"><span
												class="visually-hidden">"</span></i>
										<h3 class="qcHeading fw-medium mb-2">Elegant and Comfortable</h3>
										<p>I absolutely love the Abayas I bought from here. They’re stylish, breathable,
											and perfect for everyday wear. Highly recommended!</p>
										<div class="d-flex gap_1 mt-5">
											<cite class="flex-grow-1 qcCite fw-normal">Ayesha Khan</cite>
											<ul class="list-unstyled ratingStaticList d-flex mb-0">
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															1</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															2</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															3</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															4</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															5</span></i></li>
											</ul>
										</div>
									</blockquote>
								</div>
							</div>
							<div>
								<div class="schCol">
									<blockquote class="quoteColumn overflow-hidden bg-white p-8">
										<i
											class="qcIcn icomoon-quotes bg-black text-white rounded-circle d-flex align-items-center justify-content-center mb-4"><span
												class="visually-hidden">"</span></i>
										<h3 class="qcHeading fw-medium mb-2">Perfect for Every Occasion</h3>
										<p>I’ve worn their Fancy Abayas to events and weddings — always get compliments!
											The detailing is so elegant.</p>
										<div class="d-flex gap_1 mt-5">
											<cite class="flex-grow-1 qcCite fw-normal">Fatima Rizvi</cite>
											<ul class="list-unstyled ratingStaticList d-flex mb-0">
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															1</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															2</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															3</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															4</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															5</span></i></li>
											</ul>
										</div>
									</blockquote>
								</div>
							</div>
							<div>
								<div class="schCol">
									<blockquote class="quoteColumn overflow-hidden bg-white p-8">
										<i
											class="qcIcn icomoon-quotes bg-black text-white rounded-circle d-flex align-items-center justify-content-center mb-4"><span
												class="visually-hidden">"</span></i>
										<h3 class="qcHeading fw-medium mb-2">Great Quality Abayas</h3>
										<p>The fabric is super soft and durable. Even after multiple washes, the Abayas
											retain their color and shape beautifully.</p>
										<div class="d-flex gap_1 mt-5">
											<cite class="flex-grow-1 qcCite fw-normal">Sara Malik</cite>
											<ul class="list-unstyled ratingStaticList d-flex mb-0">
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															1</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															2</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															3</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															4</span></i></li>
												<li><i class="icomoon-star dull"><span class="visually-hidden">rated
															star 5</span></i></li>
											</ul>
										</div>
									</blockquote>
								</div>
							</div>
							<div>
								<div class="schCol">
									<blockquote class="quoteColumn overflow-hidden bg-white p-8">
										<i
											class="qcIcn icomoon-quotes bg-black text-white rounded-circle d-flex align-items-center justify-content-center mb-4"><span
												class="visually-hidden">"</span></i>
										<h3 class="qcHeading fw-medium mb-2">Stylish Yet Modest</h3>
										<p>These Abayas are the perfect mix of fashion and modesty. I wear them daily
											and always feel confident and comfortable.</p>
										<div class="d-flex gap_1 mt-5">
											<cite class="flex-grow-1 qcCite fw-normal">Huda Ansari</cite>
											<ul class="list-unstyled ratingStaticList d-flex mb-0">
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															1</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															2</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															3</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															4</span></i></li>
												<li><i class="icomoon-star"><span class="visually-hidden">rated star
															5</span></i></li>
											</ul>
										</div>
									</blockquote>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</section>


			<section class="collapsiblesBlock w-100 position-relative overflow-hidden pt-6 pb-8 pb-lg-11 pb-xl-14">
				<div class="container">
					<header class="headingHead text-center mb-5">
						<h1 class="hhHeading fw-normal">FAQ</h1>

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
											<p>At ABAYA Co., we offer reliable and efficient shipping to ensure your
												abayas reach you in perfect condition and on time. Below is an overview
												of our shipping process</p>
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
											<p>LooksAbaya proudly ships worldwide. Whether you're in the Middle East,
												Europe, North America, or Asia, we ensure that your chosen abayas are
												delivered safely and efficiently right to your doorstep. Our
												international orders are shipped via trusted global couriers like DHL,
												FedEx, and Aramex, with tracking provided for complete peace of mind.
											</p>
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
											<p>You'll receive a tracking link as soon as your order ships, so you can
												follow its journey right to your doorstep. Whether you're ordering from
												across the street or across the globe, we work to make sure your
												LooksAbaya package arrives quickly, safely, and beautifully wrapped.</p>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button border-0 px-0 collapsed" type="button"
											data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
											aria-expanded="false" aria-controls="flush-collapseFour">
											How Long Will It Take To Get My Package?
										</button>
									</h2>
									<div id="flush-collapseFour" class="accordion-collapse collapse"
										data-bs-parent="#shoppingAccordion">
										<div class="accordion-body p-0">
											<p>
												At <strong>LooksAbaya</strong>, we process and ship orders promptly to
												ensure you receive your package on time.

												<strong>Domestic Orders (India):</strong> Typically delivered within
												<strong>3–5 business days</strong> after dispatch.

												<strong>International Orders:</strong> Delivered within <strong>7–12
													business days</strong>, depending on your location and local
												customs.

												Once shipped, you’ll receive a tracking link to monitor your package’s
												journey right to your doorstep.
											</p>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>


			</section>

		</main>
		<?php include 'include/footer.php';?>
		
	</div>
</body>

<script src="js/jquery.min.js" defer=""></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>

</html>