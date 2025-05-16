<?php
session_start();
include(__DIR__ . '/../config/database.php');

// Only allow admin
if (!isset($_SESSION['loggedin']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT o.id, o.order_date, o.total_amount, o.status, u.email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.order_date DESC";
$stmt = $conn->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><img src="images/close.svg" alt=""></a></li>
            <li><a href="../public/faq.php">FAQ</a></li>
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
                        <a href="settings.php"><img src="../images/settings.svg" alt="">Settings</a>
                        <a href="authentication/logout.php" class="logout-button"><img src="../images/logout.svg" alt="">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../authentication/login.php" class="login-button"><img src="../images/person_white.svg" alt="">Login</a>
            <?php endif; ?>            
            <li><a href="../public/cart.php"><img src="../images/shopping_bag.svg" alt=""></a></li>
            <li class="menu-button" onclick=showSidebar()><a href="#"><img src="../images/menu.svg" alt=""></a></li>

        </ul>
    </nav>

    <h1>All Orders</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User Email</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Details</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['email']) ?></td>
            <td><?= $order['order_date'] ?></td>
            <td>$<?= $order['total_amount'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
                <a href="order_details.php?id=<?= $order['id'] ?>">View</a>
                <?php if ($order['status'] !== 'shipped'): ?>
                    | <a href="mark_shipped.php?id=<?= $order['id'] ?>">Mark as shipped</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    

</body>
</html>