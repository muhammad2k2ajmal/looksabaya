<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent any output before JSON response
ob_start();

// Set JSON content type
header('Content-Type: application/json');

ini_set('display_errors', 0); // Disable error display
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Include the database connection and common files
require 'config/config.php';
require 'config/common.php';

try {
    $conn = new dbClass();
    $contact = new Contact($conn); // Pass dbClass instance to Contact

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate inputs
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));

        // Check if required fields are empty
        if (empty($name) || empty($subject)) {
            echo json_encode(['status' => 'error', 'message' => 'Name and subject are required.']);
            exit();
        }

        // Call the method to insert data into the database
        if ($contact->addContact($name, $subject, $email, $message)) {
            echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'There was an error while submitting your message. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
} catch (Exception $e) {
    // Log the error (you can implement actual logging if needed)
    error_log("Error in contactsubmit.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again later.']);
}

// Clear output buffer to ensure no stray output
ob_end_flush();
exit();
?>