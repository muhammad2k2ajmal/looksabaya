<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$conn = new dbClass();
$product = new CommProducts();
$common = new CommProducts();

// $banners=$common->getAllBanners();
// $testimonials=$common->getAllTestimonials();
// var_dump($banners);
$customer = new CommCustomers();

$orderTable = new OrderPage();
$orderdetails = new OrderPage();

$orderData = $orderTable->getAllOrder($_SESSION['USER_LOGIN']);

$customer->checkSession($_SESSION['USER_LOGIN'] ?? null);
$userDetail = $customer->userDetails($_SESSION['USER_LOGIN']);
$id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : NULL;
if ($id == NULL) {
    header('location: index.php');
}
$order = $orderdetails->getOrderById($id);
$orderproducts = $orderdetails->getProductOrderDetailsById($id);

// Shiprocket API Credentials
$shiprocketEmail = "office@ajinfotek.in";
$shiprocketPassword = "Avais$$123$$";
$orderId = $order['order_number'];
$channelId = null;

// Function to get Shiprocket API Token
function getShiprocketToken($email, $password)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/auth/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["email" => $email, "password" => $password]),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
        return ["error" => "cURL Error: " . $err];
    $result = json_decode($response, true);
    return $result['token'] ?? null;
}

// Function to track Shiprocket Order by Order ID
function trackShiprocketOrder($token, $orderId, $channelId = null)
{
    $url = "https://apiv2.shiprocket.in/v1/external/courier/track?order_id=" . urlencode($orderId);
    if ($channelId)
        $url .= "&channel_id=" . urlencode($channelId);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $token, "Content-Type: application/json"],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
        return ["error" => "cURL Error: " . $err];
    return json_decode($response, true);
}

// Function to fetch order details (fallback when tracking is unavailable)
function fetchShiprocketOrderDetails($token, $orderId)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders?search=" . urlencode($orderId),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $token, "Content-Type: application/json"],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err)
        return ["error" => "cURL Error: " . $err];
    return json_decode($response, true);
}

// Get Shiprocket API Token
$token = getShiprocketToken($shiprocketEmail, $shiprocketPassword);
if (!$token || isset($token['error'])) {
    die("Failed to authenticate with Shiprocket API: " . ($token['error'] ?? "Unknown error"));
}

// Track the order
$trackingData = trackShiprocketOrder($token, $orderId, $channelId);

// Fetch order details as fallback
$orderDetails = fetchShiprocketOrderDetails($token, $orderId);

// Handle API responses
if (isset($trackingData['error']) || empty($trackingData[0]['tracking_data']['shipment_track'])) {
    // Fallback to Shiprocket order details or local order data
    if (isset($orderDetails['data']) && !empty($orderDetails['data'])) {
        $found = false;
        foreach ($orderDetails['data'] as $shiprocketOrder) {
            if ($shiprocketOrder['channel_order_id'] == $orderId) {
                $found = true;
                $courierName = !empty($shiprocketOrder['shipments'][0]['courier']) ? $shiprocketOrder['shipments'][0]['courier'] : 'Not Assigned';
                $trackingId = !empty($shiprocketOrder['shipments'][0]['awb']) ? $shiprocketOrder['shipments'][0]['awb'] : 'N/A';
                $activities = !empty($shiprocketOrder['activities']) ? array_map(function ($activity) use ($shiprocketOrder) {
                    return [
                        'date' => $shiprocketOrder['updated_at'],
                        'activity' => $activity,
                        'location' => 'N/A'
                    ];
                }, $shiprocketOrder['activities']) : [];
                $expectedDeliveryDate = ($shiprocketOrder['shipments'][0]['etd'] !== '0000-00-00 00:00:00' && !empty($shiprocketOrder['shipments'][0]['etd']))
                    ? date('d M Y', strtotime($shiprocketOrder['shipments'][0]['etd'])) : 'N/A';
                $currentStatusText = $shiprocketOrder['status'];
                break;
            }
        }
        if (!$found) {
            // Use local order data if Shiprocket order not found
            $orderPlacedDate = date('d M Y', strtotime($order['created_at']));
            $courierName = 'Not Assigned';
            $trackingId = 'N/A';
            $activities = [['date' => $order['created_at'], 'activity' => 'Order Placed', 'location' => 'N/A']];
            $expectedDeliveryDate = 'N/A';
            $currentStatusText = $order['status'] ?? 'Pending';
        }
    } else {
        // No Shiprocket data, fallback to local order
        $orderPlacedDate = date('d M Y', strtotime($order['created_at']));
        $courierName = 'Not Assigned';
        $trackingId = 'N/A';
        $activities = [['date' => $order['created_at'], 'activity' => 'Order Placed', 'location' => 'N/A']];
        $expectedDeliveryDate = 'N/A';
        $currentStatusText = $order['status'] ?? 'Pending';
        $errorMessage = $trackingData['error'] ?? "We're getting your order ready to ship! Tracking info will appear here once we hand over your order to our delivery partner. Hang tight!";
    }
} else {
    // Use tracking data if available
    $tracking = $trackingData[0]['tracking_data'];
    $shipmentTrack = $tracking['shipment_track'][0] ?? [];
    $orderPlacedDate = !empty($tracking['shipment_track_activities'])
        ? date('d M Y', strtotime(end($tracking['shipment_track_activities'])['date']))
        : date('d M Y', strtotime($order['created_at']));
    $courierName = !empty($shipmentTrack['courier_name']) ? $shipmentTrack['courier_name'] : 'Not Assigned';
    $trackingId = !empty($shipmentTrack['awb_code']) ? $shipmentTrack['awb_code'] : 'N/A';
    $activities = !empty($tracking['shipment_track_activities']) ? $tracking['shipment_track_activities'] : [];
    $expectedDeliveryDate = !empty($shipmentTrack['edd']) ? date('d-F-Y', strtotime($shipmentTrack['edd'])) : 'N/A';
    $currentStatusText = $shipmentTrack['current_status'] ?? 'N/A';
}

// Determine redirect URL for Shiprocket tracking page
$redirectUrl = $trackingId !== 'N/A' ? "https://shiprocket.co/tracking/$trackingId" : "#";
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
    <link rel="stylesheet" href="ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="icon" href="images/favicon.png" type="image/x-icon"> -->
    <!-- <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />


</head>

<body>
    <div id="pageWrapper">
        <?php include 'include/header.php'; ?>


        <div class="container pt-20 pb-20">
            <div class="card">
                <h3>Order Id : #1682929039</h3>

                <div class="section-header">Products</div>
                <div class="product-list">
                     <div class="product-item">
                        
                        <div class="product-details">
                            <p>Image</p>
                        </div>
                        <div class="product-details">
                            <p>Name</p>
                        </div>
                        <div class="product-meta">
                            <span class="price">Price</span>
                            <span class="quantity">Quantity</span>
                            <span class="subtotal">Sub Total</span>
                        </div>
                    </div>
                    <?php
                        $total = 0;
                        foreach ($orderproducts as $row):
                            $product_id = (int) $row['product_id'];

                            $productData = $product->getProductsById($product_id);
                            // var_dump($row);
                            $total = $total + $row['product_total_price'];

                            ?>
                    <div class="product-item">
                        <img src="adminUploads/products/<?= $productData['image'] ?>" alt="Product Image">
                        <div class="product-details">
                            <p><?= $productData['name'] ?></p>
                        </div>
                        <div class="product-meta">
                            <span class="price">₹<?= number_format($row['product_price'], 2) ?></span>
                            <span class="quantity"><?= $row['product_quantity'] ?></span>
                            <span class="subtotal">₹<?= number_format($row['product_total_price'], 2) ?></span>
                        </div>
                    </div>
                    <?php
                        endforeach;
                        $gst=$total*0.18;
                        $shippingCharge=$order['shipping_charge']
                        ?>
                    <!-- Add more product items here if needed -->
                </div>

                <hr class="my-4">

                <div class="summary-section">
                    
                    <div class="summary-row">
                        <span>Subtotal Amount</span>
                        <span class="text-end">₹<?php echo $order['subtotal']; ?></span>
                    </div>
                    <!-- <div class="summary-row">
                        <span>Gst (18%)</span>
                        <span class="text-end">₹<?php echo $gst; ?></span>
                    </div> -->
                    <div class="summary-row">
                        <span>Shipping Charge</span>
                        <span class="text-end">₹<?php echo $shippingCharge; ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Grand Total Amount</span>
                        <span class="text-end">₹<?php echo $order['total'] + $shippingCharge; ?></span>
                    </div>
                </div>

                <hr class="my-4">

                <div class="order-tracking-section">
                    <div class="section-header">Order Tracking</div>
                    <?php if (isset($errorMessage)): ?>
                        <div class="status-section">
                            <h2>Tracking Status</h2>
                            <p><?php echo htmlspecialchars($errorMessage); ?></p>
                        </div>
                    <?php else: ?>
                    <div class="tracking-header">
                        <div class="tracking-status">
                            <p>Status: <span><?php echo htmlspecialchars($currentStatusText); ?></span></p>
                            <p>Expected Delivery Date: <?php echo htmlspecialchars($expectedDeliveryDate); ?></p>
                        </div>
                        <div class="tracking-action">
                            <button class="btn order-status-btn">Order Status</button>
                        </div>
                    </div>

                    <p class="mb-3">Not</p> <!-- This "Not" text is present in the image -->

                    <ul class="timeline">
                        
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $event): ?>
                        <li class="timeline-item">
                            <div class="timeline-date"><?php echo strtoupper(date('d M', strtotime($event['date']))); ?><br><?php echo date('h:i A', strtotime($event['date'])); ?></div>
                            <div class="timeline-content">
                                <strong>Activity: <?php echo htmlspecialchars($event['activity']); ?></strong>
                                <span>Location: <?php echo htmlspecialchars($event['location'] ?? 'N/A'); ?></span>
                            </div>
                        </li>
                        
                                            <?php endforeach; ?>
                                        <?php else: ?>  
                                            <p>No tracking events available.</p>
                                        <?php endif; ?>
                        <!-- <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: UPDATE PICKUP ADDRESS</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: ADDRESS_MODIFIED</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: DIMENSIONS_EDITED</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: UPDATE PICKUP ADDRESS</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: DIMENSIONS_EDITED</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-date">26 MAR<br>11:33 PM</div>
                            <div class="timeline-content">
                                <strong>Activity: LABEL_GENERATED</strong>
                                <span>Location: N/A</span>
                            </div>
                        </li> -->
                    </ul>
                                <?php endif; ?>

                </div>
            </div>
        </div>


    </div>
		<?php include 'include/footer.php';?>

</body>

<script src="js/jquery.min.js" defer=""></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>

</html>