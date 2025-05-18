<?php
session_start();
include(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/index.php");
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
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><img src="../images/close.svg" alt=""></a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="mailto:phpkuben@gmail.com">Contact</a></li>
        </ul>
        
        <ul>
            <li><a href="../public/index.php"><p>Erosho</p></a></li>
            <li class="hideOnMobile"><a href="../public/faq.php">FAQ</a></li>

            <!-- If logged in show profile, else show login -->
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="dropdown">
                    <img class="profile" src="../images/person_white.svg" alt="defaultprofile" onclick="myFunction()">
                    <div id="myDropdown" class="dropdown-content">
                        <?php $role = $_SESSION['user']['role']; if($role == 'admin'){echo '<a href="admin.php">Admin Panel</a>';} ?>
                        <a href="../public/settings.php"><img src="../images/settings.svg" alt="">Settings</a>
                        <a href="../authentication/logout.php" class="logout-button"><img src="../images/logout.svg" alt="">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../authentication/login.php" class="login-button"><img src="../images/person_white.svg" alt="">Login</a>
            <?php endif; ?>            
            <li><a href="../public/cart.php"><img src="../images/shopping_bag.svg" alt=""></a></li>
            <li class="menu-button" onclick=showSidebar()><a href="#"><img src="../images/menu.svg" alt=""></a></li>

        </ul>
    </nav>
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

    <script src="../public/script.js"></script>

</body>
</html>