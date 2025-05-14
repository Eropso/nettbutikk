<?php
session_start();
// Optionally clear the cart after payment
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Your payment was successful. Your order is being processed.</p>
</body>
</html>
