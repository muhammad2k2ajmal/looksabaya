<?php
if (!isset($_SESSION)) {
    session_start();
}

require "config/config.php";
$conn = new dbClass();

// Define file to store the token temporarily
$accessTokenFile = 'access_token.json';

// Function to get the access token from the file
function getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile) {
    if (file_exists($accessTokenFile)) {
        $tokenData = json_decode(file_get_contents($accessTokenFile), true);

        if (isset($tokenData['access_token']) && time() < $tokenData['expires_at'] - (1 * 60)) {
            return $tokenData['access_token'];
        }
    }

    return false;
}

// Extract the merchant order ID from the callback request
if (!isset($_REQUEST['transactionId'])) {
    // If the transactionId is missing, store the error message in session and redirect
    $_SESSION['err'] = "Transaction ID is missing";
    header("Location: missing.php");  // Redirect user to thank-you page or error page
    exit();  // Ensure the script stops after the redirect
}

$merchantOrderId = $_REQUEST['transactionId'];  // Get the transactionId from POST data

// Get the access token (this will fetch a new token if expired or about to expire)
$accessToken = getAccessToken($clientId, $clientSecret, $clientVersion, $accessTokenFile);

if (!$accessToken) {
    error_log('Failed to retrieve access token from the stored file.');
    exit('Access token retrieval failed.');
}

// Construct the status check URL and headers
$phonePeStatusUrl = "https://api.phonepe.com/apis/pg/checkout/v2/order/{$merchantOrderId}/status";
$headers = [
    'Content-Type: application/json',
    'Authorization: O-Bearer ' . $accessToken
];

// Initialize cURL for status check
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $phonePeStatusUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);


curl_close($ch);

// Decode the response to get it as an associative array
$api_response = json_decode($response, true);


// Log incoming callback details for debugging purposes
file_put_contents('callback_log.txt', "Headers: " . print_r(getallheaders(), true) . PHP_EOL, FILE_APPEND);
file_put_contents('callback_log.txt', "Raw Response: " . print_r($response, true) . PHP_EOL, FILE_APPEND);

if (isset($api_response['state'])) {
    $orderState = $api_response['state'];  // Use the correct path to access the state

    // Handle different transaction states
    switch ($orderState) {
        case 'COMPLETED':
            // Payment was successful
            $paymentDetails = $api_response['paymentDetails'][0];  // Assuming paymentDetails is an array
            $transactionId = $paymentDetails['transactionId'];
            $amount = $paymentDetails['amount'];
            $paymentMode = $paymentDetails['paymentMode'];

            // Insert payment details into the database
            $insertQuery = "INSERT INTO payment (id, status, amount, customer_id, txt_id) 
                            VALUES (:orderId, :status, :amount, :customerId, :txtId)";
            $insertParams = [
                ':orderId' => $merchantOrderId,
                ':status' => $orderState,
                ':amount' => $amount,
                ':customerId' => 123, // You should retrieve the actual customer ID
                ':txtId' => $transactionId
            ];
            $conn->executeStatement($insertQuery, $insertParams);

            // Store session message and redirect to the success page
            $_SESSION['msg'] = "Transaction Complete, We will contact you shortly";
            $_SESSION['api_response'] = $api_response;
            header("Location: receipt.php");
            exit();

        case 'PENDING':
            // Payment is pending
            $_SESSION['err'] = "Transaction is pending. Please check back later.";
            $_SESSION['api_response'] = $api_response;
            header("Location: pending.php");
            exit();

        case 'FAILED':
            // Payment failed
            $errorCode = $api_response['paymentDetails'][0]['errorCode'] ?? 'Unknown error';
            $detailedErrorCode = $api_response['paymentDetails'][0]['detailedErrorCode'] ?? 'Unknown error';

            // Log the error details
            error_log("Payment failed with error: {$errorCode}, Detailed error: {$detailedErrorCode}");

            $_SESSION['err'] = "Transaction Failed. Error: {$errorCode}";
            $_SESSION['api_response'] = $api_response;
            header("Location: fail.php");
            exit();

        default:
            // Unknown state, handle gracefully
            $_SESSION['err'] = "Unknown transaction state.";
            $_SESSION['api_response'] = $api_response;
            header("Location: unknown.php");
            exit();
    }
} else {
    // If no state is returned, log the error and inform the user
    $_SESSION['err'] = "Transaction state not received from PhonePe.";
    error_log("Invalid response received from PhonePe: " . print_r($api_response, true));
    header("Location: thank-you.php");
    exit();
}
?>
