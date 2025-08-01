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
$userId = $_SESSION['USER_LOGIN'];

// Verify user session
$auth->checkSession($_SESSION['USER_LOGIN']);

// Retrieve request parameters
$shipId = trim($_REQUEST['shipId']);
$billId = trim($_REQUEST['billId']);
$type = trim($_REQUEST['type']);

// Define credentials 
$clientId = 'SU2507281630468558993691';
$clientSecret = 'ee67cd0e-9f46-4431-b6bc-914cf33a521b';
$clientVersion = 1;
$accessTokenFile = 'access_token.json';

// Function to get the access token from the file
function getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile)
{
    // Check if token file exists
    if (file_exists($accessTokenFile)) {
        $tokenData = json_decode(file_get_contents($accessTokenFile), true);

        // Return token if it exists and hasn't expired
        if (isset($tokenData['access_token']) && time() < $tokenData['expires_at'] - (1 * 60)) {
            return $tokenData['access_token'];
        }
    }
    return false;
}

// Check if transaction ID is provided
if (!isset($_REQUEST['transactionId'])) {
    $_SESSION['err'] = "Transaction ID is missing";
    header("Location: thank-you.php");
    exit();
}

$merchantOrderId = $_REQUEST['transactionId'];

// Get access token
$accessToken = getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile);

// Handle token retrieval failure
if (!$accessToken) {
    error_log('Failed to retrieve access token from the stored file.');
    exit('Access token retrieval failed.');
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
            // $result = $auth->addOrderAddress($shipId);
            $result = $auth->getuserShipDetailsByShipId($shipId);
            $billaddress = $auth->userOrderAddressDetailsBillId($billId);
            $defaultPostcode = $result !== false ? $result : '';

            try {
                $shippingCharge = $calculator->calculatePrice($defaultPostcode);
            } catch (Exception $e) {
                $shippingCharge = 0;
            }

            // Get address details
            $address = $auth->userOrderAddressDetailsByShipId($shipId);
            $addressId = $address['id'];
            $errors = [];

            // Function to generate unique order number
            function generateUniqueOrderNumber($conn)
            {
                do {
                    $orderNumber = rand(1000000000, 9999999999);
                    $query = $conn->getData("SELECT COUNT(*) AS count FROM `orders_table` WHERE `order_number` = '$orderNumber'");
                } while ($query['count'] > 0);
                return $orderNumber;
            }

            // Generate and store order number
            $orderNumber = generateUniqueOrderNumber($conn);
            $_SESSION['order_number'] = $orderNumber;

            // Get invoice number
            // $invoice_number = $productsObj->getInvoiceForOnlineOrders();
            $invoice_number = $orderNumber;

            // Insert order into database
            $query1 = $conn->execute("INSERT INTO `orders_table`(`order_number`, `invoice_number`, `payment_status`, `transaction_id`, `customer_id`, `address_id`, `insert_ip`, `shipping_charge`, `created_at`) VALUES ('$orderNumber', '$invoice_number', 'Completed', '$merchantOrderId', '$userId', '$addressId', '$ipAddress', '$shippingCharge', now())");
            $orderId = $conn->lastInsertId();

            // Get cart items based on purchase type
            if ($type == 'buyNow') {
                $cartData = $cartItem->buyNowItems($_SESSION['cart_item'], $ipAddress);
            } else {
                $cartData = $cartItem->cartItems($_SESSION['cart_item'], $ipAddress);
            }

            if ($cartData) {
                foreach ($cartData as $products) {
                    $productId = $products['product_id'];
                    // Fetch product details
                    $output1 = $conn->getData("SELECT * FROM `products` WHERE `product_id` = '$productId'");
                    $productprice = $output1['price'];
                    $productName = $output1['name'];
                    $prSku = $output1['sku'];
                    $prHsn = $output1['hsn'];
                    $pkgLength = $output1['pkglength'];
                    $pkgHeight = $output1['pkgheight'];
                    $pkgWidth = $output1['pkgwidth'];
                    $pkgWeight = $output1['pkgweight'];

                    $cartItem = $_SESSION['cart_item'];
                    $Ip_Address = $_SERVER['REMOTE_ADDR'];
                    $customerId = $_SESSION['USER_LOGIN'] ?? '';

                    // Get product quantity
                    $output2 = $conn->getData("SELECT quantity,color,size FROM `cart` WHERE customer_id = '$customerId' AND product_id = '$productId' AND `type`='$type'");
                    $productQuantity = $output2['quantity'];
                    $productColor = $conn->addStr($output2['color'] ?? '');
                    $productSize = $conn->addStr($output2['size'] ?? '');

                    // Calculate discount and total
                    $discountInfo = calculateDiscount($output1['price'], $output1['discount']);
                    $productTotal = intval($productQuantity) * intval($discountInfo['discountedPrice']);
                    $discountedprice = $discountInfo['discountedPrice'];

                    // Insert order product details
                    $query2 = $conn->execute("INSERT INTO `order_product_details` (`order_id`, `product_id`, `product_name`, `color`, `size`, `product_price`, `product_quantity`, `product_total_price`, `sku`, `hsn`, `pkg_height`, `pkg_width`, `pkg_length`, `pkg_weight`) 
                                      VALUES ('" . $orderId . "', '$productId', '$productName', '$productColor', '$productSize', '$discountedprice', '$productQuantity', '$productTotal', '$prSku', '$prHsn', '$pkgHeight', '$pkgWidth', '$pkgLength', '$pkgWeight')");
                }

                // Clear cart after order placement
                $deleteTheItemsFromCart = $conn->execute("Delete from `cart` where `customer_id`='$userId' AND `type`='$type'");

                // Fetch customer details
                $customerDetails = $conn->getData("SELECT customers.* FROM `orders_table`
                JOIN customers ON customers.customer_id = orders_table.customer_id
                WHERE `orders_table`.`order_number` = '$orderNumber'");

                $fname = $customerDetails['first_name'];
                $lname = $customerDetails['last_name'];
                $phone = $customerDetails['phone'];
                $email = $customerDetails['email'];

                /* Shiprocket API Credentials
                define("SHIPROCKET_EMAIL", "office@ajinfotek.in");
                define("SHIPROCKET_PASSWORD", "Avais$$123$$");
                */

                /* Function to get Shiprocket authentication token
                function getAuthToken()
                {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apiv2.shiprocket.in/v1/external/auth/login',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode([
                            "email" => SHIPROCKET_EMAIL,
                            "password" => SHIPROCKET_PASSWORD
                        ]),
                        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $result = json_decode($response, true);

                    return $result['token'] ?? null;
                }
                */

                /* Function to create Shiprocket order with multiple products
                function createOrder($token, $orderProducts, $fname1, $lname1, $shippingAddress, $city, $postcode, $state, $email1, $phone1)
                {
                    $orderData = [
                        "order_id" => $_SESSION['order_number'],
                        "order_date" => date("Y-m-d H:i"),
                        "pickup_location" => "Primary",
                        "billing_customer_name" => $fname1,
                        "billing_last_name" => $lname1,
                        "billing_address" => $shippingAddress,
                        "billing_city" => $city,
                        "billing_pincode" => $postcode,
                        "billing_state" => $state,
                        "billing_country" => "India",
                        "billing_email" => $email1,
                        "billing_phone" => $phone1,
                        "shipping_is_billing" => true,
                        "order_items" => [],
                        "payment_method" => "Prepaid",
                        "shipping_charges" => 0,
                        "sub_total" => 0,
                        "length" => 0,
                        "breadth" => 0,
                        "height" => 0,
                        "weight" => 0
                    ];

                    // Initialize dimensions and totals
                    $totalWeight = 0;
                    $maxLength = 0;
                    $maxBreadth = 0;
                    $totalHeight = 0;

                    // Add products to order
                    foreach ($orderProducts as $orderPr) {
                        $orderData['order_items'][] = [
                            "name" => $orderPr['product_name'],
                            "sku" => $orderPr['sku'],
                            "units" => $orderPr['product_quantity'],
                            "selling_price" => $orderPr['product_price'],
                            "hsn" => $orderPr['hsn'],
                        ];

                        // Calculate dimensions and totals
                        $totalWeight += $orderPr['pkg_weight'] * $orderPr['product_quantity'];
                        $maxLength = max($maxLength, $orderPr['pkg_length']);
                        $maxBreadth = max($maxBreadth, $orderPr['pkg_width']);
                        $totalHeight += $orderPr['pkg_height'] * $orderPr['product_quantity'];
                        $orderData['sub_total'] += $orderPr['product_total_price'];
                    }

                    // Assign calculated dimensions to order data
                    $orderData['weight'] = $totalWeight;
                    $orderData['length'] = $maxLength;
                    $orderData['breadth'] = $maxBreadth;
                    $orderData['height'] = $totalHeight;

                    // Send API request to Shiprocket
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($orderData),
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            "Authorization: Bearer $token"
                        ),
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    return json_decode($response, true);
                }
                */

                /* Function to logout from Shiprocket
                function logout($token)
                {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apiv2.shiprocket.in/v1/external/auth/logout',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            "Authorization: Bearer $token"
                        ),
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                }
                */

                /* Step 1: Authenticate and Get Token
                $token = getAuthToken();
                if (!$token) {
                    die("Authentication failed! Please check your credentials.");
                }
                */

                // Fetching product details
                $orderProducts = $conn->getAllData("SELECT order_product_details.* FROM `orders_table`
                 JOIN order_product_details ON order_product_details.order_id = orders_table.order_id
                 WHERE `orders_table`.`order_number` = '$orderNumber'");

                // Fetching shipping details
                $shipDetails = $conn->getData("SELECT order_address.* FROM `orders_table`
                 JOIN order_address ON order_address.id = orders_table.address_id
                 WHERE `orders_table`.`order_number` = '$orderNumber'");

                $fname1 = $shipDetails['first_name'];
                $lname1 = $shipDetails['last_name'];
                $phone1 = $shipDetails['phone'];
                $email1 = $shipDetails['email'];
                $shippingAddress = $shipDetails['address'];
                $state = $shipDetails['state'];
                $city = $shipDetails['city'];
                $postcode = $shipDetails['postcode'];
                $apartment = $shipDetails['apartment'];

                /* Step 3: Create Order with multiple products
                $orderResponse = createOrder($token, $orderProducts, $fname1, $lname1, $shippingAddress, $city, $postcode, $state, $email1, $phone1);
                */

                /* Step 4: Logout
                logout($token);
                */

                $orderDetails = '';
                $productSubTotal = 0;

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
                $adminSubject = "New Eyewear Order Placed - Order #$orderNumber";
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
            <h3>You have received a new eyewear order - Order #$orderNumber</h3>
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
        
            <p><strong>Order Details for Eyewear Order #$orderNumber:</strong></p>
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
                $adminHeaders .= "From: Peura Opticals <peuraopticals@peuraopticals.com>" . "\r\n";
                mail($adminTo, $adminSubject, $adminBody, $adminHeaders);

                // Customer Email
                $customerTo = $email1 . ', rkrk03109@gmail.com';
                $customerSubject = "Thank You for Your Eyewear Order! - Order #$orderNumber";
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
            <p>Thank you for choosing Peura Opticals for your eyewear needs. We are thrilled to have you as a customer and hope that our eyewear brings you clarity, comfort, and style.</p>

            <h4>Order Details for Eyewear Order #$orderNumber:</h4>
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
            <p>Warm regards,<br>Peura Opticals Team<br>
            <a href='mailto:sameer0018khan@gmail.com'>sameer0018khan@gmail.com</a></p>
        </body>
        </html>
        ";

                $customerHeaders = "MIME-Version: 1.0" . "\r\n";
                $customerHeaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $customerHeaders .= "From: Peura Opticals <peuraopticals@peuraopticals.com>" . "\r\n";
                mail($customerTo, $customerSubject, $customerBody, $customerHeaders);
            }

            $_SESSION['msg'] = "Transaction Complete";
            unset($_SESSION['order_number']);
            header("Location: account-orders.php");
            exit();

        case 'PENDING':
            // Payment is pending
            $_SESSION['err'] = "Transaction is pending. Please check back later.";
            $_SESSION['api_response'] = $api_response;
            header("Location: thank-you.php");
            exit();

        case 'FAILED':
            $errorCode = $api_response['paymentDetails'][0]['errorCode'] ?? 'Unknown error';
            $detailedErrorCode = $api_response['paymentDetails'][0]['detailedErrorCode'] ?? 'Unknown error';

            error_log("Payment failed with error: {$errorCode}, Detailed error: {$detailedErrorCode}");

            $_SESSION['err'] = "Transaction Failed. Error: {$errorCode}";
            $_SESSION['api_response'] = $api_response;
            header("Location: thank-you.php");
            exit();

        default:
            $_SESSION['err'] = "Unknown transaction state.";
            $_SESSION['api_response'] = $api_response;
            header("Location: thank-you.php");
            exit();
    }
} else {
    $_SESSION['err'] = "Transaction state not received from PhonePe.";
    error_log("Invalid response received from PhonePe: " . print_r($api_response, true));
    header("Location: thank-you.php");
    exit();
}
?>