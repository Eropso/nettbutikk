<?php
session_start();
include("database.php");

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user']['id']; // Assuming the user is logged in
    $total_amount = 0;

    // Calculate total amount
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Insert order into `orders` table
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Insert order items into `order_items` table
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':price', $item['price']);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    echo "Thank you for your purchase! Your order has been registered.";
} else {
    echo "No items in the cart.";
}
?>