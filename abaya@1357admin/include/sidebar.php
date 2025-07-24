<!-- leftbar-tab-menu -->
<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="home.php" class="logo">
            <span>
                <!-- <img src="images/favicon.jpg" alt="logo-small" class="logo-sm rounded-circle"> -->
            </span>
            <span class="">
                <!-- <img src="images/logo-text-light.png" alt="logo-large" class="logo-lg logo-light"> -->
                <img src="images/ayaans_logo-removebg.png" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <span>Main Menu</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php" role="button" aria-expanded="false">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboards</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarBanner" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarBanner">
                            <i class="fas fa-image menu-icon"></i>
                            <span>Banner</span>
                        </a>
                        <div class="collapse " id="sidebarBanner">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-banner.php">Add Banner</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-banner.php">View Banner</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarTestimonial" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarTestimonial">
                            <i class="fas fa-comment menu-icon"></i>
                            <span>Testimonial</span>
                        </a>
                        <div class="collapse" id="sidebarTestimonial">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-testimonial.php">Add Testimonial</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-testimonial.php">View Testimonial</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarFaq" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarFaq">
                            <i class="fas fa-question-circle menu-icon"></i>
                            <span>FAQ</span>
                        </a>
                        <div class="collapse" id="sidebarFaq">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-faq.php">Add FAQ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-faq.php">View FAQ</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="view-notice.php">
                            <i class="fas fa-bell menu-icon"></i>
                            <span>Notice</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="view-terms-and-conditions.php">
                            <i class="fas fa-bell menu-icon"></i>
                            <span>Policy</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view-customers.php">
                            <i class="fas fa-user menu-icon"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view-orders.php">
                            <i class="fas fa-shopping-bag menu-icon"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view-contact.php">
                            <i class="iconoir-phone menu-icon"></i>
                            <span>Contact</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Categories & Products</span>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarApplications" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApplications">
                            <i class="iconoir-grid-minus menu-icon"></i>
                            <span>Category</span>
                        </a>
                        <div class="collapse " id="sidebarApplications">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-category.php">Add Category</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-category.php">View Category</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                   
                    
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarColor" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarColor">
                            <i class="iconoir-color-swatch menu-icon"></i>
                            <span>Color</span>
                        </a>
                        <div class="collapse" id="sidebarColor">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-color.php">Add Color</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-color.php">View Color</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProducts">
                            <i class="fas fa-chart-pie menu-icon"></i>
                            <span>Products</span>
                        </a>
                        <div class="collapse " id="sidebarProducts">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="add-products.php">Add Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view-products.php">View Products</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- <li class="menu-label mt-2">
                            <small class="label-border">
                                <div class="border_left hidden-xs"></div>
                                <div class="border_right"></div>
                            </small>
                            <span>Website Access Password</span>
                        </li> -->
                    <!-- <li class="nav-item">
                            <a class="nav-link" href="#sidebarPassword" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarPassword">
                                <i class="fas fa-key menu-icon"></i>
                                <span>Password</span>
                            </a>
                            <div class="collapse " id="sidebarPassword">
                                <ul class="nav flex-column">                                 
                                    <li class="nav-item">
                                        <a class="nav-link" href="add-password.php">Add Password</a>
                                    </li> 
                                    <li class="nav-item">
                                        <a class="nav-link" href="view-password.php">View Password</a>
                                    </li>                                 
                                </ul>
                            </div>
                        </li> -->
                </ul>
            </div>
        </div><!--end startbar-collapse-->
    </div><!--end startbar-menu-->
</div><!--end startbar-->
<div class="startbar-overlay d-print-none"></div>
<!-- end leftbar-tab-menu-->