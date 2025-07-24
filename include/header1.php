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
$categories = $category->getAllCategoriesWithProducts();
$urlParam = '';
if (isset($_GET['new']) && $_GET['new'] == 1) {
	$urlParam = '&new=1';
} elseif (isset($_GET['bestseller']) && $_GET['bestseller'] == 1) {
	$urlParam = '&bestseller=1';
}
?>
<style>
	.mega-col-group {
    display: flex;
    gap: 10px; /* space between columns */
}

.mega-col {
    flex: 1;          /* evenly distribute width */
    min-width: 170px; /* minimum width of a column */
}

.mega-col h6 {
    font-weight: bold;
    margin-bottom: 10px;
}

.mega-col ul {
    list-style: none;
    padding-left: 0;
}

.mega-col ul li {
    margin-bottom: 8px;
}

.mega-col ul li a {
    text-decoration: none;
    color: #333;
}

.mega-col ul li a:hover {
    text-decoration: underline;
}

.new-badge {
    display: inline-block;
    background-color: #ff5733;
    color: white;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 3px;
    margin-left: 5px;
    vertical-align: middle;
}
</style>
<header>
	<div class="header-area">
		<div class="main-header">
			<div class="top-menu-wrapper d-none d-lg-block spoon-dark">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex align-items-center justify-content-between">
								<!-- Top Left Side -->
								<div class="top-header-left d-flex align-items-center ">
									<div class="top-menu">
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="13"
											viewBox="0 0 18 13" fill="none">
											<path
												d="M17.2021 1.33173C17.2021 4.77563 17.2021 8.21953 17.2021 11.6634C17.198 11.6718 17.1897 11.6843 17.1897 11.6926C17.0691 12.4941 16.3995 13.0243 15.5511 12.9992C14.8108 12.9784 14.0664 12.9951 13.3261 12.9951C9.47914 12.9951 5.63219 12.9951 1.78525 12.9951C0.86198 12.9951 0.20488 12.3606 0.20488 11.4547C0.200721 8.15274 0.200721 4.8466 0.209039 1.54045C0.209039 1.31086 0.267263 1.06039 0.362917 0.851674C0.64572 0.258906 1.1531 9.15527e-05 1.80188 9.15527e-05C6.40158 9.15527e-05 11.0013 9.15527e-05 15.5968 9.15527e-05C15.6343 9.15527e-05 15.6758 9.15527e-05 15.7133 9.15527e-05C16.1874 0.0209637 16.5783 0.204638 16.8778 0.576162C17.0607 0.801581 17.1398 1.06457 17.2021 1.33173ZM1.91417 1.0103C4.20155 3.28536 6.45981 5.53537 8.70559 7.76452C10.9597 5.50198 13.2013 3.25197 15.4388 1.0103C10.9555 1.0103 6.45565 1.0103 1.91417 1.0103ZM15.443 11.989C13.8709 10.4111 12.2905 8.82482 10.706 7.23436C10.6769 7.26359 10.6311 7.30533 10.5896 7.34707C10.1154 7.82296 9.64134 8.29884 9.16723 8.77055C8.86363 9.07528 8.60994 9.07528 8.29803 8.76638C7.81144 8.28214 7.32485 7.79791 6.83826 7.3095C6.79668 7.26776 6.77588 7.20097 6.75925 7.17592C5.12897 8.8123 3.54445 10.4028 1.96408 11.989C6.44733 11.989 10.9514 11.989 15.443 11.989ZM1.21132 1.71578C1.21132 4.92173 1.21132 8.08595 1.21132 11.2543C2.80417 9.65553 4.3887 8.06925 5.98154 6.46627C4.40117 4.89251 2.81665 3.31458 1.21132 1.71578ZM11.4837 6.49967C13.0474 8.06925 14.6237 9.65135 16.1957 11.2293C16.1957 8.08177 16.1957 4.91339 16.1957 1.77005C14.6278 3.34798 13.0474 4.93008 11.4837 6.49967Z"
												fill="#FFFFFF" />
										</svg>
										<a href="javascript:void(0)">
											<p class="pera text-color-secondary">infoseamdecor@gmail.com</p>
										</a>
									</div>
								</div>
								<!--Top Right Side -->
								<div class="top-header-right">
									<div class="login-wrapper ml-48">
										<a href="javascript:void(0)" class="list text-white">
											<i class="ri-whatsapp-fill"></i>
										</a>
									</div>
									<div class="login-wrapper ml-20">
										<a href="#" class="single">
											<div class="d-flex gap-6">
												<i class="ri-phone-fill"></i>
											</div>
										</a>
										<a href="contact.php">
											<p class="pera text-color-primary">
												Contact Us
											</p>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Top bar -->
			<div class="header-top">
				<div class="container">
					<div class="row align-items-center justify-content-between">
						<!-- 1) MOBILE TOGGLE + LOGO -->
						<div class="col-4 col-md-3 mobile-only-align">
							<div class="col-3 d-flex align-items-center row">
								<button class="navbar-toggler d-lg-none border-0" type="button"
									data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
									<i class="fas fa-bars fa-lg"></i>
								</button>
								<!-- OFFCANVAS MOBILE MENU -->
								<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu"
									aria-labelledby="mobileMenuLabel">
									<div class="offcanvas-header border-bottom">
										<h5 id="mobileMenuLabel">Menu</h5>
										<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
											aria-label="Close"></button>
									</div>
									<div class="offcanvas-body p-0">
										<!-- Top icon bar -->
										<div class="d-flex justify-content-around py-2 border-bottom">
											<button class="btn p-0" data-bs-dismiss="offcanvas">
												<i class="fas fa-bars fa-lg"></i><br /><small>Menu</small>
											</button>
											<button class="btn p-0" data-bs-toggle="collapse"
												data-bs-target="#mobileAccountMenu" aria-expanded="false"
												aria-controls="mobileAccountMenu">
												<i class="fas fa-user fa-lg"></i><br /><small>Account</small>
											</button>
										</div>

										<div class="border-bottom" id="mobileAccountMenu">
											<?php if (isset($_SESSION['USER_LOGIN'])): ?>
												<a href="account.php">
													<div class="px-3 py-2 d-flex align-items-center">
														<i class="fas fa-home me-2"></i>
													</div>
												</a>
												<a href="signout.php">
													<div class="px-3 py-2 d-flex align-items-center">
														<i class="fas fa-sign-out me-2"></i>
													</div>
												</a>
											<?php else: ?>
												<a href="login.php">
													<div class="px-3 py-2 d-flex align-items-center">
														<i class="fas fa-home me-2"></i>
													</div>
												</a>
											<?php endif; ?>
										</div>
										<!-- Main list -->
										<div class="list-group list-group-flush">
											<!-- New -->
											<a href="explore-all.php?new=1<?php echo $urlParam == '&bestseller=1' ? $urlParam : ''; ?>"
												class="list-group-item list-group-item-action text-uppercase">
												New <i class="fas fa-chevron-right float-end"></i>
											</a>
											<!-- Collection (opens submenu) -->
											<a class="list-group-item list-group-item-action text-uppercase d-flex justify-content-between align-items-center"
												data-bs-toggle="collapse" href="#submenuCollection" role="button"
												aria-expanded="false" aria-controls="submenuCollection">
												Collection <i class="fas fa-chevron-down"></i>
											</a>
											<!-- Collection submenu -->
											<div class="collapse ps-3" id="submenuCollection">
												<?php if (empty($categories)): ?>
													<div class="list-group-item">No categories available</div>
												<?php else: ?>
													<?php foreach ($categories as $index => $cat): ?>
														<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
															<?php if ($cat['id'] == 1): ?>
																href="explore-all.php?cid=<?php echo base64_encode($cat['id']); ?><?php echo $urlParam; ?>"
															<?php else: ?>
																data-bs-toggle="collapse" href="#subCat<?php echo $cat['id']; ?>"
																aria-expanded="false" aria-controls="subCat<?php echo $cat['id']; ?>"
															<?php endif; ?>>
															<span><?php echo htmlspecialchars($cat['name']); ?>
																<?php if ($cat['new'] == 1): ?>
																	<span class="new-badge">New</span>
																<?php endif; ?>
															</span>
															<i class="fas fa-chevron-down"></i>
														</a>
														<div class="collapse ps-3" id="subCat<?php echo $cat['id']; ?>">
															<?php
															$subcategories = $category->getSubCategoriesWithProducts($cat['id']);
															if (empty($subcategories)):
																?>
																<div class="list-group-item">No subcategories</div>
															<?php else: ?>
																<?php foreach ($subcategories as $subcat): ?>
																	<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
																		data-bs-toggle="collapse"
																		href="#subSubCat<?php echo $subcat['id']; ?>"
																		aria-expanded="false"
																		aria-controls="subSubCat<?php echo $subcat['id']; ?>">
																		<span><?php echo htmlspecialchars($subcat['name']); ?>
																			<?php if ($subcat['new'] == 1): ?>
																				<span class="new-badge">New</span>
																			<?php endif; ?>
																		</span>
																		<i class="fas fa-chevron-down"></i>
																	</a>
																	<div class="collapse ps-3"
																		id="subSubCat<?php echo $subcat['id']; ?>">
																		<?php
																		$subsubcategories = $category->getSubSubCategoriesWithProducts($cat['id'], $subcat['id']);
																		if (empty($subsubcategories)):
																			?>
																			<div class="list-group-item">No items</div>
																		<?php else: ?>
																			<?php foreach ($subsubcategories as $subsubcat): ?>
																				<a href="explore-all.php?sscid=<?php echo base64_encode($subsubcat['id']); ?><?php echo $urlParam; ?>"
																					class="list-group-item list-group-item-action">
																					<?php echo htmlspecialchars($subsubcat['name']); ?>
																					<?php if ($subsubcat['new'] == 1): ?>
																						<span class="new-badge">New</span>
																					<?php endif; ?>
																				</a>
																			<?php endforeach; ?>
																		<?php endif; ?>
																	</div>
																<?php endforeach; ?>
															<?php endif; ?>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>
											<!-- Other top-level links -->
											<a href="explore-all.php?new=1<?php echo $urlParam == '&bestseller=1' ? $urlParam : ''; ?>"
												class="list-group-item list-group-item-action text-uppercase">
												Bestseller
											</a>
											<a href="explore-all.php"
												class="list-group-item list-group-item-action text-uppercase">
												Explore All <i class="fas fa-chevron-right float-end"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- logo always visible -->
							<div class="col-2">
								<a href="index.php" class="logo d-inline-block">
									<img src="./assets/img/logo.png" alt="Looksabaya" />
								</a>
							</div>
						</div>
						<!-- 2) SEARCH (hidden on mobile) -->
						<div class="col-4 col-md-6 d-none d-md-block">
							<div class="position-relative w-50 mx-auto">
								<input type="text" class="form-control rounded-pill ps-3 pe-5"
									placeholder="What are you looking for?" />
								<span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted">
									<i class="fas fa-search"></i>
								</span>
							</div>
						</div>
						<!-- 3) ICONS (always visible) -->
						<div class="col-4 col-md-3 text-end d-flex justify-content-end">
							<div class="know">
								<div
									class="icon-link me-3 position-relative text-decoration-none text-black d-flex align-items-center">
									<?php if (isset($_SESSION['USER_LOGIN'])): ?>
										<a href="account.php" class="text-decoration-none text-black">
											<i class="fa-solid fa-user"></i><br>
											<small>Account</small>
										</a>
										<a href="signout.php" class="text-decoration-none text-black ms-3">
											<i class="fa-solid fa-sign-out"></i><br>
											<small>Logout</small>
										</a>
									<?php else: ?>
										<a href="login.php" class="text-decoration-none text-black">
											<i class="fa-solid fa-user"></i><br>
											<small>Login</small>
										</a>
									<?php endif; ?>
								</div>
							</div>
							<a href="shopping-cart.php" class="icon-link position-relative">
								<i class="fa-solid fa-shopping-cart"></i><br>
								<small class="know">Shopping Cart</small>
								<span class="cart-badge know"><?php echo $cartTotalCount; ?></span>
							</a>
						</div>
					</div>
					<div class="collapse navbar-collapse mt-2 mt-md-0" id="mainNav">
						<ul class="navbar-nav nav-menu mx-auto">
							<li class="nav-item">
								<a class="nav-link"
									href="explore-all.php">Explore
									All</a>
							</li>
							<li class="nav-item">
								<a class="nav-link"
									href="explore-all.php?new=1<?php echo $urlParam == '&bestseller=1' ? $urlParam : ''; ?>">New</a>
							</li>
							<li class="nav-item position-relative">
								<a class="nav-link" href="#">
									Collection <i class="fa-solid fa-chevron-down"></i>
								</a>
								<div class="mega-menu">
									<div class="mega-container d-flex">
										<!-- Sidebar -->
										<div class="mega-sidebar me-4">
											<?php if (empty($categories)): ?>
												<a href="#" class="sidebar-link">No categories available</a>
											<?php else: ?>
												<?php foreach ($categories as $index => $cat): ?>
													<a href="<?php echo $cat['id'] == 1 ? 'explore-all.php?cid=' . base64_encode($cat['id']) . $urlParam : '#'; ?>"
														class="sidebar-link <?php echo $index === 0 ? 'active' : ''; ?>"
														data-target="cat<?php echo htmlspecialchars($cat['id']); ?>">
														<?php echo htmlspecialchars($cat['name']); ?>
														<?php if ($cat['new'] == 1): ?>
															<span class="new-badge">New</span>
														<?php endif; ?>
													</a>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
										<!-- Columns -->
										<div class="mega-columns flex-grow-1">
											<?php if (empty($categories)): ?>
												<div class="mega-col-group d-flex" data-group="empty">
													<div class="mega-col">
														<h6>No categories available</h6>
													</div>
												</div>
											<?php else: ?>
												<?php foreach ($categories as $index => $cat): ?>
													<div class="mega-col-group d-flex <?php echo $index !== 0 ? 'd-none' : ''; ?>"
														data-group="cat<?php echo htmlspecialchars($cat['id']); ?>">
														<?php
														$subcategories = $category->getSubCategoriesWithProducts($cat['id']);
														if (empty($subcategories)):
														?>
															<div class="mega-col">
																<h6>No subcategories</h6>
															</div>
														<?php else: ?>
															<?php foreach ($subcategories as $subcat): ?>
																<div class="mega-col">
																	<a href="explore-all.php?cid=<?php echo base64_encode($cat['id']); ?>&scid=<?php echo base64_encode($subcat['id']); ?><?php echo $urlParam; ?>">
																		<h6><?php echo htmlspecialchars($subcat['name']); ?>
																			<?php if ($subcat['new'] == 1): ?>
																				<span class="new-badge">New</span>
																			<?php endif; ?>
																		</h6>
																	</a>
																	<ul>
																		<?php
																		$subsubcategories = $category->getSubSubCategoriesWithProducts($cat['id'], $subcat['id']);
																		if (empty($subsubcategories)):
																		?>
																			<li>No items</li>
																		<?php else: ?>
																			<?php foreach ($subsubcategories as $subsubcat): ?>
																				<li>
																					<a href="explore-all.php?cid=<?php echo base64_encode($cat['id']); ?>&scid=<?php echo base64_encode($subcat['id']); ?>&sscid=<?php echo base64_encode($subsubcat['id']); ?><?php echo $urlParam; ?>">
																						<?php echo htmlspecialchars($subsubcat['name']); ?>
																						<?php if ($subsubcat['new'] == 1): ?>
																							<span class="new-badge">New</span>
																						<?php endif; ?>
																					</a>
																				</li>
																			<?php endforeach; ?>
																		<?php endif; ?>
																	</ul>
																</div>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link"
									href="explore-all.php?bestseller=1<?php echo $urlParam == '&new=1' ? $urlParam : ''; ?>">Bestseller</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- Bottom sticky nav -->
			<div class="header-bottom">
                    <div class="position-relative search-bar">
                        <input type="text" class="form-control rounded-pill ps-3 pe-5"
                            placeholder="What are you looking for?" />
                        <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <div class="container-fluid px-5">
                        <nav class="navbar navbar-expand-lg p-0 nav-desktop">
                            <button class="navbar-toggler navbar-tooggle-align collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="navbar-collapse collapse" id="mainNav">
                                <ul class="navbar-nav nav-menu mx-auto">
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="explore-all.php?new=1<?php echo $urlParam == '&bestseller=1' ? $urlParam : ''; ?>">New</a>
                                    </li>
                                    <li class="nav-item position-relative">
                                        <a class="nav-link" href="#">
                                            Collection <i class="fa-solid fa-chevron-down"></i>
                                        </a>
                                        <div class="mega-menu">
                                            <div class="mega-container d-flex">
                                                <div class="mega-sidebar me-4">
                                                    <?php if (empty($categories)): ?>
                                                        <div class="sidebar-link">No categories available</div>
                                                    <?php else: ?>
                                                        <?php foreach ($categories as $index => $cat):
															?>
                                                            <a href="<?php if($cat['id']=='1'){echo'explore-all.php?cid='.base64_encode(1);}else{echo '#';}?>" class="sidebar-link <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                                data-target="cat<?php echo $cat['id']; ?>">
                                                                <?php echo htmlspecialchars($cat['name']); ?>
                                                                <?php if ($cat['new'] == 1): ?>
                                                                    <span class="new-badge">New</span>
                                                                <?php endif; ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="mega-columns flex-grow-1">
                                                    <?php if (empty($categories)): ?>
                                                        <div class="mega-col-group">No categories available</div>
                                                    <?php else: ?>
                                                        <?php foreach ($categories as $index => $cat): ?>
                                                            <div class="mega-col-group <?php echo $index === 0 ? '' : 'd-none'; ?>" 
                                                                data-group="cat<?php echo $cat['id']; ?>">
                                                            <?php
                                                            $subcategories = $category->getSubCategoriesWithProducts($cat['id']);
                                                            if (empty($subcategories)):
                                                            ?>
                                                                <div class="mega-col">
                                                                    <h6>No subcategories</h6>
                                                                </div>
                                                            <?php else: ?>
                                                                <?php foreach ($subcategories as $subcat): ?>
                                                                    <div class="mega-col">
                                                                        <a href="explore-all.php?cid=<?php echo base64_encode($cat['id']); ?>&scid=<?php echo base64_encode($subcat['id']); ?><?php echo $urlParam; ?>">
                                                                            <h6><?php echo htmlspecialchars($subcat['name']); ?>
                                                                                <?php if ($subcat['new'] == 1): ?>
                                                                                    <span class="new-badge">New</span>
                                                                                <?php endif; ?>
                                                                            </h6>
                                                                        </a>
                                                                        <ul>
                                                                            <?php
                                                                            $subsubcategories = $category->getSubSubCategoriesWithProducts($cat['id'], $subcat['id']);
                                                                            if (empty($subsubcategories)):
                                                                            ?>
                                                                                <li>No items</li>
                                                                            <?php else: ?>
                                                                                <?php foreach ($subsubcategories as $subsubcat): ?>
                                                                                    <li>
                                                                                        <a href="explore-all.php?cid=<?php echo base64_encode($cat['id']); ?>&scid=<?php echo base64_encode($subcat['id']); ?>&sscid=<?php echo base64_encode($subsubcat['id']); ?><?php echo $urlParam; ?>">
                                                                                            <?php echo htmlspecialchars($subsubcat['name']); ?>
                                                                                            <?php if ($subsubcat['new'] == 1): ?>
                                                                                                <span class="new-badge">New</span>
                                                                                            <?php endif; ?>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="explore-all.php?bestseller=1<?php echo $urlParam == '&new=1' ? $urlParam : ''; ?>">Bestseller</a>
                                    </li>
									<li class="nav-item">
                                        <a class="nav-link" href="explore-all.php">Explore All</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
		</div>
	</div>
</header>