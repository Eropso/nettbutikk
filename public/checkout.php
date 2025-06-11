<?php
require '../vendor/autoload.php';
session_start();

// Load env
if (file_exists(__DIR__ . '/../config/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
    $dotenv->load();
}

//Set secret key
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
\Stripe\Stripe::setApiKey($stripe_secret_key);




$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$line_items = [];

foreach ($cart_items as $product_id => $item) {
    // Stripe expects price in cents
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
    // Prepare cart metadata for webhook
    $cart_metadata[] = [
        'product_id' => $product_id,
        'price' => $item['price'],
        'quantity' => $item['quantity']
    ];
}


// Create Stripe Checkout session
$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "https://eropso.com/public/success.php",
    "cancel_url" => "https://eropso.com/public/cart.php",
    "line_items" => $line_items,
    "metadata" => [
        "user_id" => $_SESSION['user']['id'],
        "cart" => json_encode($cart_metadata)
    ],
]);


header("Location: " . $checkout_session->url);
exit();
?>