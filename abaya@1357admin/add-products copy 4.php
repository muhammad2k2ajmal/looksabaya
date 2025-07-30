<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';
require 'functions/operations.php';

$db = new dbClass();
$auth = new Authentication();
$admin = new Categories();
$products = new Products();

$auth->checkSession();

$id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
if ($id) {
    $editval = $products->getProducts($id);
    $imageVal = $products->getProductImages($id);
}

$allColors = $products->getallColor();
$allCategories = $admin->allCategories();

// Insert record
if (isset($_REQUEST['submit'])) {
    $category_id = $db->addStr($_POST['category_id'] ?? '');
    $name = $db->addStr($_POST['name'] ?? '');
    $price = $db->addStr($_POST['price'] ?? '0');
    $discount = $db->addStr($_POST['discount'] ?? '0');
    $stock = $db->addStr($_POST['stock'] ?? '0');
    $weight = $db->addStr($_POST['weight'] ?? '0.00');
    $length = $db->addStr($_POST['length'] ?? '0.00');
    $width = $db->addStr($_POST['width'] ?? '0.00');
    $height = $db->addStr($_POST['height'] ?? '0.00');
    $composition = $db->addStr($_POST['composition'] ?? '');
    $sizes = !empty($_POST['sizes']) && is_array($_POST['sizes']) ? $_POST['sizes'] : [];
    $description = $db->addStr($_POST['description'] ?? '');
    $lists = !empty($_POST['lists']) && is_array($_POST['lists']) ? array_filter($_POST['lists'], 'trim') : [];
    $delivery_options = !empty($_POST['delivery_options']) && is_array($_POST['delivery_options']) ? array_filter($_POST['delivery_options'], function($option) {
        return !empty(trim($option['location'])) && !empty(trim($option['type'])) && !empty(trim($option['time'])) && !empty(trim($option['cost']));
    }) : [];
    $trending = $db->addStr($_POST['trending'] ?? '0');
    $new_arrivals = $db->addStr($_POST['new_arrivals'] ?? '0');
    $best_selling = $db->addStr($_POST['best_selling'] ?? '0');
    $status = $db->addStr($_POST['status'] ?? '1');
    $colors = !empty($_POST['colors']) && is_array($_POST['colors']) ? $_POST['colors'] : [];
    $color_images = [];

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $dest = "../adminuploads/products/";
        $targetPath = $dest . basename($image);

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $files = $image; // Success
        } else {
            $files = '0'; // Move failed
        }
    } else {
        $files = '0'; // Upload error
    }
    if ($_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video = $_FILES['video']['name'];
        $videodest = "../adminuploads/videos/";
        $videotargetPath = $videodest . basename($video);

        // Move uploaded file
        if (move_uploaded_file($_FILES['video']['tmp_name'], $videotargetPath)) {
            $videofiles = $video; // Success
        } else {
            $videofiles = '0'; // Move failed
        }
    } else {
        $videofiles = '0'; // Upload error
    }

    // Validate and upload color images (minimum 3, maximum 5 per color)
    foreach ($colors as $color_id) {
        if (isset($_FILES['color_images_' . $color_id]) && !empty($_FILES['color_images_' . $color_id]['name'][0])) {
            $color_images[$color_id] = [];
            foreach ($_FILES['color_images_' . $color_id]['name'] as $key => $imageName) {
                if ($_FILES['color_images_' . $color_id]['error'][$key] === UPLOAD_ERR_OK && count($color_images[$color_id]) < 5) {
                    $tempName = $_FILES['color_images_' . $color_id]['tmp_name'][$key];
                    $destPath = "../adminUploads/products/";
                    $uniqueName = uniqid() . '_' . basename($imageName);
                    $targetFile = $destPath . $uniqueName;

                    // Ensure the directory exists
                    if (!is_dir($destPath)) {
                        mkdir($destPath, 0755, true);
                    }

                    // Move the uploaded file
                    if (move_uploaded_file($tempName, $targetFile)) {
                        $color_images[$color_id][] = $uniqueName;
                    } else {
                        $_SESSION['errmsg'] = "Failed to upload image: $imageName for color ID $color_id.";
                        header("Location: add-products.php");
                        exit;
                    }
                }
            }
            if (count($color_images[$color_id]) < 3) {
                $_SESSION['errmsg'] = "Please upload at least 3 images for color ID $color_id.";
                header("Location: add-products.php");
                exit;
            }
        } else {
            $_SESSION['errmsg'] = "Please upload at least 3 images for color ID $color_id.";
            header("Location: add-products.php");
            exit;
        }
    }

    $result = $products->addProducts($category_id, $name, $price, $discount, $stock, $weight, $length, $width, $height, $composition, $sizes, $files, $videofiles, $description, $lists, $delivery_options, $trending, $new_arrivals, $best_selling, $status, $colors, $color_images);

    if ($result) {
        $_SESSION['msg'] = 'Product has been created successfully.';
        header("Location: view-products.php");
        exit;
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred.';
        header("Location: add-products.php");
        exit;
    }
}

// Update record
if (isset($_REQUEST['update'])) {
    $id = $_POST['id'];
    $category_id = $db->addStr($_POST['category_id'] ?? '');
    $name = $db->addStr($_POST['name'] ?? '');
    $price = $db->addStr($_POST['price'] ?? '0');
    $discount = $db->addStr($_POST['discount'] ?? '0');
    $stock = $db->addStr($_POST['stock'] ?? '0');
    $weight = $db->addStr($_POST['weight'] ?? '0.00');
    $length = $db->addStr($_POST['length'] ?? '0.00');
    $width = $db->addStr($_POST['width'] ?? '0.00');
    $height = $db->addStr($_POST['height'] ?? '0.00');
    $composition = $db->addStr($_POST['composition'] ?? '');
    $sizes = !empty($_POST['sizes']) && is_array($_POST['sizes']) ? $_POST['sizes'] : [];
    $description = $db->addStr($_POST['description'] ?? '');
    $lists = !empty($_POST['lists']) && is_array($_POST['lists']) ? array_filter($_POST['lists'], 'trim') : [];
    $delivery_options = !empty($_POST['delivery_options']) && is_array($_POST['delivery_options']) ? array_filter($_POST['delivery_options'], function($option) {
        return !empty(trim($option['location'])) && !empty(trim($option['type'])) && !empty(trim($option['time'])) && !empty(trim($option['cost']));
    }) : [];
    $trending = $db->addStr($_POST['trending'] ?? '0');
    $new_arrivals = $db->addStr($_POST['new_arrivals'] ?? '0');
    $best_selling = $db->addStr($_POST['best_selling'] ?? '0');
    $status = $db->addStr($_POST['status'] ?? '1');
    $colors = !empty($_POST['colors']) && is_array($_POST['colors']) ? $_POST['colors'] : [];
    $color_images = [];

    $oldimage = $_POST['oldimage'];
    $dest = "../adminUploads/products/";

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $targetPath = $dest . basename($image);

        // Move the uploaded file
        if (move_uploaded_file($tmp_name, $targetPath)) {
            // Delete old image if it exists and is not the same as new
            if ($oldimage && file_exists($dest . $oldimage) && $oldimage !== $image) {
                unlink($dest . $oldimage);
            }
            $files = $image;
        } else {
            $_SESSION['errmsg'] = 'Error uploading file.';
            header("Location: view-products.php");
            exit;
        }
    } else {
        // If no new file uploaded, retain old image
        $files = $oldimage;
    }
    $oldvideo = $_POST['oldvideo'];
    $videodest = "../adminUploads/videos/";

    if ($_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video = $_FILES['video']['name'];
        $tmp_name = $_FILES['video']['tmp_name'];
        $videotargetPath = $videodest . basename($video);

        // Move the uploaded file
        if (move_uploaded_file($tmp_name, $videotargetPath)) {
            // Delete old video if it exists and is different from the new one
            if ($oldvideo && file_exists($videodest . $oldvideo) && $oldvideo !== $video) {
                unlink($videodest . $oldvideo);
            }
            $videofiles = $video;
        } else {
            $_SESSION['errmsg'] = 'Error uploading video.';
            header("Location: view-products.php");
            exit;
        }
    } else {
        // No new file uploaded, keep old video
        $videofiles = $oldvideo;
    }

    // Validate and upload color images (minimum 3, maximum 5 per color, including existing images)
    foreach ($colors as $color_id) {
        $existingImages = $products->getProductImagesByColor($id, $color_id);
        $existingImageNames = array_column($existingImages, 'image');
        $color_images[$color_id] = $existingImageNames;
        $totalImages = count($existingImageNames);

        if (isset($_FILES['color_images_' . $color_id]) && !empty($_FILES['color_images_' . $color_id]['name'][0])) {
            foreach ($_FILES['color_images_' . $color_id]['name'] as $key => $imageName) {
                if ($_FILES['color_images_' . $color_id]['error'][$key] === UPLOAD_ERR_OK && $totalImages < 5) {
                    $tempName = $_FILES['color_images_' . $color_id]['tmp_name'][$key];
                    $destPath = "../adminUploads/products/";
                    $uniqueName = uniqid() . '_' . basename($imageName);
                    $targetFile = $destPath . $uniqueName;

                    // Ensure the directory exists
                    if (!is_dir($destPath)) {
                        mkdir($destPath, 0755, true);
                    }

                    // Move the uploaded file
                    if (move_uploaded_file($tempName, $targetFile)) {
                        $color_images[$color_id][] = $uniqueName;
                        $totalImages++;
                    } else {
                        $_SESSION['errmsg'] = "Failed to upload image: $imageName for color ID $color_id.";
                        header("Location: add-products.php?id=" . base64_encode($id));
                        exit;
                    }
                }
            }
        }

        if ($totalImages < 3) {
            $_SESSION['errmsg'] = "Please ensure at least 3 images for color ID $color_id (currently $totalImages).";
            header("Location: add-products.php?id=" . base64_encode($id));
            exit;
        }
    }

    $result = $products->updateProducts($id, $category_id, $name, $price, $discount, $stock, $weight, $length, $width, $height, $composition, $sizes, $files, $videofiles, $description, $lists, $delivery_options, $trending, $new_arrivals, $best_selling, $status, $colors, $color_images);

    if ($result) {
        $_SESSION['msg'] = 'Product has been updated successfully.';
        header("Location: view-products.php");
        exit;
    } else {
        $_SESSION['errmsg'] = 'Sorry, some error occurred.';
        header("Location: view-products.php");
        exit;
    }
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
    <link rel="icon" type="image/x-icon" sizes="20x20" href="../assets/img/logo.png">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .color-swatch {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
            border: 1px solid #ccc;
        }
        .color-image-container {
            display: none;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .color-image-container.active {
            display: block;
        }
        .image-preview {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .image-preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-preview-item .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            cursor: pointer;
            font-size: 12px;
        }
        .image-upload-row {
            margin-bottom: 10px;
        }
        .image-count {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
    </style>
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
                                        <h4 class="card-title">Add Product</h4>
                                    <?php else: ?>
                                        <h4 class="card-title">Update Product</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($id)): ?>
                                <form id="product-form1" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="category_id">Category</label>
                                            <select name="category_id" class="form-select" id="category_id" required>
                                                <option value="" disabled selected>Select Category</option>
                                                <?php foreach ($allCategories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="price">Price</label>
                                            <input name="price" type="number" class="form-control" id="price" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="discount">Discount (%)</label>
                                            <input name="discount" type="number" class="form-control" id="discount">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="stock">Stock</label>
                                            <input name="stock" type="number" class="form-control" id="stock" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="weight">Weight (kg)</label>
                                            <input name="weight" type="number" step="0.01" class="form-control" id="weight" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="length">Length (cm)</label>
                                            <input name="length" type="number" step="0.01" class="form-control" id="length" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="width">Width (cm)</label>
                                            <input name="width" type="number" step="0.01" class="form-control" id="width" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="height">Height (cm)</label>
                                            <input name="height" type="number" step="0.01" class="form-control" id="height" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="composition">Composition</label>
                                            <input name="composition" type="text" class="form-control" id="composition" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3 d-flex align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label">Image (Size: 1000px * 1000px)</label>
                                            <input name="image" type="file" class="form-control" id="image" onChange="PreviewImage();" required>
                                        </div>
                                        <div class="col-md-3">
                                            <img id="uploadPreview" style="height: 130px;">
                                        </div>
                                    </div>
                                    <div class="row mb-3 d-flex align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label">Video (Format: MP4, WebM)</label>
                                            <input name="video" type="file" class="form-control" id="video" accept="video/mp4,video/webm" required>
                                        </div>
                                        <div class="col-md-3">
                                            <video id="videoPreview" controls style="height: 130px; display: none;"></video>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Sizes</label>
                                            <div class="checkbox">
                                                <?php foreach ([52, 54, 56, 58] as $size): ?>
                                                    <label class="me-3">
                                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>"> <?php echo $size; ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-10">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3 field_wrapper">
                                        <div class="col-md-5">
                                            <label class="form-label" for="lists">List Items</label>
                                            <input name="lists[]" type="text" class="form-control" placeholder="Enter list item">
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0);" class="btn btn-success add_button" title="Add field">Add More</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 delivery_wrapper">
                                        <div class="col-md-10">
                                            <label class="form-label" for="delivery_options">Delivery Options</label>
                                            <div class="row mb-2">
                                                <div class="col-md-3">
                                                    <input name="delivery_options[0][location]" type="text" class="form-control" placeholder="Location (e.g., India)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input name="delivery_options[0][type]" type="text" class="form-control" placeholder="Type (e.g., First Class)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input name="delivery_options[0][time]" type="text" class="form-control" placeholder="Time (e.g., 2-3 working days)">
                                                </div>
                                                <div class="col-md-2">
                                                    <input name="delivery_options[0][cost]" type="number" step="0.01" class="form-control" placeholder="Cost (e.g., 500.00)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-success add_delivery_button" title="Add delivery option">Add More</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="trending" value="1"> Trending
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="new_arrivals" value="1"> New Arrivals
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="best_selling" value="1"> Best Selling
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="status">Status</label>
                                            <select name="status" class="form-select" id="status" required>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="colors">Colors</label>
                                            <select name="colors[]" class="form-select select2" id="colors" multiple required>
                                                <?php foreach ($allColors as $color): ?>
                                                    <option value="<?php echo $color['id']; ?>" data-color-code="<?php echo htmlspecialchars($color['color_code']); ?>">
                                                        <?php echo htmlspecialchars($color['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="color-images-container">
                                        <!-- Dynamically generated image upload fields for each selected color -->
                                    </div>
                                    <div class="col-12 m-0">
                                        <input type="submit" class="btn btn-primary login-btn" name="submit" value="Submit">
                                    </div>
                                </form>
                            <?php else: ?>
                                <form id="product-form2" method="post" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="category_id">Category</label>
                                            <select name="category_id" class="form-select" id="category_id" required>
                                                <option value="" disabled>Select Category</option>
                                                <?php foreach ($allCategories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $editval['category_id']) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($editval['name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="price">Price</label>
                                            <input name="price" type="number" class="form-control" id="price" value="<?php echo htmlspecialchars($editval['price']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="discount">Discount (%)</label>
                                            <input name="discount" type="number" class="form-control" id="discount" value="<?php echo htmlspecialchars($editval['discount']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="stock">Stock</label>
                                            <input name="stock" type="number" class="form-control" id="stock" value="<?php echo htmlspecialchars($editval['stock']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="weight">Weight (kg)</label>
                                            <input name="weight" type="number" step="0.01" class="form-control" id="weight" value="<?php echo htmlspecialchars($editval['weight']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="length">Length (cm)</label>
                                            <input name="length" type="number" step="0.01" class="form-control" id="length" value="<?php echo htmlspecialchars($editval['length']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="width">Width (cm)</label>
                                            <input name="width" type="number" step="0.01" class="form-control" id="width" value="<?php echo htmlspecialchars($editval['width']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="height">Height (cm)</label>
                                            <input name="height" type="number" step="0.01" class="form-control" id="height" value="<?php echo htmlspecialchars($editval['height']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="composition">Composition</label>
                                            <input name="composition" type="text" class="form-control" id="composition" value="<?php echo htmlspecialchars($editval['composition']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3 d-flex align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label">Image (Size: 1000px * 1000px)</label>
                                            <input name="image" type="file" class="form-control" id="image" onChange="PreviewImage();">
                                            <input type="hidden" name="oldimage" value="<?php echo $editval['image']; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <img src="../adminUploads/products/<?php echo $editval['image']; ?>" id="uploadPreview" style="height: 130px;">
                                        </div>
                                    </div>
                                    <div class="row mb-3 d-flex align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label">Video (Format: MP4, WebM)</label>
                                            <input name="video" type="file" class="form-control" id="video" accept="video/mp4,video/webm">
                                            <input type="hidden" name="oldvideo" value="<?php echo $editval['video']; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <video id="videoPreview" src="../adminUploads/videos/<?php echo $editval['video']; ?>" controls style="height: 130px; <?php echo empty($editval['video']) ? 'display:none;' : ''; ?>"></video>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Sizes</label>
                                            <div class="checkbox">
                                                <?php foreach ([52, 54, 56, 58] as $size): ?>
                                                    <label class="me-3">
                                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>" <?php if (in_array($size, $editval['sizes'])) echo 'checked'; ?>>
                                                        <?php echo $size; ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-10">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="5" required><?php echo htmlspecialchars($editval['description']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3 field_wrapper">
                                        <div class="col-md-5">
                                            <label class="form-label" for="lists">List Items</label>
                                            <?php foreach ($editval['lists'] as $list): ?>
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <input name="lists[]" type="text" class="form-control" value="<?php echo htmlspecialchars($list); ?>">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <input name="lists[]" type="text" class="form-control" placeholder="Enter list item">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0);" class="btn btn-success add_button" title="Add field">Add More</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 delivery_wrapper">
                                        <div class="col-md-10">
                                            <label class="form-label" for="delivery_options">Delivery Options</label>
                                            <?php
                                            $deliveryOptions = $products->getDeliveryOptions($id);
                                            foreach ($deliveryOptions as $index => $option):
                                            ?>
                                                <div class="row mb-2 delivery_option_row">
                                                    <div class="col-md-3">
                                                        <input name="delivery_options[<?php echo $index; ?>][location]" type="text" class="form-control" value="<?php echo htmlspecialchars($option['delivery_location']); ?>" placeholder="Location (e.g., India)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input name="delivery_options[<?php echo $index; ?>][type]" type="text" class="form-control" value="<?php echo htmlspecialchars($option['delivery_type']); ?>" placeholder="Type (e.g., First Class)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input name="delivery_options[<?php echo $index; ?>][time]" type="text" class="form-control" value="<?php echo htmlspecialchars($option['delivery_time']); ?>" placeholder="Time (e.g., 2-3 working days)">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input name="delivery_options[<?php echo $index; ?>][cost]" type="number" step="0.01" class="form-control" value="<?php echo htmlspecialchars($option['cost']); ?>" placeholder="Cost (e.g., 500.00)">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <a href="javascript:void(0);" class="btn btn-danger remove_delivery_button" title="Remove delivery option">Remove</a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="row mb-2 delivery_option_row">
                                                <div class="col-md-3">
                                                    <input name="delivery_options[<?php echo count($deliveryOptions); ?>][location]" type="text" class="form-control" placeholder="Location (e.g., India)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input name="delivery_options[<?php echo count($deliveryOptions); ?>][type]" type="text" class="form-control" placeholder="Type (e.g., First Class)">
                                                </div>
                                                <div class="col-md-3">
                                                    <input name="delivery_options[<?php echo count($deliveryOptions); ?>][time]" type="text" class="form-control" placeholder="Time (e.g., 2-3 working days)">
                                                </div>
                                                <div class="col-md-2">
                                                    <input name="delivery_options[<?php echo count($deliveryOptions); ?>][cost]" type="number" step="0.01" class="form-control" placeholder="Cost (e.g., 500.00)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0);" class="btn btn-success add_delivery_button" title="Add delivery option">Add More</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="trending" value="1" <?php if ($editval['trending'] == 1) echo 'checked'; ?>> Trending
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="new_arrivals" value="1" <?php if ($editval['new_arrivals'] == 1) echo 'checked'; ?>> New Arrivals
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="best_selling" value="1" <?php if ($editval['best_selling'] == 1) echo 'checked'; ?>> Best Selling
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="status">Status</label>
                                            <select name="status" class="form-select" id="status" required>
                                                <option value="1" <?php if ($editval['status'] == 1) echo 'selected'; ?>>Active</option>
                                                <option value="0" <?php if ($editval['status'] == 0) echo 'selected'; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label" for="colors">Colors</label>
                                            <select name="colors[]" class="form-select select2" id="colors" multiple required>
                                                <?php foreach ($allColors as $color): ?>
                                                    <option value="<?php echo $color['id']; ?>" data-color-code="<?php echo htmlspecialchars($color['color_code']); ?>" <?php if (in_array($color['id'], $editval['colors'])) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($color['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="color-images-container">
                                        <?php
                                        // Merge color IDs from both sources
                                        $colorIds = array_unique(array_merge(
                                            isset($editval['colors']) ? $editval['colors'] : [],
                                            array_column($imageVal, 'color_id')
                                        ));

                                        // Group images by color_id
                                        $groupedImages = [];
                                        foreach ($imageVal as $imageRow) {
                                            $groupedImages[$imageRow['color_id']][] = $imageRow;
                                        }

                                        foreach ($colorIds as $color_id):
                                            $color = $db->getData("SELECT name, color_code FROM color WHERE id = '$color_id'");
                                            $images = isset($groupedImages[$color_id]) ? $groupedImages[$color_id] : [];
                                            $imageCount = count($images);
                                        ?>
                                            <div class="color-image-container active" data-color-id="<?php echo $color_id; ?>">
                                                <h5>
                                                    Images for <?php echo htmlspecialchars($color['name']); ?>
                                                    (<span style="background-color: <?php echo htmlspecialchars($color['color_code']); ?>; width: 20px; height: 20px; display: inline-block; vertical-align: middle; border: 1px solid #ccc;"></span>)
                                                </h5>
                                                <div class="image-upload-row">
                                                    <input type="file" name="color_images_<?php echo $color_id; ?>[]" class="form-control color-image-input" multiple accept="image/*" data-max-files="<?php echo max(0, 5 - $imageCount); ?>">
                                                </div>
                                                <div class="image-preview" data-color-id="<?php echo $color_id; ?>">
                                                    <?php foreach ($images as $image): ?>
                                                        <div class="image-preview-item" data-image-id="<?php echo $image['image_id']; ?>">
                                                            <a href="../adminUploads/products/<?php echo htmlspecialchars($image['image']); ?>" target="_blank">
                                                                <img src="../adminUploads/products/<?php echo htmlspecialchars($image['image']); ?>" alt="Preview">
                                                            </a>
                                                            <button type="button" class="remove-image" data-existing="true" data-image-id="<?php echo $image['image_id']; ?>">X</button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="image-count">
                                                    Current images: <span class="image-count-value"><?php echo $imageCount; ?></span> (Minimum 3, Maximum 5)
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
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

    <script src="js/jquery-3.6.0.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="libs/simplebar/simplebar.min.js"></script>
    <script src="js/pages/form-validation.js"></script>
    <script src="libs/toastr/js/toastr.min.js"></script>
    <script src="js/toastr-init.js"></script>
    <script src="js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        <?php if (isset($_SESSION['msg'])): ?>
            toastr.success("<?php echo $_SESSION['msg']; ?>");
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errmsg'])): ?>
            toastr.error("<?php echo $_SESSION['errmsg']; ?>");
            <?php unset($_SESSION['errmsg']); ?>
        <?php endif; ?>

        $(document).ready(function () {
            // Initialize Select2 for colors
            $('#colors').select2({
                placeholder: "Select Colors",
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) return data.text;
                    var $option = $('<span><span class="color-swatch" style="background-color: ' + $(data.element).data('color-code') + ';"></span>' + data.text + '</span>');
                    return $option;
                },
                templateSelection: function (data) {
                    if (!data.id) return data.text;
                    var $option = $('<span><span class="color-swatch" style="background-color: ' + $(data.element).data('color-code') + ';"></span>' + data.text + '</span>');
                    return $option;
                }
            });

            // Handle color selection change
            $('#colors').on('change', function () {
                var selectedColors = $(this).val() || [];
                var $container = $('#color-images-container');
                $container.empty();

                selectedColors.forEach(function (colorId) {
                    var colorName = $('#colors option[value="' + colorId + '"]').text();
                    var colorCode = $('#colors option[value="' + colorId + '"]').data('color-code');
                    var existingImages = <?php echo !empty($id) ? json_encode(array_reduce($imageVal, function($carry, $item) {
                        $carry[$item['color_id']][] = $item;
                        return $carry;
                    }, [])) : '{}'; ?>;
                    var imageCount = (existingImages[colorId] || []).length;
                    var maxFiles = Math.max(0, 5 - imageCount);
                    var html = '<div class="color-image-container active" data-color-id="' + colorId + '">' +
                        '<h5>Images for ' + colorName + ' <span style="background-color: ' + colorCode + '; width: 20px; height: 20px; display: inline-block; vertical-align: middle; border: 1px solid #ccc;"></span></h5>' +
                        '<div class="image-upload-row">' +
                        '<input type="file" name="color_images_' + colorId + '[]" class="form-control color-image-input" multiple accept="image/*" data-max-files="' + maxFiles + '">' +
                        '</div>' +
                        '<div class="image-preview" data-color-id="' + colorId + '">';
                    if (existingImages[colorId]) {
                        existingImages[colorId].forEach(function (image) {
                            html += '<div class="image-preview-item" data-image-id="' + image.image_id + '">' +
                                '<a href="../adminUploads/products/' + image.image + '" target="_blank">' +
                                '<img src="../adminUploads/products/' + image.image + '" alt="Preview">' +
                                '</a>' +
                                '<button type="button" class="remove-image" data-existing="true" data-image-id="' + image.image_id + '">X</button>' +
                                '</div>';
                        });
                    }
                    html += '</div>' +
                        '<div class="image-count">Current images: <span class="image-count-value">' + imageCount + '</span> (Minimum 3, Maximum 5)</div>' +
                        '</div>';
                    $container.append(html);
                });

                // Reattach event listeners for new inputs
                attachImagePreviewListeners();
            });

            // Function to attach image preview listeners
            function attachImagePreviewListeners() {
                $('.color-image-input').off('change').on('change', function () {
                    var $input = $(this);
                    var colorId = $input.closest('.color-image-container').data('color-id');
                    var $previewContainer = $input.closest('.color-image-container').find('.image-preview[data-color-id="' + colorId + '"]');
                    var $imageCountSpan = $input.closest('.color-image-container').find('.image-count-value');
                    var maxFiles = parseInt($input.data('max-files'));
                    var currentImages = parseInt($imageCountSpan.text());
                    var files = $input[0].files;

                    if (currentImages + files.length > 5) {
                        toastr.error('You can upload a maximum of 5 images per color.');
                        $input.val('');
                        return;
                    }

                    if (files.length > maxFiles) {
                        toastr.error('You can only upload ' + maxFiles + ' more image(s) for this color.');
                        $input.val('');
                        return;
                    }

                    $.each(files, function (index, file) {
                        if (file.type.match('image.*')) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var $previewItem = $('<div class="image-preview-item">' +
                                    '<img src="' + e.target.result + '" alt="Preview">' +
                                    '<button type="button" class="remove-image">X</button>' +
                                    '</div>');
                                $previewContainer.append($previewItem);
                                $imageCountSpan.text(parseInt($imageCountSpan.text()) + 1);
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    // Update the input to ensure only the latest files are submitted
                    var dataTransfer = new DataTransfer();
                    $.each(files, function (index, file) {
                        dataTransfer.items.add(file);
                    });
                    $input[0].files = dataTransfer.files;
                });

                // Handle image removal
                $(document).off('click', '.remove-image').on('click', '.remove-image', function () {
                    var $previewItem = $(this).closest('.image-preview-item');
                    var $previewContainer = $previewItem.closest('.image-preview');
                    var colorId = $previewContainer.data('color-id');
                    var $input = $previewContainer.siblings('.image-upload-row').find('input[name="color_images_' + colorId + '[]"]');
                    var $imageCountSpan = $previewContainer.siblings('.image-count').find('.image-count-value');
                    var isExisting = $(this).data('existing');
                    var imageId = $(this).data('image-id');

                    if (isExisting && imageId) {
                        // Remove from database via AJAX
                        if (confirm('Are you sure to remove this image?')) {
                            $.ajax({
                                url: 'get-values.php',
                                type: 'GET',
                                data: { deleteImages: 'deleteImages', image_id: imageId },
                                success: function () {
                                    $previewItem.remove();
                                    $imageCountSpan.text(parseInt($imageCountSpan.text()) - 1);
                                    $input.attr('data-max-files', parseInt($input.attr('data-max-files')) + 1);
                                    toastr.success('Image deleted successfully.');
                                }
                            });
                        }
                    } else {
                        // Update the file input to remove the file
                        var files = Array.from($input[0].files);
                        var index = $previewContainer.find('.image-preview-item').index($previewItem);
                        files.splice(index, 1);
                        var dataTransfer = new DataTransfer();
                        files.forEach(function (file) {
                            dataTransfer.items.add(file);
                        });
                        $input[0].files = dataTransfer.files;
                        $previewItem.remove();
                        $imageCountSpan.text(parseInt($imageCountSpan.text()) - 1);
                        $input.attr('data-max-files', parseInt($input.attr('data-max-files')) + 1);
                    }
                });
            }

            // Add more list items
            var maxListFields = 10;
            var addListButton = $('.add_button');
            var wrapper = $('.field_wrapper');
            var fieldHTML = '<div class="row mb-2"><div class="col-md-5"><input name="lists[]" type="text" class="form-control" placeholder="Enter list item"></div><div class="col-md-3"><a href="javascript:void(0);" class="btn btn-danger remove_button" title="Remove field">Remove</a></div></div>';

            var x = <?php echo empty($id) ? 1 : count($editval['lists']) + 1; ?>;
            $(addListButton).click(function () {
                if (x < maxListFields) {
                    x++;
                    $(wrapper).append(fieldHTML);
                }
            });

            $(wrapper).on('click', '.remove_button', function (e) {
                e.preventDefault();
                $(this).closest('.row').remove();
                x--;
            });

            // Add more delivery options
            var maxDeliveryFields = 10;
            var addDeliveryButton = $('.add_delivery_button');
            var deliveryWrapper = $('.delivery_wrapper');
            var deliveryFieldHTML = '<div class="row mb-2 delivery_option_row">' +
                '<div class="col-md-3"><input name="delivery_options[INDEX][location]" type="text" class="form-control" placeholder="Location (e.g., India)"></div>' +
                '<div class="col-md-3"><input name="delivery_options[INDEX][type]" type="text" class="form-control" placeholder="Type (e.g., First Class)"></div>' +
                '<div class="col-md-3"><input name="delivery_options[INDEX][time]" type="text" class="form-control" placeholder="Time (e.g., 2-3 working days)"></div>' +
                '<div class="col-md-2"><input name="delivery_options[INDEX][cost]" type="number" step="0.01" class="form-control" placeholder="Cost (e.g., 500.00)"></div>' +
                '<div class="col-md-1"><a href="javascript:void(0);" class="btn btn-danger remove_delivery_button" title="Remove delivery option">Remove</a></div>' +
                '</div>';

            var y = <?php echo empty($id) ? 1 : count($deliveryOptions) + 1; ?>;
            $(addDeliveryButton).click(function () {
                if (y < maxDeliveryFields) {
                    var newFieldHTML = deliveryFieldHTML.replace(/INDEX/g, y);
                    $(deliveryWrapper).find('.delivery_option_row').last().after(newFieldHTML);
                    y++;
                }
            });

            $(deliveryWrapper).on('click', '.remove_delivery_button', function (e) {
                e.preventDefault();
                $(this).closest('.delivery_option_row').remove();
                y--;
            });

            // Form validation
            $("#product-form1, #product-form2").validate({
                rules: {
                    category_id: { required: true },
                    name: { required: true, maxlength: 200 },
                    price: { required: true },
                    stock: { required: true },
                    weight: { required: true },
                    length: { required: true },
                    width: { required: true },
                    height: { required: true },
                    composition: { required: true },
                    description: { required: true },
                    status: { required: true },
                    'colors[]': { required: true },
                    'sizes[]': { required: true },
                    'delivery_options[0][location]': { required: true },
                    'delivery_options[0][type]': { required: true },
                    'delivery_options[0][time]': { required: true },
                    'delivery_options[0][cost]': { required: true }
                },
                messages: {
                    category_id: "Please select a category.",
                    name: {
                        required: "Please enter product name.",
                        maxlength: "Product name cannot be more than 200 characters."
                    },
                    price: "Please enter price.",
                    stock: "Please enter stock.",
                    weight: "Please enter weight.",
                    length: "Please enter length.",
                    width: "Please enter width.",
                    height: "Please enter height.",
                    composition: "Please enter composition.",
                    description: "Please enter description.",
                    status: "Please select a status.",
                    'colors[]': "Please select at least one color.",
                    'sizes[]': "Please select at least one size.",
                    'delivery_options[0][location]': "Please enter delivery location.",
                    'delivery_options[0][type]': "Please enter delivery type.",
                    'delivery_options[0][time]': "Please enter delivery time.",
                    'delivery_options[0][cost]': "Please enter delivery cost."
                },
                errorClass: "is-invalid",
                validClass: "is-valid",
                highlight: function (element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function (element, error) {
                    $(element).siblings(".invalid-feedback").remove();
                    $(element).after(error);
                    $(error).css('color', 'red');
                },
                submitHandler: function (form) {
                    // Validate image count for each color
                    var valid = true;
                    $('#color-images-container .color-image-container.active').each(function () {
                        var colorId = $(this).data('color-id');
                        var $previewContainer = $(this).find('.image-preview[data-color-id="' + colorId + '"]');
                        var imageCount = parseInt($(this).find('.image-count-value').text());
                        if (imageCount < 3) {
                            toastr.error('Please ensure at least 3 images for color ID ' + colorId + ' (currently ' + imageCount + ').');
                            valid = false;
                        }
                    });
                    if (valid) {
                        form.submit();
                    }
                }
            });

            // Trigger color images container update on page load for edit form
            <?php if (!empty($id)): ?>
                $('#colors').trigger('change');
            <?php endif; ?>

            // Initialize image preview listeners
            attachImagePreviewListeners();
        });
    </script>

    <script>
        document.getElementById("video").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const video = document.getElementById("videoPreview");
                video.src = URL.createObjectURL(file);
                video.style.display = "block";
            }
        });
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

</body>
</html>