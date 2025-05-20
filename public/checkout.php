<?php
require '../vendor/autoload.php';
session_start();

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
\Stripe\Stripe::setApiKey($stripe_secret_key);

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$line_items = [];

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

// Pass cart as JSON in metadata
$cart_metadata = [];
foreach ($_SESSION['cart'] as $product_id => $item) {
    $cart_metadata[] = [
        'product_id' => $product_id,
        'title' => $item['title'],
        'price' => $item['price'],
        'quantity' => $item['quantity']
    ];
}

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "https://eropso/public/success.php",
    "cancel_url" => "https://eropso/index.php",
    "line_items" => $line_items,
    "metadata" => [
        "user_id" => $_SESSION['user']['id'],
        "cart" => json_encode($cart_metadata)
    ],
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
exit();
?>