<?php
session_start();
include("../database.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    echo "Order ID missing.";
    exit();
}

// Get order info
$sql = "SELECT o.*, u.email, u.first_name, u.last_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $order_id);
$stmt->execute();
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found.";
    exit();
}

// Get order items
$sql = "SELECT oi.quantity, p.title, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Order #<?= $order['id'] ?> Details</h1>
    <p>User: <?= htmlspecialchars($order['email']) ?> (<?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?>)</p>
    <p>Date: <?= $order['order_date'] ?></p>
    <p>Status: <?= $order['status'] ?></p>
    <h2>Products</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['title']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>$<?= $item['price'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin.php">Back to orders</a>
</body>
</html>