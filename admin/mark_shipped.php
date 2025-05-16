<?php
session_start();
include(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

$order_id = $_GET['id'] ?? null;
if ($order_id) {
    $sql = "UPDATE orders SET status = 'shipped' WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $order_id);
    $stmt->execute();
}
header("Location: admin.php");
exit();


