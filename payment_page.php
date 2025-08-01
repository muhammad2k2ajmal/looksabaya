<?php
if (!isset($_SESSION)) {
    session_start();
}

// Fetch the redirect URL from the session
if (isset($_SESSION['payment_redirect_url'])) {
    $paymentUrl = $_SESSION['payment_redirect_url'];
} else {
    $_SESSION['errmsg'] = 'Payment URL not found.';
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MTGGJ2LBP1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-MTGGJ2LBP1');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhonePe Payment Gateway</title>
    <style>
        /* Style for the iframe container */
        #payment-frame-container {
            width: 100%;
            height: 600px;
            /* Adjust this as per your needs */
            border: none;
        }
    </style>
</head>

<body>
    <h2>PhonePe Payment Gateway</h2>
    <p>Please complete your payment in the iframe below:</p>

    <!-- Embed the PhonePe payment page in an iframe -->
    <iframe src="<?php echo htmlspecialchars($paymentUrl); ?>" id="payment-frame-container"></iframe>

</body>

</html>