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
$banner = new Banner();

$auth->checkSession();

$id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
$editval = $banner->getBanner($id);

// Insert record query
if (isset($_REQUEST['submit'])) {
    $status = $db->addStr($_POST['status'] ?? '1');
    $heading = $db->addStr($_POST['heading'] ?? '');
    $subheading = $db->addStr($_POST['subheading'] ?? '');
    $button_link = $db->addStr($_POST['button_link'] ?? '');

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $dest = "../adminUploads/banner/";
        $files = resize(1920, 700, $dest, $_FILES['image']['tmp_name'], $image);
    } else {
        $files = '0';
    }

    $result = $banner->addBanner($files, $status, $heading, $subheading, $button_link);

    if ($result === true) {
        $_SESSION['msg'] = 'Banner has been created successfully.';
        header("Location: view-banner.php");
        exit;
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred. in add';
        header("Location: add-banner.php");
        exit;
    }    
}

// Update record query
if(isset($_REQUEST['update'])) {
    $id = $_REQUEST['eId'];
    $status = $db->addStr($_POST['status']);
    $heading = $db->addStr($_POST['heading'] ?? '');
    $subheading = $db->addStr($_POST['subheading'] ?? '');
    $button_link = $db->addStr($_POST['button_link'] ?? '');
  
    $oldimage = $_POST['oldimage'];
    $dest = "../adminUploads/banner/";
  
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $image = $_FILES['image']['name'];
      $tmp_name = $_FILES['image']['tmp_name'];
      $files = resize(1920, 700, $dest, $tmp_name, $image);
  
      if ($files) {
        if (file_exists($dest . $oldimage)) {
          unlink($dest . $oldimage);
        }
      } else {
        $_SESSION['errmsg'] = 'Error uploading file.';
        header("Location: view-banner.php");
        exit;
      }
    } else {
      $files = $oldimage;
    }
  
    $result = $banner->updateBanner($files, $status, $id, $heading, $subheading, $button_link);
  
    if($result===true){
      $_SESSION['msg'] = "Banner has been updated successfully ..!!";
    } else {  
      $_SESSION['errmsg'] = "Sorry !! Some Error Occurred .. Try Again";  
    }
  
    header("Location: view-banner.php");
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

    <body>
    <?php include 'include/header.php'; ?>
    <?php include 'include/sidebar.php'; ?>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="container-xxl">                    
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header p-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">         
                                            <?php if (empty($id)): ?>
                                                <h4 class="card-title">Add Banner</h4> 
                                            <?php else: ?>
                                                <h4 class="card-title">Update Banner</h4> 
                                            <?php endif; ?>                                 
                                        </div>
                                    </div>                                 
                                </div>
                                <div class="card-body">
                                    <?php if (empty($id)): ?>
                                        <form id="banner-form1" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <div class="row mt-3 d-flex align-items-center">
                                                <div class="col-md-5">                                                    
                                                    <label class="form-label">Image (Size : 1920px*700px)</label>
                                                    <input name="image" type="file" class="form-control" id="image" onChange="PreviewImage();" required>                                                   
                                                </div>
                                                <div class="col-md-3">                                               
                                                    <img id="uploadPreview" style="height: 130px;">                                                   
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="heading">Heading</label>
                                                    <input name="heading" type="text" class="form-control" id="heading" value="" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="subheading">Subheading</label>
                                                    <input name="subheading" type="text" class="form-control" id="subheading" value="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="button_link">Button Link</label>
                                                    <input name="button_link" type="text" class="form-control" id="button_link" value="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom02">Status</label>
                                                    <select name="status" class="form-select" id="validationCustom02" required>
                                                        <option selected value="">Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>  

                                            <div class="col-12 m-0">
                                                <input type="submit" class="btn btn-primary login-btn" name="submit" value="Submit">
                                            </div>
                                        </form>   
                                    <?php else: ?> 
                                        <form id="banner-form2" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <input type="hidden" name="eId" value="<?php echo $id; ?>">  
                                            <div class="row mt-3 d-flex align-items-center">
                                                <div class="col-md-5">                                                    
                                                    <label class="form-label">Image (Size : 1920px*700px)</label>
                                                    <input name="image" type="file" class="form-control" id="image" onChange="PreviewImage();">  
                                                    <input type="hidden" name="oldimage" value="<?php echo $editval['image']; ?>" class="form-control">                                                 
                                                </div>
                                                <div class="col-md-3">                                               
                                                    <img src="../adminUploads/banner/<?php echo $editval['image']; ?>" id="uploadPreview" style="height: 130px;">                                                   
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="heading">Heading</label>
                                                    <input name="heading" type="text" class="form-control" id="heading" value="<?php echo htmlspecialchars($editval['heading'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="subheading">Subheading</label>
                                                    <input name="subheading" type="text" class="form-control" id="subheading" value="<?php echo htmlspecialchars($editval['subheading'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="button_link">Button Link</label>
                                                    <input name="button_link" type="text" class="form-control" id="button_link" value="<?php echo htmlspecialchars($editval['button_link'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom02">Status</label>
                                                    <select name="status" class="form-select" id="validationCustom02" required>
                                                        <option value="">Select Status</option>
                                                        <option value="1" <?php if ($editval['status'] == "1") echo 'selected' ?>>Active</option>
                                                        <option value="0" <?php if ($editval['status'] == "0") echo 'selected' ?>>Inactive</option>
                                                    </select>
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
                </div>
                
                <?php include 'include/footer.php'; ?>
            </div>
        </div>

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
                $("#banner-form1, #banner-form2").validate({
                    rules: {
                        image: {
                            required: function(element) {
                                return $(element).closest('form').attr('id') === 'banner-form1';
                            }
                        },
                        heading: {
                            required: true
                        },
                        subheading: {
                            required: false
                        },
                        button_link: {
                            required: false
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        image: {
                            required: "Please select an image.",
                        },
                        heading: {
                            required: "Please enter a heading."
                        },
                        status: {
                            required: "Please select status."
                        }
                    },
                    errorClass: "is-invalid",
                    validClass: "is-valid",
                    highlight: function (element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function (error, element) {
                        $(element).siblings(".invalid-feedback").remove();
                        $(element).after(error);
                        $(error).css('color', 'red');
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

                $("select, input").change(function () {
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
</html>