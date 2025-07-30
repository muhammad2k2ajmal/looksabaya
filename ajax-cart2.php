<?php
session_start();
error_reporting(E_ALL);

require 'config/config.php';
require 'config/cart.php';
require 'config/common.php';

$conn = new dbClass();
$cartItem = new Cart();
$common = new CommProducts();
$ipAddress = $_SERVER["REMOTE_ADDR"];

header('Content-Type: application/json');

error_log("Received request to ajax-cart.php: Method=" . $_SERVER['REQUEST_METHOD'] . ", POST=" . json_encode($_POST) . ", GET=" . json_encode($_GET));

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD'] . ", Query: " . json_encode($_GET));
    echo json_encode($response);
    exit;
}

if (isset($_POST['setCheckoutSession'])) {
    $_SESSION['USER_CHECKOUT'] = 'checkout';
    $_SERVER['REQUEST_URI'] = "checkout.php";
    echo json_encode(['status' => 'success', 'message' => 'Checkout session set']);
    exit;
}

// City Selection
if (isset($_POST['state_id']) || (isset($_POST['state']) && isset($_POST['country']))) {
    $stateId = isset($_POST['state_id']) ? $_POST['state_id'] : $_POST['state'];
    $result = $conn->cities($stateId);
    $cities = [];

    foreach ($result as $row) {
        $cities[] = ['id' => $row['id'], 'name' => $row['name']];
    }

    if (count($cities) > 0) {
        $response = [
            'status' => 'success',
            'data' => ['cities' => $cities]
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No cities available'
        ];
    }

    echo json_encode($response);
    exit;
}

// Cart Quantity Update
if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['product_quantity']) && isset($_POST['cart_id'])) {
    $userId = $_POST['user_id'];
    $productId = intval($_POST['product_id']);
    $size = $_POST['size'] ?? '';
    $color = $_POST['color'] ?? '';
    $quantity = intval($_POST['product_quantity']);
    $cartId = intval($_POST['cart_id']);

    if ($productId <= 0 || $quantity < 1 || $cartId <= 0 || empty($size) || empty($color)) {
        $response['message'] = 'Invalid parameters: ' .
            ($productId <= 0 ? 'Invalid product ID. ' : '') .
            ($quantity < 1 ? 'Invalid quantity. ' : '') .
            ($cartId <= 0 ? 'Invalid cart ID. ' : '') .
            (empty($size) ? 'Size not selected. ' : '') .
            (empty($color) ? 'Color not selected. ' : '');
        error_log("Cart quantity update failed: " . $response['message']);
        echo json_encode($response);
        exit;
    }

    // Stock validation
    $product = $common->getProductsById($productId);
    if (!$product) {
        error_log("Product not found for productId: $productId");
        $response['message'] = 'Product not found';
        echo json_encode($response);
        exit;
    }
    if ($product['stock'] < $quantity) {
        error_log("Insufficient stock for productId: $productId, requested: $quantity, available: {$product['stock']}");
        $response['message'] = 'Requested quantity exceeds available stock';
        echo json_encode($response);
        exit;
    }

    $result = $cartItem->updateCartItem123($userId, $productId, $quantity, $size, $color, $cartId);
    if ($result) {
        $response = [
            'status' => 'success',
            'message' => 'Cart updated successfully'
        ];
    } else {
        $response['message'] = 'Failed to update cart';
        error_log("Failed to update cart: productId=$productId, userId=$userId");
    }

    echo json_encode($response);
    exit;
}

// Add Product to Cart
if (isset($_POST['buyNow']) && $_POST['buyNow'] === 'Add To Cart' && isset($_POST['pId']) && isset($_POST['quantity'])) {
    if (!isset($_SESSION['cart_item'])) {
        $_SESSION['cart_item'] = uniqid('cart_', true); // Initialize cart for guests
        error_log("Initialized cart_item session: " . $_SESSION['cart_item']);
    }
    $userId = $_SESSION['cart_item'];
    $pId = intval($_POST['pId']);
    $quantity = intval($_POST['quantity']);
    $size = $_POST['size'] ?? '';
    $color = $_POST['color'] ?? '';
    $ipAddress = $_SERVER["REMOTE_ADDR"];

    if (!$userId || $pId <= 0 || $quantity <= 0 || empty($size) || empty($color)) {
        $response['message'] = 'Missing or invalid parameters: ' .
            (!$userId ? 'User ID missing. ' : '') .
            ($pId <= 0 ? 'Invalid product ID. ' : '') .
            ($quantity <= 0 ? 'Invalid quantity (must be greater than 0). ' : '') .
            (empty($size) ? 'Size not selected. ' : '') .
            (empty($color) ? 'Color not selected. ' : '');
        error_log("Add to Cart failed: " . $response['message']);
        echo json_encode($response);
        exit;
    }

    // Stock validation
    $product = $common->getProductsById($pId);
    if (!$product) {
        error_log("Product not found for pId: $pId");
        $response['message'] = 'Product not found';
        echo json_encode($response);
        exit;
    }
    if ($product['stock'] < $quantity) {
        error_log("Insufficient stock for pId: $pId, requested: $quantity, available: {$product['stock']}");
        $response['message'] = 'Requested quantity exceeds available stock';
        echo json_encode($response);
        exit;
    }

    $cartDetail = $cartItem->cartCheck($userId, $pId, $ipAddress);

    if (empty($cartDetail['cart_id']) && empty($cartDetail['insert_ip'])) {
        // Add new cart item
        $addCartSql = $cartItem->addCartItem($userId, $pId, $quantity, $size, $color, $ipAddress);
        if ($addCartSql) {
            $response = [
                'status' => 'success',
                'message' => 'Product added to cart successfully'
            ];
        } else {
            $response['message'] = 'Error adding product to cart';
            error_log("Failed to add product to cart: pId=$pId, userId=$userId");
        }
    } else {
        // Update existing cart item
        if ($product['stock'] >= $quantity) {
            $updateCartSql = $cartItem->updateCartItem($userId, $pId, $quantity, $size, $color, $ipAddress, $cartDetail['cart_id']);
            if ($updateCartSql) {
                $response = [
                    'status' => 'success',
                    'message' => 'Product quantity updated in cart'
                ];
            } else {
                $response['message'] = 'Error updating product in cart';
                error_log("Failed to update cart: pId=$pId, userId=$userId");
            }
        } else {
            $response['message'] = 'Requested quantity exceeds available stock';
            error_log("Insufficient stock for update: pId=$pId, requested: $quantity, available: {$product['stock']}");
        }
    }

    echo json_encode($response);
    exit;
}

// Delete Cart Item
if (isset($_POST['deleteCartItem']) && isset($_POST['productId']) && isset($_POST['productCartId'])) {
    $userId = $_SESSION['cart_item'] ?? null;
    $productId = intval($_POST['productId']);
    $productCartId = intval($_POST['productCartId']);

    if (!$userId || $productId <= 0 || $productCartId <= 0) {
        $response['message'] = 'Missing or invalid parameters: ' .
            (!$userId ? 'User ID missing. ' : '') .
            ($productId <= 0 ? 'Invalid product ID. ' : '') .
            ($productCartId <= 0 ? 'Invalid cart ID. ' : '');
        error_log("Delete cart item failed: userId=" . ($userId ?: 'null') . ", productId=$productId, productCartId=$productCartId");
        echo json_encode($response);
        exit;
    }

    $result = $cartItem->removeCartItem($userId, $productCartId, $productId, $ipAddress);
    if ($result) {
        $response = [
            'status' => 'success',
            'message' => 'Item removed from cart successfully'
        ];
    } else {
        $response['message'] = 'Failed to remove item from cart';
        error_log("Failed to remove cart item: userId=$userId, productId=$productId, productCartId=$productCartId");
    }

    echo json_encode($response);
    exit;
}

echo json_encode($response);
exit;
?>