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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.css">



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
                                        <a href="looksabaya-contact.php" class="text-decoration-none">Contact</a>
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="stroreBlock w-100 position-relative overflow-hidden py-6">
                <div class="container">
                    <header class="headingHead text-center mb-8 mb-xl-12">
                        <h2 class="hhHeading HDii fw-normal">Contact</h2>
                    </header>

                    <address class="row bioContact text-center mb-0  row-gap-6">
                        <div class="col-12 col-md-4">
                            <i class="icnWrap d-flex align-items-center justify-content-center mb-2">
                                <span class="w-100">
                                    <img src="images/ico-08.svg" class="img-fluid" width="18" height="30" alt="icon">
                                </span>
                            </i>
                            <strong class="fw-medium bcdHeading d-block mb-1">OUR STORE</strong>
                            <p>502 Zakir Nager Str,
                                Oklha New Delhi</p>
                        </div>
                        <div class="col-12 col-md-4">
                            <i class="icnWrap d-flex align-items-center justify-content-center mb-2">
                                <span class="w-100">
                                    <img src="images/ico-09.svg" class="img-fluid" width="30" height="30" alt="icon">
                                </span>
                            </i>
                            <strong class="fw-medium bcdHeading d-block mb-1">CONTACT INFO</strong>
                            <ul class="list-unstyled mb-0">
                                <li>Telephone: <a href="tel:+918910781331" class="text-decoration-none">+918910781331</a></li>

                            </ul>
                        </div>
                        <div class="col-12 col-md-4">
                            <i class="icnWrap d-flex align-items-center justify-content-center mb-2">
                                <span class="w-100 ">
                                    <img src="images/envelope.png" class="img-fluid" width="40" height="30" alt="icon">
                                </span>
                            </i>
                            <strong class="fw-medium bcdHeading d-block mb-1">Email</strong>
                            <a href="mailto:abayalooks@gmail.com" class="text-decoration-none">abayalooks@gmail.com</a>
                        </div>
                    </address>
                </div>
            </section>
            <div class="container">
                <hr class="my-0">
            </div>
            <section class="formBlock w-100 position-relative overflow-hidden py-6 py-lg-9 py-xl-12">
                <div class="container">
                    <header class="headingHead text-center mb-8 mb-xl-12">
                        <h2 class="hhHeading fw-normal">Have an question? Contact us!</h2>
                    </header>
                    <form  id="contactForm" method="POST" >
                        <div class="frmWrap">
                            <div class="row">
                                <div class="col-12 col-md-8 offset-md-2">
                                    <div class="row mx-n2">
                                        <div class="col-12 col-sm-6 px-2">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" placeholder="Name" name="name">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 px-2">
                                            <div class="form-group mb-4">
                                                <input type="email" class="form-control" placeholder="Email" name="email">
                                            </div>
                                        </div>
                                        <div class="col-12 px-2">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" placeholder="Subject" name="subject">
                                            </div>
                                        </div>
                                        <div class="col-12 px-2">
                                            <div class="form-group mb-4">
                                                <textarea class="form-control" name="message" 
                                                    placeholder="Write your comment&hellip;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btnWrap">
                                        <input type="submit" class="btn btn-primary w-100" value="Send Message">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </section>
            <div class="mapFrameWrap position-relative w-100">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3504.108189460397!2d77.2798602754984!3d28.566513775700837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce32d7d199789%3A0xfb91d7391d30c139!2sAbayalooks!5e0!3m2!1sen!2sin!4v1752926725830!5m2!1sen!2sin"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

        </main>
        <?php include 'include/footer.php'; ?>

    </div>
</body>

<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.js"></script>
<!-- Script -->
		<script>
	$(document).ready(function() {
		// jQuery Validation setup
		$("#contactForm").validate({
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 50
				},
				subject: {
					required: true,
					minlength: 10,
					maxlength: 50
				},
				email: {
                    required: true,
					email: true,
					maxlength: 50
				},
				message: {
					maxlength: 300
				}
			},
			messages: {
				name: {
					required: "Please enter your Name",
					minlength: "Your name must be at least 3 characters long"
				},
				email: {
                    required: "Please enter your Email",
					email: "Please enter a valid email address"
				},
				subject: {
					required: "Please enter a subject",
					minlength: "Your subject must be at least 10 characters long",
					maxlength: "Your subject cannot be more than 50 characters long"    
				},
				message: {
					maxlength: "Your message cannot exceed 300 characters"
				}
			},
			submitHandler: function(form) {
				// Debugging the form data before AJAX call
				let formData = $(form).serialize();
				console.log("Form Data being sent: ", formData); // Log form data
                // Use AJAX to submit the form data
                    $.ajax({
                        url: 'contactsubmit', // The PHP script that processes the form
                        type: 'post',
                        data: formData, // Pass serialized form data
                        dataType: 'json', // Expect JSON response
                        success: function(response) {
                            // Debugging the response from the server
                            console.log("AJAX Response: ", response); // Log the response object

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Reload the page after user clicks "OK"
                                        console.log("Reloading page..."); // Log page reload
                                        location.reload();
                                    }
                                });
                            } else if (response.status === 'error') {
                                console.error("Error response received: ", response.message); // Log error message
                                Swal.fire({
                                    title: 'Oops!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                form.reset();
                            }
                        },
                        error: function(xhr, status, error) {
                            // Debugging AJAX error
                            console.error("AJAX Error: ", status, error); // Log error details
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was a problem with the request. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            form.reset();
                        }
                    });
                },
                invalidHandler: function(event, validator) {
                    // Log validation errors
                    console.log("Form validation errors: ", validator.numberOfInvalids());
                    if (validator.numberOfInvalids()) {
                        console.log("Form contains invalid fields.");
                    }
                }
		});
	});
</script>


</html>