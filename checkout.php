<?php
require 'vendor/autoload.php';

session_start();

$stripe_secret_key = "sk_test_51R9NB7FRlyPBVzYQWq5Zr6lHVjN7unlY0aeVavjMW0IhMJtjgIwGTaBF8HPvRZLnvguqgZ4iEEMuD4NoOD15gPhF00AJfVC15Q";

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
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
?>