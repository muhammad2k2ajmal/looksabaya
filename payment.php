<?php

require 'vendor/autoload.php';
use PhonePe\Env;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;
use PhonePe\payments\v1\PhonePePaymentClient;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;

// Define credentials 
$clientId = 'SU2507281630468558993691';
$clientSecret = 'ee67cd0e-9f46-4431-b6bc-914cf33a521b';
$clientVersion = 1;

// Define file to store the token temporarily
$accessTokenFile = 'access_token.json';

// Reusable function to handle cURL requests
function makeCurlRequest($url, $data = null, $headers = [])
{
    $ch = curl_init();

    // Default cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    if ($data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if ($response === false) {
        error_log('cURL Error: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Function to fetch a new access token securely
function fetchAccessToken($clientId, $clientSecret, $clientVersion)
{
    $url = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
    $data = [
        'client_id' => $clientId,
        'client_version' => $clientVersion,
        'client_secret' => $clientSecret,
        'grant_type' => 'client_credentials'
    ];

    $responseData = makeCurlRequest($url, http_build_query($data), ['Content-Type: application/x-www-form-urlencoded']);

    if (isset($responseData['access_token'])) {
        return $responseData;
    } else {
        error_log('Error fetching access token: ' . print_r($responseData, true));
        return false;
    }
}

// Function to check if the token is expired and refresh it
function getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile)
{
    if (file_exists($accessTokenFile)) {
        $tokenData = json_decode(file_get_contents($accessTokenFile), true);

        if (isset($tokenData['access_token']) && time() < $tokenData['expires_at'] - (20 * 60)) {
            return $tokenData['access_token'];
        }
    }

    $tokenData = fetchAccessToken($clientId, $clientSecret, $clientVersion);

    if ($tokenData) {
        $expiryTime = time() + 3600;
        file_put_contents($accessTokenFile, json_encode([
            'access_token' => $tokenData['access_token'],
            'expires_at' => $expiryTime
        ]));
        return $tokenData['access_token'];
    }

    return false;
}

// Handle payment initiation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the access token (this will fetch a new token if expired or about to expire)
    $shipId = trim($_POST['shipId']);
    $billId = trim($_POST['billId']);
    $totaAmt = trim($_POST['totalAmount']);
    $type = trim($_POST['type']);
    $accessToken = getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile);

    if (!$accessToken) {
        echo 'Failed to retrieve access token. Please try again later.';
        exit;
    }

    $merchantTransactionId = 'ABAYA' . time() . rand(1000, 9999);

    // The data array for the payment request
    $data = [
        "merchantOrderId" => $merchantTransactionId,
        "amount" => 100 * $totaAmt,
        "paymentFlow" => [
            "type" => "PG_CHECKOUT",
            "message" => "Payment message used for collect requests",
            "merchantUrls" => [
                 'redirectUrl' => 'https://abayalooks.com/update-shipping.php?transactionId=' . $merchantTransactionId . '&shipId=' . $shipId . '&billId=' . $billId . '&type=' . $type,
                 'redirectMode' => 'POST',
                 'callbackUrl' => 'https://abayalooks.com/update-shipping.php?transactionId=' . $merchantTransactionId . '&shipId=' . $shipId . '&billId=' . $billId . '&type=' . $type,
            ]
        ]
    ];

    // Initialize cURL session for payment request
    $headers = [
        'Content-Type: application/json',
        'Authorization: O-Bearer ' . $accessToken
    ];
    $url = 'https://api.phonepe.com/apis/pg/checkout/v2/pay';

    $responseData = makeCurlRequest($url, json_encode($data), $headers);

    if (!$responseData) {
        echo 'Payment initiation failed. Please try again later.';
        exit;
    }

    // Log the request and response for debugging purposes
    file_put_contents('request_log.txt', print_r($data, true), FILE_APPEND);
    file_put_contents('response_log.txt', print_r($responseData, true), FILE_APPEND);

    if (isset($responseData['redirectUrl'])) {
        // Return the redirect URL in the response
        echo json_encode(['redirectUrl' => $responseData['redirectUrl']]);
        exit;
    } else {
        echo 'Payment initiation failed. Please try again later. Response: ' . print_r($responseData, true);
        exit;
    }
} else {
    echo 'Invalid request method. Please use POST to make a payment.';
    exit;
}
?>
