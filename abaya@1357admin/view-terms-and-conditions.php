<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';
require 'functions/operations.php';
include 'fckeditor/fckeditor_php5.php';

$db = new dbClass();
$auth = new Authentication();
$Policy = new Policy();

$auth->checkSession();

$id = (isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '');
$editval = $Policy->getPolicy($id);

if (isset($_REQUEST['update'])) {
    $id = $_REQUEST['eId'];
    $description = $db->addStr($_POST['description']);
    
    // Server-side validation for description
    $errors = [];
    if (empty($description)) {
        $errors[] = 'Description is required.';
    } elseif (strlen(strip_tags($description)) < 10) {
        $errors[] = 'Description must be at least 10 characters long (excluding HTML tags).';
    // } elseif (strlen($description) > 10000) {
    //     $errors[] = 'Description cannot exceed 10,000 characters.';
    }

    if (empty($errors)) {
        $result = $Policy->updatePolicy($description, $id);
        
        if ($result === true) {
            $_SESSION['msg'] = 'Policy has been updated successfully.';
        } else {
            $_SESSION['errmsg'] = 'Sorry, some error occurred during update.';
        }
    } else {
        $_SESSION['errmsg'] = implode('<br>', $errors);
    }
    
    header("Location: view-terms-and-conditions.php");
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
        <link href="libs/simple-datatables/style.css" rel="stylesheet" type="text/css" />
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">         
                                            <?php if (empty($id)): ?>
                                                <h4 class="card-title">View Policy</h4> 
                                            <?php else: ?>
                                                <h4 class="card-title">Update Policy</h4> 
                                            <?php endif; ?>                                 
                                        </div>
                                    </div>                                
                                </div>
                                
                                <div class="card-body pt-0">   
                                    <?php if (!empty($id)): ?>
                                        <form id="Policy-form" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                            <input type="hidden" name="eId" value="<?php echo $id; ?>"> 
                                            
                                            <div class="row my-3">
                                                <div class="col-md-5">
                                                    <label class="form-label" for="validationCustom01">Title</label>
                                                    <input name="" type="text" class="form-control" placeholder="Title" id="validationCustom01" value="<?php echo $editval['title']; ?>" required readonly>
                                                </div>
                                            </div> 
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-10">
                                                    <label class="form-label" for="description">Description</label>
                                                    <?php
                                                        $shortid = $editval['description'];
                                                        $sBasePath = 'fckeditor/';
                                                        $oFCKeditor = new FCKeditor('description');
                                                        $oFCKeditor->BasePath = $sBasePath;
                                                        $oFCKeditor->Value = $shortid;
                                                        $oFCKeditor->Width = '100%';
                                                        $oFCKeditor->Height = '400';
                                                        $oFCKeditor->Create();
                                                    ?>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 m-0">
                                                <input type="submit" class="btn btn-primary login-btn" name="update" value="Update">
                                            </div>
                                        </form>

                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="datatable_1">
                                                <thead class="table-light">
                                                <tr>
                                                    <th width="5%" class="ps-0">S.No</th>
                                                    <th width="10%" class="ps-0">S.No</th>
                                                    <th width="64%">Description</th>                                       
                                                    <th width="1%"> </th>                                       
                                                    <th width="20%" class="text-end">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    $PolicyQuery = $Policy->allPolicy();
                                                    foreach ($PolicyQuery as $row) :
                                                    ?>
                                                        <tr>                                                        
                                                            <td width="5%"><?php echo $i++; ?></td>
                                                            <td width="10%"><?php echo $row['title']; ?></td>
                                                            <td width="64%"><?php echo substr($row['description'], 0, 2500); ?></td>
                                                            <td with="1%"> </td>
                                                            <td width="20%" class="text-end">                                                       
                                                                <a href="view-terms-and-conditions.php?id=<?php echo base64_encode($row['privacy_id']); ?>">
                                                                    <i class="las la-pen text-warning fs-18"></i>
                                                                </a>
                                                            </td>
                                                        </tr>   
                                                    <?php endforeach; ?>                                                                                    
                                                </tbody>
                                            </table>
                                        </div>
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
        <script src="libs/simple-datatables/umd/simple-datatables.js"></script>
        <script src="js/pages/datatable.init.js"></script>
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
            $(document).ready(function () {
                $("#Policy-form").validate({
                    rules: {
                        description: {
                            required: true,
                            minlength: 500,
                            // maxlength: 10000
                        }
                    },
                    messages: {
                        description: {
                            required: "Please enter a description.",
                            minlength: "Description must be at least 10 characters long.",
                            maxlength: "Description cannot exceed 10,000 characters."
                        }
                    },
                    errorClass: "is-invalid",
                    validClass: "is-valid",
                    highlight: function (element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                        // Update FCKeditor border
                        if (element.name === 'description') {
                            $('#cke_description').css('border', '1px solid red');
                        }
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                        // Reset FCKeditor border
                        if (element.name === 'description') {
                            $('#cke_description').css('border', '');
                        }
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr('name') === 'description') {
                            error.insertAfter(element.closest('.cke'));
                        } else {
                            error.insertAfter(element);
                        }
                        $(error).css('color', 'red');
                    },
                    submitHandler: function (form) {
                        // Get the FCKeditor content
                        var editor = FCKeditorAPI.GetInstance('description');
                        var content = editor.GetXHTML(true);
                        // Update the hidden textarea with editor content
                        $('textarea[name="description"]').val(content);
                        form.submit();
                    }
                });

                // Update editor validation on change
                if (typeof FCKeditorAPI !== 'undefined') {
                    var editor = FCKeditorAPI.GetInstance('description');
                    editor.Events.AttachEvent('OnAfterSetHTML', function() {
                        $("#Policy-form").validate().element('textarea[name="description"]');
                    });
                }
            });
        </script>
    </body>
</html>