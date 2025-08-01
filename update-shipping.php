<?php
// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary configuration and class files
require "config/config.php";
require "config/authentication.php";
require 'config/cart.php';
require 'config/products.php';
require 'config/calculate-shipping.php';

// Initialize class objects
$conn = new dbClass();
$auth = new Authentication();
$cartItem = new Cart();
$productsObj = new Products();
$calculator = new PincodeDistanceCalculator();

// Get client IP address and user ID from session
$ipAddress = $_SERVER["REMOTE_ADDR"];
$userId = isset($_SESSION['USER_LOGIN']) ? $_SESSION['USER_LOGIN'] : null;

// Validate session and cart
if (!$userId || !isset($_SESSION['cart_item'])) {
    $_SESSION['err'] = "User not logged in or cart is empty.";
    error_log("Session validation failed: userId=$userId, cart_item=" . (isset($_SESSION['cart_item']) ? 'set' : 'not set'));
    header("Location: abayalooks-orders.php");
    exit();
}

// Verify user session
$auth->checkSession($userId);

// Retrieve and sanitize request parameters
$shipId = trim($_REQUEST['shipId'] ?? '');
$billId = trim($_REQUEST['billId'] ?? '');
$type = trim($_REQUEST['type'] ?? '');

// Log inputs for debugging
error_log("shipId: $shipId, billId: $billId, type: $type");

// Validate request parameters
if (empty($shipId) || empty($billId) || empty($type)) {
    $_SESSION['err'] = "Missing required parameters.";
    error_log("Missing parameters: shipId=$shipId, billId=$billId, type=$type");
    header("Location: abayalooks-orders.php");
    exit();
}

// Define credentials 
$clientId = 'SU2507281630468558993691';
$clientSecret = 'ee67cd0e-9f46-4431-b6bc-914cf33a521b';
$clientVersion = 1;
$accessTokenFile = 'access_token.json';

// Function to get the access token from the file
function getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile)
{
    if (file_exists($accessTokenFile)) {
        $tokenData = json_decode(file_get_contents($accessTokenFile), true);
        if (isset($tokenData['access_token']) && time() < $tokenData['expires_at'] - (1 * 60)) {
            return $tokenData['access_token'];
        }
    }
    return false;
}

// Check if transaction ID is provided
if (!isset($_REQUEST['transactionId']) || empty(trim($_REQUEST['transactionId']))) {
    $_SESSION['err'] = "Transaction ID is missing";
    error_log("Transaction ID missing");
    header("Location: abayalooks-orders.php");
    exit();
}

$merchantOrderId = trim($_REQUEST['transactionId']);

// Get access token
$accessToken = getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile);

// Handle token retrieval failure
if (!$accessToken) {
    error_log('Failed to retrieve access token from the stored file.');
    $_SESSION['err'] = "Access token retrieval failed.";
    header("Location: abayalooks-orders.php");
    exit();
}

// Set up PhonePe status check API request
$phonePeStatusUrl = "https://api.phonepe.com/apis/pg/checkout/v2/order/{$merchantOrderId}/status";
$headers = [
    'Content-Type: application/json',
    'Authorization: O-Bearer ' . $accessToken
];

// Initialize cURL for PhonePe status check
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $phonePeStatusUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// Decode API response
$api_response = json_decode($response, true);

// Log callback details for debugging
file_put_contents('callback_log.txt', "Headers: " . print_r(getallheaders(), true) . PHP_EOL, FILE_APPEND);
file_put_contents('callback_log.txt', "Raw Response: " . print_r($response, true) . PHP_EOL, FILE_APPEND);

// Handle transaction states
if (isset($api_response['state'])) {
    $orderState = $api_response['state'];

    switch ($orderState) {
        case 'COMPLETED':
            // Add shipping address and calculate shipping charge
            $result = $auth->getuserShipDetailsByShipId($shipId);
            $order_ship = $auth->addOrderShipAddress($shipId);
            $order_bill = $auth->addOrderBillAddress($billId);

            $defaultPostcode = ($result !== false && isset($result['postal_code'])) ? $result['postal_code'] : '';
            try {
                $shippingCharge = $calculator->calculatePrice($defaultPostcode);
            } catch (Exception $e) {
                $shippingCharge = 0;
                error_log("Shipping charge calculation failed: " . $e->getMessage());
            }

            // Get address details
            $address = $auth->userOrderAddressDetailsByShipId($shipId);
            $addressId = (isset($address['id']) && is_numeric($address['id'])) ? $address['id'] : null;

            if ($addressId === null) {
                $_SESSION['err'] = "Invalid shipping address ID.";
                error_log("Invalid address ID for shipId: $shipId");
                header("Location: abayalooks-orders.php");
                exit();
            }

            $billaddress = $auth->userOrderAddressDetailsBillId($billId);
            $errors = [];

            // Function to generate unique order number
            function generateUniqueOrderNumber($conn)
            {
                do {
                    $orderNumber = rand(1000000000, 9999999999);
                    $query = $conn->getDataWithParams("SELECT COUNT(*) AS count FROM `orders_table` WHERE `order_number` = :orderNumber", ['orderNumber' => $orderNumber]);
                } while ($query['count'] > 0);
                return $orderNumber;
            }

            // Generate and store order number
            $orderNumber = generateUniqueOrderNumber($conn);
            $_SESSION['order_number'] = $orderNumber;

            // Get invoice number
            $invoice_number = $orderNumber;

            // Insert order into database using prepared statement


            // Get cart items based on purchase type
            if ($type == 'buyNow') {
                $cartData = $cartItem->buyNowItems($_SESSION['cart_item'], $ipAddress);
            } else {
                $cartData = $cartItem->cartItems($_SESSION['cart_item'], $ipAddress);
            }

            if ($cartData) {
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
            $stmt = "INSERT INTO `orders_table` (`order_number`, `invoice_number`, `payment_status`, `transaction_id`, `customer_id`, `address_id`, `bill_address`, `insert_ip`, `shipping_charge`, `subtotal`, `total`, `created_at`) 
                                   VALUES (:orderNumber, :invoiceNumber, :paymentStatus, :transactionId, :customerId, :addressId, :billAddress, :insertIp, :shippingCharge, :subtotal, :total, now())";
            $conn->executeStatement($stmt, [
                'orderNumber' => $orderNumber,
                'invoiceNumber' => $invoice_number,
                'paymentStatus' => 'Completed',
                'transactionId' => $merchantOrderId,
                'customerId' => $userId,
                'addressId' => $addressId,
                'billAddress' => $order_bill,
                'insertIp' => $ipAddress,
                'shippingCharge' => $shippingCharge,
                'subtotal' => $subtotal,
                'total' => $total
            ]);
            $orderId = $conn->lastInsertId();
                foreach ($cartData as $products) {
                    $productId = $products['product_id'];
                    // Fetch product details
                    $output1 = $conn->getDataWithParams("SELECT * FROM `product` WHERE `product_id` = :productId", ['productId' => $productId]);
                    $productprice = $output1['price'];
                    $productName = $output1['name'];
                    $prSku = $output1['sku']??'';
                    $prHsn = $output1['hsn']??'';
                    $pkgLength = $output1['pkglength']??'';
                    $pkgHeight = $output1['pkgheight']??'';
                    $pkgWidth = $output1['pkgwidth']??'';
                    $pkgWeight = $output1['pkgweight']??'';

                    $cartItem = $_SESSION['cart_item'];
                    $customerId = $userId;

                    // Get product quantity
                    $output2 = $conn->getDataWithParams("SELECT quantity, color, size FROM `cart` WHERE customer_id = :customerId AND product_id = :productId AND `type` = :type", 
                                             ['customerId' => $customerId, 'productId' => $productId, 'type' => $type]);
                    $productQuantity = $output2['quantity'] ?? 1;
                    $productColor = $conn->addStr($output2['color'] ?? '');
                    $productSize = $conn->addStr($output2['size'] ?? '');

                    // Calculate discount and total
                    $discountInfo = calculateDiscount($output1['price'], $output1['discount']);
                    $productTotal = intval($productQuantity) * intval($discountInfo['discountedPrice']);
                    $discountedprice = $discountInfo['discountedPrice'];

                    // Insert order product details
                    $stmt = "INSERT INTO `order_product_details` (`order_id`, `product_id`, `product_name`, `color`, `size`, `product_price`, `product_quantity`, `product_total_price`, `sku`, `hsn`, `pkg_height`, `pkg_width`, `pkg_length`, `pkg_weight`) 
                                          VALUES (:orderId, :productId, :productName, :color, :size, :productPrice, :productQuantity, :productTotal, :sku, :hsn, :pkgHeight, :pkgWidth, :pkgLength, :pkgWeight)";
                    $conn->executeStatement($stmt, [
                        'orderId' => $orderId,
                        'productId' => $productId,
                        'productName' => $productName,
                        'color' => $productColor,
                        'size' => $productSize,
                        'productPrice' => $discountedprice,
                        'productQuantity' => $productQuantity,
                        'productTotal' => $productTotal,
                        'sku' => $prSku,
                        'hsn' => $prHsn,
                        'pkgHeight' => $pkgHeight,
                        'pkgWidth' => $pkgWidth,
                        'pkgLength' => $pkgLength,
                        'pkgWeight' => $pkgWeight
                    ]);
                }

                // Clear cart after order placement
                $stmt = "DELETE FROM `cart` WHERE `customer_id` = :customerId AND `type` = :type";
                $conn->executeStatement($stmt, ['customerId' => $userId, 'type' => $type]);

                // Fetch customer details
                $customerDetails = $conn->getData("SELECT customers.* FROM `orders_table`
                    JOIN customers ON customers.customer_id = orders_table.customer_id
                    WHERE `orders_table`.`order_number` = $orderNumber");

                $fname = $customerDetails['first_name'] ?? '';
                $lname = $customerDetails['last_name'] ?? '';
                $phone = $customerDetails['phone'] ?? '';
                $email = $customerDetails['email'] ?? '';

                // Fetching shipping details
                $shipDetails = $conn->getData("SELECT order_address.* FROM `orders_table`
                    JOIN order_address ON order_address.id = orders_table.address_id
                    WHERE `orders_table`.`order_number` = $orderNumber");

                $fname1 = $shipDetails['first_name'] ?? '';
                $lname1 = $shipDetails['last_name'] ?? '';
                $phone1 = $shipDetails['phone'] ?? '';
                $email1 = $shipDetails['email'] ?? '';
                $shippingAddress = $shipDetails['address'] ?? '';
                $state = $shipDetails['state'] ?? '';
                $city = $shipDetails['city'] ?? '';
                $postcode = $shipDetails['postcode'] ?? '';
                $apartment = $shipDetails['apartment'] ?? '';

                $orderDetails = '';
                $productSubTotal = 0;

                // Fetching product details
                $orderProducts = $conn->getAllData("SELECT order_product_details.* FROM `orders_table`
                    JOIN order_product_details ON order_product_details.order_id = orders_table.order_id
                    WHERE `orders_table`.`order_number` = $orderNumber");

                foreach ($orderProducts as $item) {
                    $productName = $item['product_name'];
                    $productPrice = $item['product_price'];
                    $productQuantity = $item['product_quantity'];
                    $productTotal = $item['product_total_price'];
                    $productSubTotal += $item['product_total_price'];

                    $orderDetails .= "<tr>
                        <td>{$productName}</td>
                        <td style='text-align:center;'>₹ {$productPrice}</td>
                        <td style='text-align:center;'>{$productQuantity}</td>
                        <td style='text-align:center;'>₹ {$item['product_total_price']}</td>
                    </tr>";
                }

                // Admin Email
                $adminTo = "muhammad.ajmal2k2@gmail.com, ajmal@ajinfotek.in";
                $adminSubject = "New Abaya Order Placed - Order #$orderNumber";
                $adminBody = "
                    <html>
                    <head>
                        <style>
                            body {font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333;}
                            table {border-collapse: collapse; width: 100%; max-width: 800px; margin: 20px auto; background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                            th, td {padding: 12px; text-align: left; border-bottom: 1px solid #ddd;}
                            th {background-color: #4DD6E0; color: white;}
                            tr:hover {background-color: #f2f2f2;}
                        </style>
                    </head>
                    <body>
                        <h3>You have received a new Abaya order - Order #$orderNumber</h3>
                        <p><strong>Customer Details:</strong></p>
                        <p>Name: $fname $lname<br>
                        Mobile: $phone<br>
                        Email: $email</p>
                        
                        <p><strong>Shipping Address:</strong></p>
                        <p>$fname1 $lname1<br>
                        Phone: $phone1<br>
                        Email: $email1<br>       
                        $apartment<br>
                        $shippingAddress<br>
                        $city, $state - $postcode</p>
                    
                        <p><strong>Order Details for Abaya Order #$orderNumber:</strong></p>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                $orderDetails                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='3' style='text-align: right;'><strong>Total Amount:</strong></td>
                                    <td style='text-align:center;'>₹ $productSubTotal</td>
                                </tr>
                            </tfoot>
                        </table>
                    </body>
                    </html>
                ";

                $adminHeaders = "MIME-Version: 1.0" . "\r\n";
                $adminHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $adminHeaders .= "From: Abaya Looks <abayalooks@abayalooks.com>" . "\r\n";
                mail($adminTo, $adminSubject, $adminBody, $adminHeaders);

                // Customer Email
                $customerTo = $email1 . ', rkrk03109@gmail.com';
                $customerSubject = "Thank You for Your Abaya Order! - Order #$orderNumber";
                $customerBody = "
                    <html>
                    <head>
                        <style>
                            body {font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333;}
                            table {border-collapse: collapse; width: 100%; max-width: 800px; margin: 20px auto; background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                            th, td {padding: 12px; text-align: left; border-bottom: 1px solid #ddd;}
                            th {background-color: #4DD6E0; color: white;}
                            tr:hover {background-color: #f2f2f2;}
                        </style>
                    </head>
                    <body>
                        <p>Dear $fname1 $lname1,</p>
                        <p>Thank you for choosing Abaya Looks for your Abaya needs. We are thrilled to have you as a customer and hope that our Abaya brings you clarity, comfort, and style.</p>

                        <h4>Order Details for Abaya Order #$orderNumber:</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                $orderDetails  
                            </tbody>
                        </table>

                        <p>Your order is being processed and will be shipped to the following address:</p>
                        <p><strong>Shipping Address:</strong><br>
                        $apartment<br>
                        $shippingAddress<br>
                        $city, $state - $postcode</p>

                        <p>Thank you again for your order! We look forward to serving you again soon.</p>
                        <p>Warm regards,<br>Abaya Looks Team<br>
                        <a href='mailto:ajmal@ajinfotek.com'>ajmal@ajinfotek.com</a></p>
                    </body>
                    </html>
                ";

                $customerHeaders = "MIME-Version: 1.0" . "\r\n";
                $customerHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $customerHeaders .= "From: Abaya Looks <abayalooks@abayalooks.com>" . "\r\n";
                mail($customerTo, $customerSubject, $customerBody, $customerHeaders);
            }

            $_SESSION['msg'] = "Transaction Complete";
            unset($_SESSION['order_number']);
            header("Location: abayalooks-orders.php.php");
            exit();

        case 'PENDING':
            $_SESSION['err'] = "Transaction is pending. Please check back later.";
            $_SESSION['api_response'] = $api_response;
            error_log("Transaction pending: " . print_r($api_response, true));
            header("Location: abayalooks-orders.php");
            exit();

        case 'FAILED':
            $errorCode = $api_response['paymentDetails'][0]['errorCode'] ?? 'Unknown error';
            $detailedErrorCode = $api_response['paymentDetails'][0]['detailedErrorCode'] ?? 'Unknown error';
            $_SESSION['err'] = "Transaction Failed. Error: {$errorCode}";
            $_SESSION['api_response'] = $api_response;
            error_log("Payment failed with error: {$errorCode}, Detailed error: {$detailedErrorCode}");
            header("Location: abayalooks-orders.php");
            exit();

        default:
            $_SESSION['err'] = "Unknown transaction state.";
            $_SESSION['api_response'] = $api_response;
            error_log("Unknown transaction state: " . print_r($api_response, true));
            header("Location: abayalooks-orders.php");
            exit();
    }
} else {
    $_SESSION['err'] = "Transaction state not received from PhonePe.";
    error_log("Invalid response from PhonePe: " . print_r($api_response, true));
    header("Location: abayalooks-orders.php");
    exit();
}
?>