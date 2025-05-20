<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}



// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_title = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'title' => $product_title,
            'price' => $product_price,
            'quantity' => $quantity,
        ];
    }

    header('Location: cart.php');
    exit();
}

// Handle removing items from the cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><img src="../images/close.svg" alt=""></a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="mailto:phpkuben@gmail.com">Contact</a></li>
        </ul>
        
        <ul>
            <li><a href="../index.php"><p>Erosho</p></a></li>
            <li class="hideOnMobile"><a href="faq.php">FAQ</a></li>

            <!-- If logged in show profile, else show login -->
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="dropdown">
                    <img class="profile" src="../images/person_white.svg" alt="defaultprofile" onclick="myFunction()">
                    <div id="myDropdown" class="dropdown-content">
                        <?php $role = $_SESSION['user']['role']; if($role == 'admin'){echo '<a href="../admin/admin.php">Admin Panel</a>';} ?>
                        <a href="settings.php"><img src="../images/settings.svg" alt="">Settings</a>
                        <a href="../authentication/logout.php" class="logout-button"><img src="../images/logout.svg" alt="">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../authentication/login.php" class="login-button"><img src="../images/person_white.svg" alt="">Login</a>
            <?php endif; ?>            
            <li><a href="cart.php"><img src="../images/shopping_bag.svg" alt=""></a></li>
            <li class="menu-button" onclick=showSidebar()><a href="#"><img src="../images/menu.svg" alt=""></a></li>

        </ul>
    </nav>

    <h1>Your Shopping Cart</h1>
    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                    <tr>
                        <td><?php echo $item['title']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>

                        <td id="quantity-container">
                            <img src="../images/remove.svg" alt="" onclick="changeQuantity('<?php echo $product_id; ?>', -1)">
                            <input type="text" name="quantity_<?php echo $product_id; ?>" id="quantity_<?php echo $product_id; ?>" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                            <img src="../images/add.svg" alt="" onclick="changeQuantity('<?php echo $product_id; ?>', 1)">
                        </td>

                        <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                        <td><a href="cart.php?remove=<?php echo $product_id; ?>">Remove</a></td>                    
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total:</strong> $
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            echo $total;
            ?>
        </p>
        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
            <?php $_SESSION['login-from-cart'] = true; ?>
            <a href="../authentication/login.php">Proceed to Checkout</a>
        <?php else: ?>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>


    <script src="script.js"></script>
</body>
</html>