<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';
require 'functions/operations.php';
require 'functions/image-resize.php';

$db = new dbClass();
$auth = new Authentication();
$user = new userDetail();

$auth->checkSession();
$userRow = $user->loginUserDetail($_SESSION['ADMIN_USER_ID']);

$id = (isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '');
$editval = $user->loginUserDetail($id);

if(isset($_REQUEST['update'])){
	$username = $db->addStr($_POST['username']);
	$phone = $db->addStr($_POST['phone']);
	$email = $db->addStr($_POST['email']);

	$oldimage = $_POST['oldimage'];
    $dest = "../adminuploads/user/";

	if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $files = resize(120, 120, $dest, $tmp_name, $image);
    
        if ($files) {
          if (file_exists($dest . $oldimage)) {
            unlink($dest . $oldimage);
          }
        } else {
          $_SESSION['errmsg'] = 'Error uploading file.';
          header("Location: profile.php");
          exit;
        }
    } else {
        $files = $oldimage;
    }

	$result = $user->updateProfile($files, $username, $phone, $email, $id);

	if($result == true){
		$_SESSION['msg'] = 'Profile has been updated Successfully ..';
	} else {
		$_SESSION['errmsg'] = 'Sorry Some Error !! Accurd ..';
  	}
    header("Location: profile.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

    <head>      
        <meta charset="utf-8" />
        <title>Looksabaya | Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">        
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="icon" type="image/x-icon" sizes="20x20" href="../assets/img/logo.png">    
         <!-- App css -->
         <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
         <link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
         <link href="css/app.min.css" rel="stylesheet" type="text/css" />
    </head>

    
    <!-- Top Bar Start -->
    <body>
        
    <?php include 'include/header.php'; ?>

    <?php include 'include/sidebar.php'; ?>

        <div class="page-wrapper">
            <!-- Page Content-->
            <div class="page-content">
                <div class="container-xxl">                  
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header p-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">     
                                            <?php if (empty($id)): ?>
                                                <h4 class="card-title">Admin Profile</h4> 
                                            <?php else: ?>
                                                <h4 class="card-title">Update Admin Profile</h4> 
                                            <?php endif; ?>                                   
                                        </div>
                                    </div>                                 
                                </div>
                                <div class="card-body d-flex justify-content-center">                                    
                                    <?php if (empty($id)): ?>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header pb-0">
                                                    <div class="col-lg-12 align-self-center mb-3">
                                                        <div class="d-flex align-items-center flex-row flex-wrap">
                                                            <div class="position-relative me-3">
                                                                <?php if(!empty($userRow['image'])){ ?>
                                                                    <img src="../adminuploads/user/<?php echo $userRow['image']; ?>" alt="" height="120" class="rounded-circle">
                                                                <?php }else{ ?>
                                                                    <img src="images/user-image.jpg" alt="" height="120" class="rounded-circle">
                                                                <?php } ?>
                                                                <a href="?id=<?php echo base64_encode($userRow['id']); ?>" 
                                                                    class="thumb-md justify-content-center d-flex align-items-center bg-primary text-white rounded-circle position-absolute end-0 bottom-0 border border-3 border-card-bg">
                                                                    <i class="fas fa-camera"></i>
                                                                </a>
                                                            </div>
                                                            <div class="">
                                                                <h5 class="fw-semibold fs-22 mb-1"><?php echo $userRow['username']; ?></h5>                                                        
                                                                <p class="mb-0 text-muted fw-medium">Master Admin</p>                                                        
                                                            </div>
                                                        </div>                                                
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col">                      
                                                            <h4 class="card-title">Personal Information</h4>                      
                                                        </div>
                                                        <div class="col-auto">                      
                                                            <a href="?id=<?php echo base64_encode($userRow['id']); ?>" 
                                                                class="float-end text-muted d-inline-flex text-decoration-underline">
                                                                <i class="iconoir-edit-pencil fs-18 me-1"></i>Edit
                                                            </a>                      
                                                        </div>
                                                    </div>                                
                                                </div>
                                                <div class="card-body pt-0">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mt-2"><i class="las la-phone me-2 text-secondary fs-22 align-middle"></i> 
                                                            <b> Phone </b> : <?php echo $userRow['phone']; ?>
                                                        </li>
                                                        <li class="mt-2"><i class="las la-envelope text-secondary fs-22 align-middle me-2"></i> 
                                                            <b> Email </b> : <?php echo $userRow['email']; ?>
                                                        </li>
                                                        <li class="mt-2"><i class="las la-globe text-secondary fs-22 align-middle me-2"></i> 
                                                            <b> Login IP </b> : <?php echo $userRow['login_ip']; ?>
                                                        </li>
                                                        <li class="mt-2"><i class="las la-calendar text-secondary fs-22 align-middle me-2"></i> 
                                                            <b> Login Date </b> : 
                                                            <?php 
                                                            $loginDate = new DateTime($userRow['login_date']);
                                                            $formattedLoginDate = $loginDate->format('j F, Y - g:i A'); 
                                                            echo $formattedLoginDate;
                                                            ?>
                                                        </li>
                                                        <li class="mt-2"><i class="las la-calendar-plus text-secondary fs-22 align-middle me-2"></i> 
                                                            <b> Created Date </b> : 
                                                            <?php 
                                                            $createdDate = new DateTime($userRow['created_at']);
                                                            $formattedCreatedDate = $createdDate->format('j F, Y - g:i A'); 
                                                            echo $formattedCreatedDate;
                                                            ?>
                                                        </li>
                                                        <li class="mt-2"><i class="las la-sync text-secondary fs-22 align-middle me-2"></i> 
                                                            <b> Updated Date </b> : 
                                                            <?php 
                                                            $updatedDate = new DateTime($userRow['updated_at']);
                                                            $formattedUpdatedDate = $updatedDate->format('j F, Y - g:i A'); 
                                                            echo $formattedUpdatedDate;
                                                            ?>
                                                        </li>
                                                    </ul>                                                           
                                                </div>
                                            </div>
                                        </div>  
                                    <?php else: ?> 
                                        <form id="user-form" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <div class="row mt-3 d-flex align-items-center">
                                                <div class="col-md-5">                                                    
                                                    <label class="form-label">Image</label>
                                                    <input name="image" type="file" class="form-control" id="image" onChange="PreviewImage();">    
                                                    <input type="hidden" name="oldimage" value="<?php echo $editval['image']; ?>" class="form-control">                                                
                                                </div>
                                                <div class="col-md-2">                                               
                                                    <img src="../adminuploads/user/<?php echo $editval['image']; ?>" id="uploadPreview" class="rounded-circle" style="height: 120px; ">                                                   
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                <label class="form-label" for="validationCustom02">User Name</label>
                                                <input name="username" type="text" class="form-control" placeholder="Enter User Name" id="validationCustom02" value="<?php echo $editval['username']; ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                <label class="form-label" for="validationCustom03">Phone</label>
                                                <input name="phone" type="tel" class="form-control" placeholder="Enter Phone Number" id="validationCustom03" value="<?php echo $editval['phone']; ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                <label class="form-label" for="validationCustom04">Email</label>
                                                <input name="email" type="email" class="form-control" placeholder="Enter Email" id="validationCustom04" value="<?php echo $editval['email']; ?>" required>
                                                </div>
                                            </div>
                                              

                                            <div class="col-12 m-0">
                                                <input type="submit" class="btn btn-primary login-btn" name="update" value="Update">
                                            </div>
                                        </form>   
                                    <?php endif; ?>                                                   
                                </div>
                            </div>
                        </div>                                                                           
                    </div>                    
                                      
                </div><!-- container -->
                
                <?php include 'include/footer.php'; ?>

            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- Javascript  -->  
        <!-- vendor js -->
        
        <script src="js/jquery-3.6.0.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="libs/simplebar/simplebar.min.js"></script>
        <script src="js/pages/form-validation.js"></script>
        <script src="libs/toastr/js/toastr.min.js"></script>
        <script src="js/toastr-init.js"></script>
        <script src="js/app.js"></script>

        <script>
            <?php if (isset($_SESSION['msg'])): ?>
                toastr.success("<?php echo $_SESSION['msg']; ?>");
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['errmsg'])): ?>
                toastr.error("<?php echo $_SESSION['errmsg']; ?>");
                <?php unset($_SESSION['errmsg']); ?>
            <?php endif; ?>
        </script>

        <script>
            function PreviewImage() 
            {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("image").files[0]);
                oFReader.onload = function(oFREvent) {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                };
            };    
        </script>

        <script>
            $(document).ready(function () {
                $("#user-form").validate({
                    rules: {
                        username: {
                            required: true,
                            maxlength: 50
                        },
                        phone: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10
                        },
                        email: {
                            required: true,
                            email: true,
                            maxlength: 100
                        }
                    },
                    messages: {
                        username: {
                            required: "Please enter username.",
                            maxlength: "User Name must not exceed 50 characters."
                        },
                        phone: {
                            required: "Please enter your phone number",
                            digits: "Please enter only digits",
                            minlength: "Please enter exactly 10 digits",
                            maxlength: "Please enter exactly 10 digits"
                        },
                        email: {
                            required: "Please enter your email",
                            email: "Please enter a valid email address",
                            maxlength: "Email cannot be more than 100 characters"
                        }
                    },
                    errorClass: "is-invalid", // Class for invalid fields
                    validClass: "is-valid", // Class for valid fields
                    highlight: function (element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function (error, element) {
                        // Remove any existing error message for this element
                        $(element).siblings(".invalid-feedback").remove();
                        // Append the error message to the element's parent
                        $(element).after(error);
                        // Apply red color to error message
                        $(error).css('color', 'red'); // Set error message color to red
                    },
                    submitHandler: function (form) {
                        form.submit(); // Submit the form when valid
                    }
                });

                // Instant validation for dropdowns
                $("select").change(function () {
                    if ($(this).valid()) {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                        $(this).siblings(".invalid-feedback").remove();
                    } else {
                        $(this).addClass("is-invalid");
                    }
                });
            });
        </script>    

    </body>
    <!--end body-->
</html>