<?php
require '../vendor/autoload.php';

if (file_exists(__DIR__ . '/../config/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
    $dotenv->load();
}

$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
$webhook_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];
\Stripe\Stripe::setApiKey($stripe_secret_key);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $webhook_secret
    );

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        $user_id = $session->metadata->user_id;
        $cart = json_decode($session->metadata->cart, true);
        $total_amount = $session->amount_total / 100;

        include(__DIR__ . '/../config/database.php');
        
        $sql = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->execute();
        $order_id = $conn->lastInsertId();

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

    http_response_code(200);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}
?>