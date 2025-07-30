<?php
session_start();
require 'config/calculate-shipping.php';

header('Content-Type: application/json');

if (isset($_POST['postcode']) && !empty($_POST['postcode'])) {
    $calculator = new PincodeDistanceCalculator();
    $shippingCharge = $calculator->calculatePrice($_POST['postcode']);
    if (is_array($shippingCharge) && isset($shippingCharge['error'])) {
        echo json_encode(['error' => $shippingCharge['error']]);
    } else {
        echo json_encode(['shippingCharge' => $shippingCharge]);
    }
} else {
    echo json_encode(['error' => 'Invalid postcode']);
}
exit;
?>