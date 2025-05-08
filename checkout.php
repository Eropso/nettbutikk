<?php
require 'vendor/autoload.php';

session_start();

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Use the secret key from the environment
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];


\Stripe\Stripe::setApiKey($stripe_secret_key);

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$line_items = [];

// Loop through cart items to build the line_items array
foreach ($cart_items as $item) {
    $line_items[] = [
        "quantity" => $item['quantity'],
        "price_data" => [
            "currency" => "usd",
            "unit_amount" => intval($item['price'] * 100),
            "product_data" => [
                "name" => $item['title']
            ]
        ]
    ];
}

// Create the checkout session
$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://10.100.10.134/success.php",
    "cancel_url" => "http://10.100.10.134/index.php",
    "line_items" => $line_items,
    "metadata" => [
        "user_id" => $_SESSION['user']['id'], // Pass the user ID to the webhook
    ],
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
?>