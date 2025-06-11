<?php
require '../vendor/autoload.php';

// Load env
if (file_exists(__DIR__ . '/../config/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
    $dotenv->load();
}

//Set secret key
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
\Stripe\Stripe::setApiKey($stripe_secret_key);
$webhook_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];


// Read Stripe webhook payload and signature
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    // Verify and parse the Stripe event
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);


    //If successful
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        // Get user ID and cart from metadata
        $user_id = $session->metadata->user_id;
        $cart = json_decode($session->metadata->cart, true);
        $total_amount = $session->amount_total / 100;

        include(__DIR__ . '/../config/database.php');
        
        // Insert orders
        $sql = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->execute();
        $order_id = $conn->lastInsertId();

        // Insert each order item
        foreach ($cart as $item) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $item['product_id']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':price', $item['price']);
            $stmt->execute();
        }
    }

    // Respond OK to Stripe
    http_response_code(200);
} catch (Exception $e) {
    // Error message
    http_response_code(400);
    exit();
}
?>