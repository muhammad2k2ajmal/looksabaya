<?php
session_start();
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$conn = new dbClass();
$common = new CommProducts();
$cartItem = new Cart();

$ipAddress = $_SERVER["REMOTE_ADDR"];
$cartData = $cartItem->cartItems($_SESSION['cart_item'], $ipAddress);

$customerId = isset($_SESSION['USER_LOGIN']) ? $_SESSION['USER_LOGIN'] : null;
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
                                        <a href="looksabaya-cart.php" class="text-decoration-none">Shopping Cart</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="carttablewrap pt-7 pb-20">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="mnHding fw-normal mb-7 mb-sm-10">Shopping Cart</h1>
                        </div>
                        <?php if (empty($cartData)): ?>
                            <div class="col-12 text-center">
                                <p>Your cart is empty.</p>
                            </div>
                        <?php else: ?>
                            <div class="col-12 col-xl-9">
                                <div class="table-responsive pe-xl-14">
                                    <table class="table align-middle carttable mb-10">
                                        <thead>
                                            <tr>
                                                <th class="col col01 text-uppercase fw-normal ps-15">Product</th>
                                                <th class="col col02 text-uppercase fw-normal">Price</th>
                                                <th class="col col03 text-uppercase fw-normal">Quantity</th>
                                                <th class="col col04 text-uppercase fw-normal">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $itemTotal = 0;
                                            $discountTotal = 0;
                                            $amountTotal = 0;
                                            $allProductsInStock = true;

                                            foreach ($cartData as $cartRow):
                                                $cartProductSql = $common->getProductsById($cartRow['product_id']);
                                                $i++;
                                                $discountInfo = $cartProductSql['price'] * (1 - $cartProductSql['discount'] / 100);
                                                $cartProductTotal = $cartRow['quantity'] * $discountInfo;
                                                $itemTotal += $cartRow['quantity'] * $cartProductSql['price'];
                                                $discountTotal += $cartRow['quantity'] * ($cartProductSql['price'] - $discountInfo);
                                                $amountTotal += $cartProductTotal;
                                                $quantity = $cartRow['quantity'];
                                                $product_stock = $cartProductSql['stock'];

                                                if ($product_stock <= 0) {
                                                    $outOfStock = true;
                                                    $allProductsInStock = false;
                                                } elseif ($quantity > $product_stock) {
                                                    $cart_id = $cartRow['cart_id'];
                                                    $userId = $_SESSION['cart_item'];
                                                    $newQuantity = $product_stock;
                                                    $cartItem->decreaseCartItembecauseofstock($userId, $cart_id, $cartRow['product_id'], $ipAddress, $newQuantity);
                                                    $outOfStock = false;
                                                } else {
                                                    $outOfStock = false;
                                                }

                                                $PurchasebuttonClass = $allProductsInStock ? '' : 'disabled';
                                                ?>
                                                <tr class="product-cart" data-product-id="<?php echo $cartRow['product_id']; ?>"
                                                    data-cart-id="<?php echo $cartRow['cart_id']; ?>">
                                                    <td class="d-flex align-items-center ps-4 pt-4 pb-4">
                                                        <button class="btn btn-sm btnicon p-0 me-6 removeCart"
                                                            data-product-id="<?php echo $cartRow['product_id']; ?>"
                                                            data-cart-id="<?php echo $cartRow['cart_id']; ?>">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <img width="60px" height="60px"
                                                            src="adminUploads/products/<?= $cartProductSql['image']; ?>"
                                                            class="img-thumbnail border-0 p-0 me-4 rounded-0 tb-img"
                                                            alt="Product Image">
                                                        <span
                                                            class="tb-heading d-block fw-light"><?= $cartProductSql['name']; ?></span>
                                                    </td>
                                                    <?php if ($cartProductSql['discount'] > 0): ?>
                                                        <td class="tb-price fw-normal">
                                                            <s>Rs.<?= number_format($cartProductSql['price'], 2); ?></s>
                                                            <br>
                                                            <span class="fs-6"><?= $cartProductSql['discount']; ?>%</span>
                                                            <br>
                                                            <span class="fs-5">Rs.<?= number_format($discountInfo, 2); ?></span>
                                                        </td>
                                                    <?php else: ?>
                                                        <td class="tb-price fw-normal">
                                                            <span
                                                                class="fs-5">Rs.<?= number_format($cartProductSql['price'], 2); ?></span>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td>
                                                        <div class="input-group position-relative">
                                                            <button class="btn btn-minus border-0" style="
                                                                top: 20px;
                                                            "><i class="fa fa-minus"></i>   </button>
                                                            <input type="text"
                                                                class="form-control text-center fw-light px-6 quantity-input"
                                                                value="<?php echo $cartRow['quantity']; ?>" min="1"
                                                                data-stock="<?php echo $cartProductSql['stock']; ?>"
                                                                data-discounted-price="<?php echo $discountInfo; ?>"
                                                                data-original-price="<?php echo $cartProductSql['price']; ?>">
                                                            <button class="btn btn-plus border-0" style="
                                                                top: 20px;
                                                            "><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </td>
                                                    <td class="tb-price fw-normal product-subtotal">
                                                        Rs.<?php echo number_format($cartProductTotal, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="p-0 border-0" colspan="4">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-end align-items-start align-items-md-center gap-3 pt-5">
                                                        <button class="btn btn-dark btncart">Update Cart</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php
                            $appliedCoupon = isset($_SESSION['applied_coupon']) ? $_SESSION['applied_coupon'] : null;
                            $couponDiscount = $appliedCoupon ? $amountTotal * ($appliedCoupon['discount_percentage'] / 100) : 0;
                            if ($couponDiscount > 1000) {
                                $couponDiscount = 1000;
                            }
                            $finalTotal = $amountTotal ;
                            ?>
                            <div class="col-12 col-xl-3">
                                <div class="ms-xl-n8">
                                    <div class="cartSide border py-5 px-2 px-md-6">
                                        <h5 class="cartHeading fw-medium mb-4">Cart Totals</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="subheading fw-normal">Subtotal</span>
                                            <strong
                                                class="Hprice fw-normal subtotal">Rs.<?php echo number_format($amountTotal, 2); ?></strong>
                                        </div>
                                        <hr class="mb-2">
                                        <div class="d-flex justify-content-between mb-4">
                                            <span class="subheading fw-normal">Total</span>
                                            <strong
                                                class="Hprice fw-medium total-order">Rs.<?php echo number_format($finalTotal, 2); ?></strong>
                                        </div>
                                        <button
                                            class="btn_hover_color fw-medium w-100 mb-1 <?php echo $PurchasebuttonClass; ?>"
                                            id="proceedToCheckout" data-customer-id="<?php echo $customerId; ?>"><a
                                                href="looksabaya-checkout.php?checkout=cart">Proceed to Checkout</a></button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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
            const userId = '<?php echo $_SESSION['cart_item']; ?>';
            const customerId = '<?php echo isset($_SESSION['USER_LOGIN']) ? $_SESSION['USER_LOGIN'] : ''; ?>';
            const ipAddress = '<?php echo $ipAddress; ?>';

            // Update totals in the summary section
            function updateSummary() {
                let subtotal = 0;
                let totalDiscount = 0;

                $('.product-cart').each(function () {
                    const quantity = parseInt($(this).find('.quantity-input').val()) || 1;
                    const discountedPrice = parseFloat($(this).find('.quantity-input').data('discounted-price'));
                    const originalPrice = parseFloat($(this).find('.quantity-input').data('original-price'));
                    const productSubtotal = quantity * discountedPrice;
                    subtotal += productSubtotal;
                    totalDiscount += quantity * (originalPrice - discountedPrice);
                });

                // Apply coupon discount
                const couponDiscount = <?php echo $couponDiscount; ?>;
                totalDiscount += couponDiscount;

                $('.subtotal').text(`Rs.${subtotal.toFixed(2)}`);
                $('.total-discount').text(`Rs.${totalDiscount.toFixed(2)}`);
                $('.total-order').text(`Rs.${(subtotal).toFixed(2)}`);

                // Update Proceed to Checkout button
                const allInStock = $('.quantity-input').toArray().every(input =>
                    parseInt($(input).val()) <= parseInt($(input).data('stock')));
                $('#proceedToCheckout').prop('disabled', !allInStock).toggleClass('disabled', !allInStock);
            }

            // Update product subtotal
            function updateProductSubtotal(row) {
                const quantity = parseInt(row.find('.quantity-input').val()) || 1;
                const discountedPrice = parseFloat(row.find('.quantity-input').data('discounted-price'));
                const subtotal = quantity * discountedPrice;
                row.find('.product-subtotal').text(`Rs.${subtotal.toFixed(2)}`);
            }

            // Update cart via AJAX
            function updateCart(userId, productId, quantity, cartId, ipAddress, row, prevQuantity) {
                console.log("Updating cart for userId:", userId, "productId:", productId, "quantity:", quantity, "cartId:", cartId, "ipAddress:", ipAddress);
                $.ajax({
                    type: 'POST',
                    url: 'ajax-cart',
                    data: {
                        user_id: userId,
                        product_id: productId,
                        product_quantity: quantity,
                        cart_id: cartId,
                        ip_address: ipAddress
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            updateProductSubtotal(row);
                            updateSummary();
                        } else {
                            toastr.error(response.message);
                            row.find('.quantity-input').val(prevQuantity);
                            updateProductSubtotal(row);
                            updateSummary();
                        }
                    },
                    error: function (xhr, status, error) {
                        toastr.error('Error updating cart: ' + error);
                        row.find('.quantity-input').val(prevQuantity);
                        updateProductSubtotal(row);
                        updateSummary();
                    }
                });
            }

            // Quantity Decrease
            $('.btn-minus').on('click', function () {
                const row = $(this).closest('.product-cart');
                const input = row.find('.quantity-input');
                let quantity = parseInt(input.val()) || 1;
                if (quantity <= 1) return;
                const prevQuantity = quantity;
                quantity--;
                input.val(quantity);
                updateProductSubtotal(row);
                updateSummary();
                const productId = row.data('product-id');
                const cartId = row.data('cart-id');
                updateCart(userId, productId, quantity, cartId, ipAddress, row, prevQuantity);
            });

            // Quantity Increase
            $('.btn-plus').on('click', function () {
                const row = $(this).closest('.product-cart');
                const input = row.find('.quantity-input');
                let quantity = parseInt(input.val()) || 1;
                const stock = parseInt(input.data('stock')) || Infinity;
                if (quantity >= stock) {
                    toastr.error('Maximum stock limit reached.');
                    return;
                }
                const prevQuantity = quantity;
                quantity++;
                input.val(quantity);
                updateProductSubtotal(row);
                updateSummary();
                const productId = row.data('product-id');
                const cartId = row.data('cart-id');
                updateCart(userId, productId, quantity, cartId, ipAddress, row, prevQuantity);
            });

            // Remove Cart Item
            $(document).on('click', '.removeCart', function (e) {
                e.preventDefault();
                const row = $(this).closest('.product-cart');
                const action = 'deleteCartItem';
                const productId = $(this).data('product-id');
                const productCartId = $(this).data('cart-id');

                $.ajax({
                    type: 'POST',
                    url: 'ajax-cart',
                    data: {
                        deleteCartItem: action,
                        productId: productId,
                        productCartId: productCartId,
                        ip_address: ipAddress
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            row.remove();
                            updateSummary();
                            if ($('.product-cart').length === 0) {
                                location.reload();
                            }
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        toastr.error('Error removing item: ' + error);
                    }
                });
            });

            // Handle Proceed to Checkout
            $('#proceedToCheckout').on('click', function () {
                if (!customerId) {
                    $('#cartModal').modal('show');
                } else {
                    window.location.href = 'checkout.php';
                }
            });

            // Initialize summary
            updateSummary();
        });
    </script>
</body>

</html>