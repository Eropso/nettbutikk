<?php
require 'vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}


$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
$webhook_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Retrieve the raw body and signature
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $webhook_secret
    );

    // Handle the event
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        // Retrieve metadata (e.g., user_id) from the session
        $user_id = $session->metadata->user_id; // Set this when creating the Checkout session
        $total_amount = $session->amount_total / 100; // Convert from cents to dollars

        // Insert order into the database
        include("database.php");

        $sql = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->execute();
        $order_id = $conn->lastInsertId();

        // Insert order items (if applicable)
        foreach ($session->display_items as $item) {
            $product_name = $item->custom->name;
            $quantity = $item->quantity;
            $price = $item->amount / 100; // Convert from cents to dollars

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $product_name); // Replace with actual product ID if available
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);
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