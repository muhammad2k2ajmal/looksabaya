<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$product_id = isset($_GET['id']) ? base64_decode($_GET['id']) : '';

// Validate product_id
if (!$product_id || !is_numeric($product_id) || $product_id <= 0) {
    error_log("Invalid product_id: " . ($_GET['id'] ?? 'not set') . ", decoded: " . $product_id);
    header('location: index.php');
    exit;
}

$conn = new dbClass();
$common = new CommProducts();
$product = $common->getProductsById($product_id);
if (!$product) {
    error_log("Product not found for product_id: $product_id");
    header('location: index.php');
    exit;
}
$relatedProduct = $common->allOtherProduct($product['category_id'], $product_id);

$colorName = $product['colors'][0]['color_id'] ?? '';
$productImagesFirstFive = $common->getProductImagesByColor($product_id, $colorName);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="js/toastr/css/toastr.min.css">
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
                                        <a href="looksabaya-products.php?cid=<?= base64_encode($product['category_id']); ?>" class="text-decoration-none">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $product['name']; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="position-relative w-100 overflow-hidden py-2 productDetailsArea border-0">
                <div class="container">
                    <div class="productsDetailsWrapper WrapII row">
                        <div class="col-12 col-md-6 col-xlwd-6">
                            <div class="images">
                                <div class="preview-image mb-4">
                                    <div class="position-relative">
                                        <img class="img w-100 img-fluid sliderImg popup-img" src="adminUploads/products/<?= $productImagesFirstFive[0]['image']??$product['image']; ?>" alt="">
                                    </div>
                                </div>
                                <div class="imagesBlock d-flex flex-wrap">
                                    <div class="imagsItems me-4 mb-4">
                                        <img class="w-100 img-fluid popup-img" src="adminUploads/products/<?= $productImagesFirstFive[1]['image']??''; ?>" alt="">
                                    </div>
                                    <div class="imagsItems mb-4">
                                        <img class="w-100 img-fluid popup-img" src="adminUploads/products/<?= $productImagesFirstFive[2]['image']??''; ?>" alt="">
                                    </div>
                                    <div class="imagsItems me-4 mb-4">
                                        <img class="w-100 img-fluid popup-img" src="adminUploads/products/<?= $productImagesFirstFive[3]['image']??''; ?>" alt="">
                                    </div>
                                    <div class="imagsItems mb-4">
                                        <img class="w-100 img-fluid popup-img" src="adminUploads/products/<?= $productImagesFirstFive[4]['image']??''; ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="imageModal" class="modal" style="display: none;">
                            <span class="close" style="position:absolute;top:10px;right:45px;font-size:45px;cursor:pointer;color:#fff;">&times;</span>
                            <img class="modal-content" id="modalImg">
                            <div id="caption" style="color:white;text-align:center;margin-top:10px;"></div>
                        </div>
                        <div class="col-12 col-md-6 col-xlwd-5">
                            <div class="productInfo">
                                <div class="productInfodetails py-sm-2 px-sm-1">
                                    <div class="productInfoheader mb-6">
                                        <h2 class="PrdutHd fw-normal mb-1"><?= $product['name']; ?></h2>
                                        <div class="d-flex gap-2 align-items-center mb-2"></div>
                                        <h3 class="HPrice fw-normal mb-4">RS. <?= number_format($product['price']); ?></h3>
                                        <strong class="TxtPro">Availability: 
                                        <?php if ($product['stock'] > 10): ?>
                                            <span class="productStock fw-normal">In Stock</span></strong>
                                        <?php elseif ($product['stock'] > 0 && $product['stock'] <= 10): ?>
                                            <span class="productStock fw-normal"><?= $product['stock']; ?> Left</span></strong>
                                        <?php else: ?>
                                            <span class="productStock fw-normal">Out of Stock</span></strong>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-filter">
                                        <div class="color-options d-flex gap-2 mt-2">
                                            <?php foreach ($product['colors'] as $color): ?>
                                                <button class="color-btn <?= $color['color_id'] === $colorName ? 'active' : '' ?>" 
                                                        data-color="<?= $color['color_id'] ?>" 
                                                        aria-label="<?= $color['color_id'] ?>" 
                                                        style="background-color: <?= $color['color_code'] ?>;"></button>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="selected-color-container mt-2" style="display: none;">
                                            <label class="filter-label fw-normal">Selected Color: 
                                                <span class="selected-color fw-light"><?= $colorName ?></span>
                                            </label>
                                        </div>
                                        <div class="filter-group mb-2">
                                            <label class="filter-label fw-normal">Size: 
                                                <span class="selected-size fw-light currentSize"><?= $product['sizes'][0] ?></span>
                                            </label>
                                            <div class="size-options d-flex gap-2 mt-2">
                                                <?php foreach ($product['sizes'] as $index => $size): ?>
                                                    <button class="size-btn btn btn-outline-dark p-0 <?= $index === 0 ? 'active' : '' ?>"><?= $size ?></button>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="butttonsWraper">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="input-group position-relative me-3" style="max-width: 160px;">
                                                    <button class="btn btn-minus border-0" type="button"><i class="fa fa-minus"></i></button>
                                                    <input type="number" id="quantityInput" class="form-control text-center fw-light px-6" min="1" max="12" value="1">
                                                    <button class="btn btn-plus border-0" type="button"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <button type="button" class="btn btnTheme submitButton fw-medium text-uppercase add-to-cart">Add To Cart</button>
                                            </div>
                                            <a href="javascript:void(0);" class="btn btn-light submitButton btnII fw-medium text-uppercase buy-now">BUY IT NOW</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion product-accordion" id="productAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingDesc">
                                            <button class="accordion-button fw-normal rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDesc" aria-expanded="true" aria-controls="collapseDesc">
                                                Description
                                            </button>
                                        </h2>
                                        <div id="collapseDesc" class="accordion-collapse collapse show" aria-labelledby="headingDesc" data-bs-parent="#productAccordion">
                                            <div class="accordion-body">
                                                <p><?= $product['description']; ?></p>
                                                <ul class="ul_text">
                                                    <?php foreach ($product['lists'] as $list): ?>
                                                        <li><?= $list; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingInfo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo" aria-expanded="false" aria-controls="collapseInfo">
                                                Additional Information
                                            </button>
                                        </h2>
                                        <div id="collapseInfo" class="accordion-collapse collapse" aria-labelledby="headingInfo" data-bs-parent="#productAccordion">
                                            <div class="accordion-body mb-6">
                                                <table class="table DetalsTable table-bordered mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-uppercase fw-medium py-3 px-6">Weight</th>
                                                            <td class="py-3 px-6"><?= $product['weight']; ?> kg</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-uppercase fw-medium py-3 px-6">Dimensions</th>
                                                            <td class="py-3 px-6">
                                                                <?= number_format($product['length'], 0); ?> x 
                                                                <?= number_format($product['width'], 0); ?> x 
                                                                <?= number_format($product['height'], 0); ?> cm
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-uppercase fw-medium py-3 px-6">Composition</th>
                                                            <td class="py-3 px-6"><?= $product['composition']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-uppercase fw-medium py-3 px-6">Colour</th>
                                                            <td class="py-3 px-6">
                                                                <?php
                                                                $colorNames = array_map(function($color) {
                                                                    return $color['name'];
                                                                }, $product['colors']);
                                                                echo implode(', ', $colorNames);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-uppercase fw-medium py-3 px-6">Size</th>
                                                            <td class="py-3 px-6">
                                                                <?php
                                                                $sizeNames = array_map(function($size) {
                                                                    return $size;
                                                                }, $product['sizes']);
                                                                echo implode(', ', $sizeNames);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingReviews">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReview" aria-expanded="false" aria-controls="collapseReview">
                                                Delivery & Returns
                                            </button>
                                        </h2>
                                        <div id="collapseReview" class="accordion-collapse collapse" aria-labelledby="headingReview" data-bs-parent="#productAccordion">
                                            <div class="accordion-body">
                                                <table class="table DetalsTable table-bordered mb-0">
                                                    <tbody>
                                                        <tr class="py-3 px-6">
                                                            <th>Delivery Time Once Dispatched</th>
                                                            <th>Cost</th>
                                                        </tr>
                                                        <?php foreach ($product['delivery_options'] as $delivery_options): ?>
                                                            <tr>
                                                                <td><?= $delivery_options['delivery_location'] ?></td>
                                                                <td><?= $delivery_options['cost'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="collectionBlock position-relative w-100 overflow-hidden py-6">
                <div class="container">
                    <header class="headingHead text-center mb-5 mt-1">
                        <h2 class="position-relative fw-normal hhHeading patternActive d-flex justify-content-center align-items-center gap-4 mb-2">
                            Related products
                        </h2>
                    </header>
                    <div class="slidersColsHolder">
                        <div class="cbSlider">
                            <?php foreach ($relatedProduct as $productRow): ?>
                                <div class="schCol">
                                    <article class="productColumn text-center text-decoration-none position-relative d-block overflow-hidden">
                                        <div class="imgHolder mb-2">
                                            <a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']); ?>">
                                                <img src="images/best selling/p-15.jpeg" class="w-100 img-fluid" alt="image description">
                                            </a>
                                        </div>
                                        <h3 class="fw-light pcHeading mb-1">
                                            <a href="looksabaya-products-details.php?id=<?= base64_encode($productRow['product_id']); ?>" class="text-decoration-none">
                                                <?= $productRow['name']; ?>
                                            </a>
                                        </h3>
                                        <h4 class="fw-normal mb-0">
                                            <span class="regPrice">Rs. <?= number_format($productRow['price'], 2); ?></span>
                                        </h4>
                                        <button class="position-absolute fw-medium p-0 border-0">ADD TO CART</button>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'include/footer.php'; ?>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/popper.js" defer=""></script>
    <script src="js/bootstrap.js" defer=""></script>
    <script src="js/custom.js" defer=""></script>
    <script src="js/toastr/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Check resource loading
        window.addEventListener('error', function (e) {
            console.error('Resource failed to load:', e.target.src || e.target.href);
        });

        // Fallback if jQuery fails
        if (typeof jQuery === 'undefined') {
            console.error('jQuery not loaded. Check path to js/jquery.min.js');
            alert('JavaScript error: jQuery not loaded. Please contact support.');
        } else {
            console.log('jQuery version:', jQuery.fn.jquery);
        }

        $(document).ready(function () {
            try {
                console.log('jQuery loaded and document ready');

                // Quantity input handling
                const input = $('#quantityInput');
                const btnMinus = $('.btn-minus');
                const btnPlus = $('.btn-plus');

                // Set default value if empty
                if (!input.val() || isNaN(parseInt(input.val()))) {
                    input.val(1);
                }

                btnMinus.on('click', function () {
                    let value = parseInt(input.val()) || 1;
                    if (value > parseInt(input.attr('min'))) {
                        input.val(value - 1);
                    }
                });

                btnPlus.on('click', function () {
                    let value = parseInt(input.val()) || 1;
                    if (value < parseInt(input.attr('max'))) {
                        input.val(value + 1);
                    }
                });

                input.on('input', function () {
                    let value = parseInt($(this).val());
                    const min = parseInt($(this).attr('min'));
                    const max = parseInt($(this).attr('max'));

                    if (isNaN(value) || value < min) {
                        $(this).val(min);
                    } else if (value > max) {
                        $(this).val(max);
                        toastr.error('Maximum stock limit reached.');
                    }
                });

                // Size selection
                const sizeButtons = $('.size-btn');
                const selectedSize = $('.selected-size');

                sizeButtons.on('click', function () {
                    sizeButtons.removeClass('active');
                    $(this).addClass('active');
                    selectedSize.text($(this).text());
                });

                // Image modal
                const modal = $('#imageModal');
                const modalImg = $('#modalImg');
                const captionText = $('#caption');
                const closeBtn = modal.find('.close');

                $('.popup-img').on('click', function () {
                    modal.show();
                    modalImg.attr('src', $(this).attr('src'));
                    captionText.text($(this).attr('alt') || '');
                });

                closeBtn.on('click', function () {
                    modal.hide();
                });

                modal.on('click', function (e) {
                    if (e.target === modal[0]) {
                        modal.hide();
                    }
                });

                // Color selection
                const colorImageMap = {
                    <?php
                    $colorCount = count($product['colors']);
                    $i = 0;
                    foreach ($product['colors'] as $color) {
                        $colorName = is_array($color) ? $color['color_id'] : $color;
                        $productImages = $common->getProductImagesByColor($product_id, $colorName);
                        if (empty($productImages)) continue;

                        echo "\"{$colorName}\": {\n";
                        echo "    preview: 'adminUploads/products/{$productImages[0]['image']}',\n";
                        echo "    thumbnails: [\n";

                        $thumbs = array_slice($productImages, 1);
                        $thumbCount = count($thumbs);
                        foreach ($thumbs as $j => $img) {
                            $comma = $j < $thumbCount - 1 ? ',' : '';
                            echo "        'adminUploads/products/{$img['image']}'{$comma}\n";
                        }

                        echo "    ]\n";
                        echo "}";
                        if (++$i < $colorCount) echo ",\n";
                    }
                    ?>
                };

                const previewImg = $('.preview-image img');
                const thumbnailImgs = $('.imagesBlock .popup-img');
                const colorButtons = $('.color-btn');
                const selectedColorLabel = $('.selected-color');

                colorButtons.on('click', function () {
                    const selectedColor = $(this).data('color');
                    colorButtons.removeClass('active');
                    $(this).addClass('active');
                    previewImg.attr('src', colorImageMap[selectedColor].preview);
                    colorImageMap[selectedColor].thumbnails.forEach((src, index) => {
                        if (thumbnailImgs[index]) {
                            $(thumbnailImgs[index]).attr('src', src);
                        }
                    });
                    selectedColorLabel.text(selectedColor.charAt(0).toUpperCase() + selectedColor.slice(1));
                });

                // Carousel (if applicable)
                const carousel = $('#productCarousel');
                const thumbs = $('.thumb');
                carousel.on('slid.bs.carousel', function () {
                    const index = carousel.find('.carousel-item.active').index();
                    thumbs.removeClass('active').eq(index).addClass('active');
                });
                thumbs.on('click', function () {
                    thumbs.removeClass('active');
                    $(this).addClass('active');
                });

                // Add to Cart
                function addToCart(action, redirect = false) {
                    const productId = '<?php echo $product_id; ?>';
                    let quantity = parseInt($('#quantityInput').val());
                    if (isNaN(quantity) || quantity < 1) {
                        quantity = 1; // Default to 1 if invalid
                        $('#quantityInput').val(1);
                    }
                    const size = $('.selected-size').text().trim();
                    const color = $('.color-btn.active').data('color') || $('.selected-color').text().trim();

                    if (!productId || !size || !color) {
                        toastr.error('Please select a valid product, size, and color.');
                        console.error('Invalid parameters:', { productId, quantity, size, color });
                        return;
                    }

                    console.log('Sending AJAX request (Add to Cart):', { 
                        buyNow: action, 
                        pId: productId, 
                        quantity: quantity, 
                        size: size, 
                        color: color 
                    });

                    $.ajax({
                        type: 'POST',
                        url: 'ajax-cart',
                        data: { 
                            buyNow: action, 
                            pId: productId, 
                            quantity: quantity, 
                            size: size, 
                            color: color 
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            console.log('Initiating AJAX request to ajax-cart.php');
                        },
                        success: function (response) {
                            console.log('AJAX response:', response);
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                if (action === 'Add To Cart') {
                                    $('#modal-quantity').text(quantity);
                                    $('#cartModal').modal('show');
                                } else if (redirect) {
                                    window.location.href = 'buy-now.php';
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', { status: status, error: error, responseText: xhr.responseText });
                            toastr.error('Error processing request: ' + error);
                        }
                    });
                }

                // Buy Now
                function addToBuyNow(action, redirect = false) {
                    const productId = '<?php echo $product_id; ?>';
                    let quantity = parseInt($('#quantityInput').val());
                    if (isNaN(quantity) || quantity < 1) {
                        quantity = 1; // Default to 1 if invalid
                        $('#quantityInput').val(1);
                    }
                    const size = $('.selected-size').text().trim();
                    const color = $('.color-btn.active').data('color') || $('.selected-color').text().trim();

                    if (!productId || !size || !color) {
                        toastr.error('Please select a valid product, size, and color.');
                        console.error('Invalid parameters:', { productId, quantity, size, color });
                        return;
                    }

                    console.log('Sending AJAX request (Buy Now):', { 
                        buyNow: action, 
                        pId: productId, 
                        quantity: quantity, 
                        size: size, 
                        color: color 
                    });

                    $.ajax({
                        type: 'POST',
                        url: 'ajax-buy-now',
                        data: { 
                            buyNow: action, 
                            pId: productId, 
                            quantity: quantity, 
                            size: size, 
                            color: color 
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            console.log('Initiating AJAX request to ajax-buy-now.php');
                        },
                        success: function (response) {
                            console.log('AJAX response:', response);
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                if (action === 'Add To Cart') {
                                    $('#modal-quantity').text(quantity);
                                    $('#cartModal').modal('show');
                                } else if (redirect) {
                                    window.location.href = 'looksabaya-buynow.php';
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', { status: status, error: error, responseText: xhr.responseText });
                            toastr.error('Error processing request: ' + error);
                        }
                    });
                }

                // Event handlers
                $('.add-to-cart').on('click', function () {
                    console.log('Add to Cart button clicked');
                    addToCart('Add To Cart');
                });

                $('.buy-now').on('click', function (e) {
                    e.preventDefault();
                    console.log('Buy Now button clicked');
                    addToBuyNow('Buy Now', true);
                });

                // Toastr notifications
                <?php if (isset($_SESSION['msg'])): ?>
                    toastr.success("<?php echo $_SESSION['msg']; ?>");
                    <?php unset($_SESSION['msg']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['errmsg'])): ?>
                    toastr.error("<?php echo $_SESSION['errmsg']; ?>");
                    <?php unset($_SESSION['errmsg']); ?>
                <?php endif; ?>
            } catch (error) {
                console.error('JavaScript error in document.ready:', error);
                alert('JavaScript error occurred. Please check the console for details.');
            }
        });
    </script>
</body>
</html>