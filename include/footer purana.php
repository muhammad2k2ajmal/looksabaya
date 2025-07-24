	<footer>
		<?php $common = new CommProducts();?>
		<div class="footer-wrapper  kitchen">
			<div class="container ">
				<div class="footer-area position-relative ">
					<div class="row g-4">
						<div class="col-xl-3 col-lg-4 col-sm-6">
							<div class="single-footer-caption">
								<div class="footer-tittle">
									<h4 class="title">About Looksabaya</h4>
									<!-- <p class="para">Etoshi is an exciting contemporary brand
										which focuses on high-quality products
										graphics with a British style</p> -->
									<div class="footer-social-section">
										<ul class="footer-social-lists">
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-facebook-fill"></i>
												</a>
											</li>
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-whatsapp-fill"></i>
												</a>
											</li>
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-twitter-fill"></i>
												</a>
											</li>
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-instagram-fill"></i>
												</a>
											</li>
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-linkedin-fill"></i>
												</a>
											</li>
											<li class="list-icon">
												<a href="javascript:void(0)" class="list">
													<i class="ri-pinterest-fill"></i>
												</a>
											</li>
										</ul>
									</div>
									<ul class="info-listing pt-2">
										<li class="footer-info-list">
											<a href="#" class="single">
												<i class="far fa-envelope"></i>

												<p class="para">info@seamdecor.co</p>
											</a>
										</li>
										<li class="footer-info-list">
											<a href="#" class="single">
												<div class="d-flex gap-6">
													<i class="ri-phone-fill"></i>
													<p class="para">+91 9911993344</p>
												</div>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="col-xl-2 offset-xl-1 col-lg-3 col-sm-6">
							<div class="single-footer-caption">
								<div class="footer-tittle">
									<h4 class="title">My Accounts</h4>
									<ul class="listing">
										<li class="single-list"><a href="login.php" class="single">Login</a></li>
										<li class="single-list"><a href="register.php" class="single">Sign Up</a>
										</li>
										<li class="single-list"><a href="shopping-cart.php" class="single">
												Cart</a></li>
										<li class="single-list"><a href="account.php" class="single">Account
												Settings</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xl-2 offset-xl-1 col-lg-2 col-sm-6">
							<div class="single-footer-caption">
								<div class="footer-tittle">
									<h4 class="title">Categories</h4>
									<ul class="listing">
										<?php
											$categories=$common->getAllCategoriesWithProduct();
											foreach($categories as $catRow):
										?>
										<li class="single-list"><a href="explore-all.php?cid=<?= base64_encode($catRow['id']);?>" class="single"><?= $catRow['name'];?></a></li>
										<?php endforeach;?>
										
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xl-2 offset-xl-1 col-lg-3 col-sm-6">
							<div class="single-footer-caption">
								<div class="footer-tittle">
									<h4 class="title">Our Policies</h4>
									<ul class="listing">
										<li class="single-list"><a href="client-and-faqs.php" class="single">Client
												Reviews and FAQs
											</a></li>
										<li class="single-list"><a href="terms-and-condition.php" class="single">Terms
												& Conditions</a></li>
										<li class="single-list"><a href="privacy-policy.php" class="single">Privacy Policy</a>
										<li class="single-list"><a href="return-and-refund-policy.php" class="single">Return and Refund Policy</a>
										<li class="single-list"><a href="shipping-and-delivery-policy.php" class="single">Shipping and Delivery Policy</a>
										<li class="single-list"><a href="contact.php" class="single">Contact Us</a>
										</li>

									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- footer-bottom area -->
			<div class="footer-bottom-area position-relative">
				<div class="container">
					<div class="d-flex justify-content-between gap-14 flex-wrap">
						<div class="privacy-section d-flex">

							<a href="#0">
								<p class="para ml-25 text-white">© 2025 Looksabaya. All Rights Reserved</p>
							</a>
						</div>
						<div class="payment-list">
							<a class="aws-office" href="https://www.ahmadwebsolutions.com/" target="_blank">Designed By
								AWS</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>