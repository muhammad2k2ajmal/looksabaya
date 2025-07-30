<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "config/config.php";
require "config/authentication.php";
require 'config/cart.php';
require 'config/calculate-shipping.php';
require 'config/common.php';

$conn = new dbClass();
$auth = new Authentication();
$cartItem = new Cart();
$calculator = new PincodeDistanceCalculator();
$ipAddress = $_SERVER["REMOTE_ADDR"];
$userId = $_SESSION['USER_LOGIN'] ?? null;

// Validate user session
if (!$userId || !$auth->checkSession($userId)) {
    $_SESSION['err'] = "User not logged in.";
    // header("Location: checkout.php");
    // exit();
}

// Validate input parameters
if (!isset($_REQUEST['shipId']) || !isset($_REQUEST['billId']) || !isset($_REQUEST['type'])) {
    $_SESSION['err'] = "Missing required parameters.";
    // header("Location: checkout.php");
    // exit();
}

$shipId = $conn->addStr(trim($_REQUEST['shipId']));
$billId = $conn->addStr(trim($_REQUEST['billId']));
$type = $conn->addStr(trim($_REQUEST['type']));

// Add shipping and billing addresses to order
$order_ship = $auth->addOrderShipAddress($shipId);
$order_bill = $auth->addOrderBillAddress($billId);

// Calculate shipping charge
$address = $auth->getuserShipDetailsByShipId($shipId);
$billaddress = $auth->userOrderAddressDetailsBillId($billId);
$defaultPostcode = $address['postal_code'] ?? '';
$shippingCharge = 0;
if ($defaultPostcode) {
    try {
        $shippingCharge = $calculator->calculatePrice($defaultPostcode);
        if (!is_numeric($shippingCharge) || $shippingCharge < 0) {
            $shippingCharge = 0;
        }
    } catch (Exception $e) {
        $shippingCharge = 0;
        $_SESSION['errmsg'] = "Error calculating shipping: " . $e->getMessage();
    }
}

$addressId = $address['id'] ?? '';
if (!$addressId) { 
    $_SESSION['err'] = "Invalid shipping address.";
    // header("Location: checkout.php");
    // exit();
}
$billaddressId = $billaddress['id'] ?? '';
if (!$billaddressId) {
    $_SESSION['err'] = "Invalid billing address.";
    // header("Location: checkout.php");
    // exit();
}

// Function to generate a unique order number
function generateUniqueOrderNumber($conn) {
    do {
        $orderNumber = rand(1000000000, 9999999999);
        $query = $conn->getData("SELECT COUNT(*) AS count FROM `orders_table` WHERE `order_number` = '$orderNumber'");
    } while ($query['count'] > 0);
    return $orderNumber;
}

// Generate and store order number
$orderNumber = generateUniqueOrderNumber($conn);
$_SESSION['order_number'] = $orderNumber;
$invoice_number = $orderNumber;

// Fetch cart items
$cartData = ($type == 'buyNow') ? $cartItem->buyNowItems($_SESSION['cart_item'] ?? [], $ipAddress) : $cartItem->cartItems($_SESSION['cart_item'] ?? [], $ipAddress);

if (!$cartData) {
    $_SESSION['err'] = "No cart items found.";
    // header("Location: checkout.php");
    // exit();
}

// Calculate subtotal
$subtotal = 0;
$discountTotal = 0;

foreach ($cartData as $products) {
    $productId = $products['product_id'];
    $output1 = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$productId'");
    if (!$output1) continue;

    $productPrice = floatval($output1['price']);
    $discount = floatval($output1['discount'] ?? 0);
    $discountedPrice = $productPrice * (1 - $discount / 100);
    
    $output2 = $conn->getData("SELECT quantity FROM `cart` WHERE customer_id = '$userId' AND product_id = '$productId' AND `type`='$type'");
    $productQuantity = intval($output2['quantity'] ?? 1);

    $productTotal = $productQuantity * $discountedPrice;
    $subtotal += $productTotal;
    $discountTotal += $productQuantity * ($productPrice - $discountedPrice);
}

$total = $subtotal + $shippingCharge;

// Insert order into orders_table
$query1 = $conn->execute("INSERT INTO `orders_table` (`order_number`, `invoice_number`, `payment_status`, `transaction_id`, `customer_id`, `address_id`, `bill_address`, `insert_ip`, `shipping_charge`, `subtotal`, `total`, `created_at`) 
                         VALUES ('$orderNumber', '$invoice_number', 'Pending', '', '$userId', '$order_ship', '$order_bill', '$ipAddress', '$shippingCharge', '$subtotal', '$total', now())");
$orderId = $conn->lastInsertId();

if (!$orderId) {
    $_SESSION['err'] = "Failed to create order.";
    // header("Location: checkout.php");
    // exit();
}

// Insert order items
foreach ($cartData as $products) {
    $productId = $products['product_id'];
    $output1 = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$productId'");
    if (!$output1) continue;

    $productPrice = floatval($output1['price']);
    $productName = $conn->addStr($output1['name']);
    $prSku = $conn->addStr($output1['sku'] ?? '');
    $prHsn = $conn->addStr($output1['hsn'] ?? '');
    $pkgLength = floatval($output1['pkglength'] ?? 0);
    $pkgHeight = floatval($output1['pkgheight'] ?? 0);
    $pkgWidth = floatval($output1['pkgwidth'] ?? 0);
    $pkgWeight = floatval($output1['pkgweight'] ?? 0);

    $output2 = $conn->getData("SELECT quantity,color,size FROM `cart` WHERE customer_id = '$userId' AND product_id = '$productId' AND `type`='$type'");
    $productQuantity = intval($output2['quantity'] ?? 1);
    $productColor = $conn->addStr($output2['color'] ?? '');
    $productSize = $conn->addStr($output2['size'] ?? '');

    // Calculate discount
    $discount = floatval($output1['discount'] ?? 0);
    $discountedPrice = $productPrice * (1 - $discount / 100);
    $productTotal = $productQuantity * $discountedPrice;

    // Insert into order_product_details
    $query2 = $conn->execute("INSERT INTO `order_product_details` (`order_id`, `product_id`, `product_name`, `color`, `size`, `product_price`, `product_quantity`, `product_total_price`, `sku`, `hsn`, `pkg_height`, `pkg_width`, `pkg_length`, `pkg_weight`) 
                             VALUES ('$orderId', '$productId', '$productName', '$productColor', '$productSize', '$discountedPrice', '$productQuantity', '$productTotal', '$prSku', '$prHsn', '$pkgHeight', '$pkgWidth', '$pkgLength', '$pkgWeight')");
}

// Clear cart items
$conn->execute("DELETE FROM `cart` WHERE `customer_id`='$userId' AND `type`='$type'");

// Clear session data
unset($_SESSION['order_number']);
unset($_SESSION['cart_item']);

// Set success message
$_SESSION['msg'] = "Order placed successfully.";

// Redirect to abayalooks-orders.php
header("Location: abayalooks-orders.php");
exit();
?>