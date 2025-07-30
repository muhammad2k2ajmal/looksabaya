<div class="footerAreaWrap">
			<aside class="footerAside pt-7 pb-4 pt-md-10 pb-md-6 pt-xl-14 pb-xl-10">
				<div class="container">
					<div class="ftColsWrap d-flex flex-wrap row-gap-3">
						<div class="ftCol">
							<div class="ftLogo mx-auto mx-sm-0 mb-6 text-center">
								<a href="index.php" class="text-decoration-none ">
									<h5 class="mb-0 offcanvas-title text-white">ABAYA<span class="ms-1">LOOKS </span>
									</h5>

								</a>
							</div>
						</div>
						<div class="ftCol">
							<h3 class="ftHeading mb-5 fw-normal">Shop Abayas</h3>
							<ul class="list-unstyled ftLinksList">
								<li>
									<a href="looksabaya-products.php?new=<?= base64_encode(1); ?>">New Arrivals</a>
								</li>
								<?php
										$categories=$common->getAllCategoriesWithProducts();
										foreach ($categories as $category) :?>
									<li>
										<a href="looksabaya-products.php?cid=<?= base64_encode($category['id']); ?>"><?= $category['name']; ?></a>
									</li>
								<?php endforeach; ?>

								<!-- <li>
									<a href="looksabaya-products.php">Daily Wear Abaya</a>
								</li>
								<li>
									<a href="looksabaya-products.php">Fancy Abaya</a>
								</li>
								<li>
									<a href="looksabaya-products.php">Occasional Abaya</a>
								</li> -->
								<li>
									<a href="looksabaya-about-us.php">About Us</a>
								</li>
								<li>
									<a href="looksabaya-contact.php">Contact Us</a>
								</li>

							</ul>
						</div>
						<div class="ftCol">
							<h3 class="ftHeading mb-5 fw-normal">Customer Service</h3>
							<ul class="list-unstyled ftLinksList">

								<li>
									<a href="abayalooks-privacy-policy.php">Privacy Policy</a>
								</li>
								<li>
									<a href="abayalooks-return-policy.php">Return and Refund Policy</a>
								</li>
								<li>
									<a href="abayalooks-terms-&-conditions.php">Terms & Conditions</a>
								</li>
								<li>
									<a href="abayalooks-shipping-and-delivery-policy.php">Shipping and Delivery Policy</a>
								</li>
								<li>
									<a href="looksabaya-faq.php">Faq</a>
								</li>
							</ul>
						</div>

						<div class="ftCol">
							<h3 class="ftHeading mb-5 fw-normal">Store Details</h3>
							<address class="ftLocation">
								<a href="tel:+918910781331"
									class="d-flex align-items-center text-decoration-none gap-2 ftPhone fw-normal mb-3">
									<i
										class="icnWrap flex-shrink-0 d-flex align-items-center justify-content-center icomoon-headphone rounded-circle"><span
											class="visually-hidden">icon</span></i>
									<div>
										<strong class="d-block fw-light ftTitle text-white">Need Any Help?</strong>
										<span>+918910781331  </span>
									</div>
								</a>
								<span class="d-block mb-1"><strong class="fw-normal">Address:</strong> 502 Zakir Nager
									Str, <br> Oklha New Delhi</span>
								<span class="d-block mb-1"><strong class="fw-normal">Email:</strong> <a
										href="mailto:abayalooks@gmail.com"
										class="text-decoration-none text-white">abayalooks@gmail.com</a></span>
							</address>
						</div>
						<div class="ftCol">
							<h3 class="ftHeading mb-5 fw-normal">Follow Us</h3>
							<ul
								class="list-unstyled socialNetworks d-flex flex-wrap gap-4 gap-sm-2 gap-md-4 ftSocialNetworks mb-0">
								<li>
									<a href="javascript:void(0);" class="text-decoration-none">
										<i class="ri-facebook-fill"><span class="visually-hidden">facebook</span></i>
									</a>
								</li>

								<li>
									<a href="https://www.instagram.com/looksabaya?igsh=NW1kMTJuZ3Y4bmNp" target="_blank"
										class="text-decoration-none">
										<i class="icomoon-instagram"><span class="visually-hidden">instagram</span></i>
									</a>
								</li>
								<li>
									<a href="javascript:void(0);" class="text-decoration-none">
										<i class="ri-youtube-fill"><span class="visually-hidden">pinterest</span></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</aside>
			<footer id="pageFooter" class="text-center py-2">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center ">

						<div>
							<p class="mb-0 text-white">&copy; Copyright 2025 - Looksabaya</a>
								All Rights Reserved</p>
						</div>
						
						<div>
							<p class="mb-0 text-white link-hover "><a
									href="https://www.ahmadwebsolutions.com/" target="_blank">Ecommerce
									Design Experts</a></p>
						</div>
					</div>




				</div>
			</footer>
		</div>
		<!-- Sidebar Menu Wrapper -->
		<div class="sidebarMenu offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu">
			<div class="offcanvas-header justify-content-between py-3 px-4">
				<h5 class="offcanvas-title mb-0"><a href="index.php">ABAYA <span class="ms-2">LOOKS</span></a></h5>
				<a class="btn-close fw-light text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close"></a>
			</div>
			<div class="offcanvas-body p-0">

				<div class="tab-content" id="menuTabContent">
					<div class="tab-pane fade show active" id="menu" role="tabpanel" aria-labelledby="menu-tab">
						<ul class="list-unstyled">
							<li class="py-2 px-4">
								<a class="text-decoration-none fw-medium " href="looksabaya-products.php">New Arrivals
								</a>
							</li>
							<li class="py-2 px-4">
								<a class="text-decoration-none fw-medium d-block " href="looksabaya-products.php">
									Daily Wear Abaya
								</a>
							</li>
							<li class="py-2 px-4">
								<a class="collapsed text-decoration-none fw-medium d-block "
									href="looksabaya-products.php">Fancy Abaya
								</a>
							</li>
							<li class="py-2 px-4">
								<a class="collapsed text-decoration-none fw-medium d-block "
									href="looksabaya-products.php">Occassional
									Abaya
								</a>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</div>