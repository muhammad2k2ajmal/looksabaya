<?php

$cartItem = new Cart();

// Set Session for cart
if (!isset($_SESSION['cart_item'])) {
	$_SESSION['cart_item'] = strtoupper(uniqid() . time() . str_shuffle('12345'));
	header("Location: " . $_SERVER['REQUEST_URI']); // Reloads the same page
	exit;
}

$ipAddress = $_SERVER["REMOTE_ADDR"];

$cartSqlCount = $cartItem->cartCount($_SESSION['cart_item'], $ipAddress);

if (!empty($cartSqlCount['CartCount'])):
	$cartTotalCount = $cartSqlCount['CartCount'];
else:
	$cartTotalCount = 0;
endif;
$category = new Categories();

// Fetch all categories with active products
$categories = $common->getAllCategoriesWithProducts();
$urlParam = '';

?>
<header id="pageHeader">
			<div class="phTopBar bg-dark py">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center">
						<div class="mail-text">
							<a href="mailto:abayalooks@gmail.com" class="text-white"><i class="ri-mail-fill"></i>
								abayalooks@gmail.com</a>
						</div>
						<div>
							<div class="top-bar__social">
								<a href="#0"><i class="ri-facebook-fill"></i></a>
								<a href="https://www.instagram.com/looksabaya?igsh=NW1kMTJuZ3Y4bmNp" target="_blank"><i
										class="ri-instagram-line"></i></a>
								<a href="#0"><i class="ri-youtube-fill"></i></a>

							</div>
						</div>
					</div>
				</div>
			</div> 
			<div class="phWrapper pbpt-20 ">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center">
						<div class="position-relative mobile_hidden">
							<div class="navbar navbar-expand-md p-0 position-static">
								<button class="navbar-toggler border-0 p-0 mainNavigationToggle position-absolute"
									type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
									<span class="navbar-toggler-icon"></span>
								</button>
							</div>
							<form class="d-flex position-relative d-none d-md-block phSearchForm" role="search">
								<input class="form-control border-1 pe-8" type="search" placeholder="Search Products"
									aria-label="Search">
								<button
									class="p-0 border-0 btnReset d-flex align-items-center justify-content-center position-absolute rounded-0 btnSUbmit"
									type="submit">
									<i class="icomoon-search"><span class="visually-hidden">submit</span></i>
								</button>
							</form>
						</div>
						<div class="ms_45">
							<div class="logo text-center mx-auto mobile-text">
								<a href="index.php">
									<h2 class="mb-0 ">
										ABAYA LOOKS 
									</h2>
								</a>
							</div>
						</div>
						<div class=" position-relative mobile_hidden_1 min_width">
							<div class="navbar navbar-expand-md p-0 position-static">
								<button class="navbar-toggler border-0 p-0 mainNavigationToggle position-absolute"
									type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
									<span class="navbar-toggler-icon"></span>
								</button>
							</div>
							<form class="d-flex position-relative d-none d-md-block phSearchForm" role="search">
								<input class="form-control border-1 pe-8" type="search" placeholder="Search Products"
									aria-label="Search">
								<button
									class="p-0 border-0 btnReset d-flex align-items-center justify-content-center position-absolute rounded-0 btnSUbmit"
									type="submit">
									<i class="icomoon-search"><span class="visually-hidden">submit</span></i>
								</button>
							</form>
						</div>

						<div class="me-2">
							<ul
								class="list-unstyled d-flex flex-wrap justify-content-end mb-0 phActionsList gap-2 gap-md-4 pt-1 pe-md-3">
								<?php if (isset($_SESSION['USER_LOGIN'])): ?>
									<li>
										<a href="abayalooks-dashboard.php" class="position-relative d-block">
											<i class="icomoon-user"><span class="visually-hidden">account</span></i>
										</a>
									</li>
								<?php else: ?>
									<li>
										<a href="abayalooks-login.php" class="position-relative d-block">
											<i class="icomoon-user"><span class="visually-hidden">login</span></i>
										</a>
									</li>
								<?php endif; ?>
								<li data-bs-toggle="offcanvas" data-bs-target="#filtersProduct"
									aria-controls="filtersProduct">
									<a href="#0" class="position-relative d-block">
										<i class="icomoon-cart"><span class="visually-hidden">cart</span></i>
										<strong
											class="phCartBubble fw-semibold d-block text-center rounded-circle position-absolute">0</strong>
									</a>
								</li>
							</ul>
							<div class="searchrow offcanvas offcanvas-top" tabindex="-1" id="searchCol"
								aria-labelledby="searchcol" style="height: 100vh;">
								<div class="offcanvas-header justify-content-end">
									<button type="button" class="btn-close" data-bs-dismiss="offcanvas"
										aria-label="Close"></button>
								</div>
								<div
									class="offcanvas-body d-flex flex-column align-items-start justify-content-md-start px-4 pt-2">
									<h5 class="srchHD fw-light mb-3">What are you looking for?</h5>
									<form class="w-100" role="search">
										<div class="input-group">
											<input type="search" class="form-control border-0"
												placeholder="Search for products" aria-label="Search">
											<button class="btn" type="submit">
												<i class="fa-solid fa-magnifying-glass"></i>
											</button>
										</div>
									</form>
								</div>
							</div>
							<div class="cartsection offcanvas offcanvas-end d-flex flex-column border-0" tabindex="-1"
								id="filtersProduct" aria-labelledby="filtersProductLabel">
								<div class="offcanvas-header px-3 py-3 px-sm-8 py-sm-3">
									<h6 class="cartHD mb-1 fw-normal">Your Cart (2)</h6>
									<a class="btn-close fw-light text-decoration-none" data-bs-dismiss="offcanvas"
										aria-label="Close">Close</a>
								</div>
								<div
									class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between px-3 py-5 px-sm-8 py-sm-8">
									<div>
										<div class="d-flex align-items-start justify-content-between mb-4">
											<div class="imgholder me-2">
												<img src="images/img85.jpg" alt="Ring" class="w-100 img-fluid">
											</div>
											<div class="flex-grow-1">
												<h4 class="cartHding fw-light mb-1">Premium Three Piece Abaya Set with Balloon Sleeves</h4>
												<small class="subheading fw-normal">1 × <strong class="fw-normal">Rs.
														380.00</strong></small>
											</div>
											<button
												class="btn btn-sm p-0 ms-2 btnclose border-0 bg-transparent">&times;</button>
										</div>
										<hr class="mb-4">
										<div class="d-flex align-items-start justify-content-between mb-3">
											<div class="imgholder me-2">
												<img src="images/img86.jpg" alt="Ring" class="w-100 img-fluid">
											</div>
											<div class="flex-grow-1">
												<h4 class="cartHding fw-light mb-1">Luxury Textured Open Abaya with Tulle Lace Detailing</h4>
												<small class="subheading fw-normal">1 × <strong class="fw-normal">Rs.
														920.00</strong></small>
											</div>
											<button
												class="btn btn-sm p-0 ms-2 btnclose border-0 bg-transparent">&times;</button>
										</div>
									</div>
									<div>
										<div class="d-flex justify-content-between fw-semibold mb-3">
											<span class="HDtotal fw-normal">Total</span>
											<span class="HDprice fw-medium">Rs. 1300.00</span>
										</div>
										<div class="d-grid gap-2 mb-4">
											<a href="looksabaya-cart.php"><button class=" fw-medium btn_hover_color">View Cart</button></a>
                                            <a href="looksabaya-checkout.php"><button class=" fw-medium btn_hover_color">Checkout</button></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="phNavWrapper position-relative">
				<div class="container">
					<nav class="navbar navbar-expand-md d-block position-static" id="mainNavigation">
						<div class="row align-items-center justify-content-center">
							<div class="col-12 col-lg-8 col-xl-8 mnCol d-none d-md-block">
								<div class="collapse navbar-collapse mainNavigationCollapse"
									id="mainNavigationCollapse">
									<ul class="navbar-nav mx-auto mx-lg-0 mx-xl-auto gap-md-4 gap-xxl-7">
                                        <li class="nav-item staf dropdown">
                                            <a class="nav-link text-uppercase fw-medium" href="looksabaya-products.php?new=<?= base64_encode(1); ?>">New
                                                Arrivals</a>
                                        </li>
										<?php
										$categories=$common->getAllCategoriesWithProducts();
										foreach ($categories as $category) :?>
                                        <li class="nav-item staf dropdown">
                                            <a class="nav-link text-uppercase fw-medium"
                                                href="looksabaya-products.php?cid=<?= base64_encode($category['id']); ?>"><?= $category['name']; ?></a>
                                        </li>
										<?php endforeach; ?>
                                    </ul>
								</div>
							</div>

						</div>
					</nav>
				</div>
			</div>
		</header>