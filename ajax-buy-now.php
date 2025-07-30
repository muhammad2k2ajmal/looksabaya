<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require 'config/config.php';
require 'config/cart.php';
require 'config/common.php';

$conn = new dbClass();
$cartItem = new Cart();
$common = new CommProducts();

// City selection
if (!empty($_POST["state_id"])) {
    $stateId = $_POST["state_id"];
    $result = $conn->cities($stateId);

    if (count($result) > 0) {
        echo '<option value="">Select city</option>';
        foreach ($result as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    } else {
        echo '<option value="">City not available</option>';
    }
    exit;
}

// JSON response of city
if (isset($_POST["state"]) && !empty($_POST["state"]) && isset($_POST["country"]) && !empty($_POST["country"])) {
    $selectedState = $_POST["state"];
    $selectedCountry = $_POST["country"];

    $result = $conn->cities($selectedState);

    $cities = [];
    foreach ($result as $row) {
        $cities[] = ['id' => $row['id'], 'name' => $row['name']];
    }

    if (count($result) > 0) {
        $response = [
            'success' => true,
            'data' => ['cities' => $cities]
        ];
    } else {
        $response = [
            'success' => false
        ];
    }
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle cart quantity update
    if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['product_quantity']) && isset($_POST['cart_id'])) {
        $userId = $_POST['user_id'];
        $product_id = intval($_POST['product_id']);
        $product_quantity = intval($_POST['product_quantity']);
        $cart_id = intval($_POST['cart_id']);
        $size = $_POST['size'] ?? '';
        $color = $_POST['color'] ?? '';

        if ($product_id <= 0 || $product_quantity < 1 || $cart_id <= 0 || empty($size) || empty($color)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            exit;
        }

        $updateCartSql = $cartItem->updateCartItem123($userId, $product_id, $product_quantity, $size, $color, $cart_id);

        if ($updateCartSql) {
            echo json_encode(['status' => 'success', 'message' => 'Cart Updated Successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sorry, Some Error occurred']);
        }
        exit;
    }
    // Handle adding a product to the cart for Buy Now
    elseif (isset($_POST['buyNow']) && $_POST['buyNow'] === 'Buy Now' && isset($_POST['pId']) && isset($_POST['quantity'])) {
        if (!isset($_SESSION['cart_item'])) {
            $_SESSION['cart_item'] = uniqid('cart_', true); // Initialize cart for guests
        }
        $userId = $_SESSION['cart_item'];
        $pId = intval($_POST['pId']);
        $quantity = intval($_POST['quantity']);
        $size = $_POST['size'] ?? '';
        $color = $_POST['color'] ?? '';
        $ipAddress = $_SERVER["REMOTE_ADDR"];
        $_SESSION['BUYNOW'] = true;

        if (!$userId || $pId <= 0 || $quantity <= 0 || empty($size) || empty($color)) {
            $response = [
                'status' => 'error',
                'message' => 'Missing or invalid parameters: ' .
                    (!$userId ? 'User ID missing' : '') .
                    ($pId <= 0 ? 'Invalid product ID' : '') .
                    ($quantity <= 0 ? 'Invalid quantity' : '') .
                    (empty($size) ? 'Size not selected' : '') .
                    (empty($color) ? 'Color not selected' : '')
            ];
            echo json_encode($response);
            exit;
        }

        $product = $common->getProductsById($pId);
        if (!$product) {
            error_log("Product not found for pId: $pId");
            $response = ['status' => 'error', 'message' => 'Product not found'];
            echo json_encode($response);
            exit;
        }

        if ($product['stock'] < $quantity) {
            error_log("Insufficient stock for pId: $pId, requested: $quantity, available: {$product['stock']}");
            $response = ['status' => 'error', 'message' => 'Insufficient stock'];
            echo json_encode($response);
            exit;
        }

        $addCartSql = $cartItem->addBuyNowItem($userId, $pId, $quantity, $size, $color, $ipAddress);
        if ($addCartSql) {
            $response = [
                'status' => 'success',
                'message' => 'Product added to cart successfully'
            ];
        } else {
            $response = ['status' => 'error', 'message' => 'Error adding product to cart'];
        }

        echo json_encode($response);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Delete cart item
if (isset($_POST['deleteCartItem']) && isset($_POST['productId']) && isset($_POST['productCartId'])) {
    $userId = $_SESSION['cart_item'] ?? null;
    $productId = intval($_POST['productId']);
    $productCartId = intval($_POST['productCartId']);
    $ipAddress = $_SERVER["REMOTE_ADDR"];

    if (!$userId || $productId <= 0 || $productCartId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Missing or invalid parameters']);
        exit;
    }

    $cartDeleteItem = $cartItem->removeCartItem($userId, $productCartId, $productId, $ipAddress);
    if ($cartDeleteItem) {
        echo json_encode(['status' => 'success', 'message' => 'Remove item from cart successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sorry Some Error']);
    }
    exit;
}
?>