<?php
session_start();
include("database.php");


$product_id = $_GET['id'];

$sql = "SELECT title, description, price, quantity, img FROM products WHERE id = :product_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><img src="images/close.svg" alt=""></a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="mailto:phpkuben@gmail.com">Contact</a></li>
        </ul>
        
        <ul>
            <li><a class="erobank-logo" href="index.php"><p>Erosho</p></a></li>
            <li class="hideOnMobile"><a href="about.php">About</a></li>
            <li class="hideOnMobile"><a href="#">FAQ</a></li>

            <!-- If logged in show profile else show login -->
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="dropdown">
                    <img class="profile" src="images/person_white.svg" alt="defaultprofile" onclick="myFunction()">
                    <div id="myDropdown" class="dropdown-content">
                        <?php $role = $_SESSION['user']['role']; if($role == 'admin'){echo '<a href="admin.php">Admin Panel</a>';} ?>
                        <a href="settings.php"><img src="images/settings.svg" alt="">Settings</a>
                        <a href="authentication/logout.php" class="logout-button"><img src="images/logout.svg" alt="">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="authentication/login.php" class="login-button"><img src="images/person_white.svg" alt="">Login</a>
            <?php endif; ?>            
            <li class="menu-button" onclick=showSidebar()><a href="#"><img src="images/menu.svg" alt=""></a></li>
            <li><a href="cart.php"><img src="images/shopping_bag.svg" alt=""></a></li>

        </ul>
    </nav>


    <div class="content-container">


        <div class="product-description-container">
            <div class="product-description-image">
                <img src="<?php echo $result['img'] ?>" alt="">
            </div>

            <div>
                <h1><?php echo $result['title']?></h1>
                <p><?php echo $result['description'] ?></p>

                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="product_title" value="<?php echo $result['title']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $result['price']; ?>">
                    <div class="quantity">
                        <label for="quantity">Quantity:</label>
                        <div id="quantity-container">
                            <img src="images/remove.svg" alt="Decrease" id="decrease-quantity" onclick="changeQuantity(-1)">
                            <input type="text" name="quantity" id="quantity" value="1" min="1" required>
                            <img src="images/add.svg" alt="Increase" id="increase-quantity" onclick="changeQuantity(1)">
                        </div>
                    </div>
                    <button class="purchase" type="submit">Add to Cart</button>
                </form>
            </div>

        </div>


    </div>


    <script src="script.js"></script>



</body>
</html>