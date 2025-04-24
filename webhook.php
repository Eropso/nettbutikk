<?php
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Retrieve the raw body and signature from Stripe
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );

    // Handle the event
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        // Retrieve the session details
        $customer_email = $session->customer_details->email;
        $amount_total = $session->amount_total / 100; // Convert from cents to dollars
        $payment_status = $session->payment_status;

        if ($payment_status === 'paid') {
            // Insert order into the database
            include("database.php");

            $sql = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user']['id']); // Replace with actual user ID
            $stmt->bindParam(':total_amount', $amount_total);
            $stmt->execute();
        }
    }

    http_response_code(200); // Acknowledge receipt of the event
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}
?>