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
                <?php foreach ($_SESSION['cart'] as $ixd => $item): ?>
                    <tr>
                        <td><?php echo $item['title']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>
                        <td id="quantity-container"><img src="images/remove.svg" alt="" onclick="changeQuantity(-1)"><input type="text" name="quantity" id="quantity" value="<?php echo $item['quantity']; ?>" min="1"><img src="images/add.svg" alt="" onclick="changeQuantity(1)"></td>
                        <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                        <td><a href="cart.php?remove=<?php echo $ixd; ?>">Remove</a></td>                    </tr>
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
            <a href="authentication/login.php">Proceed to Checkout</a>
        <?php else: ?>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>


    <script src="script.js"></script>
</body>
</html>