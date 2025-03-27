<?php
session_start();
include("database.php");

$sql = "SELECT title, price, img FROM products";
$stmt = $conn->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><img src="images/close.svg" alt=""></a></li>
            <li><a href="Dashboard.php">Dashboard</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="mailto:phpkuben@gmail.com">Contact</a></li>
        </ul>
        
        <ul>
            <li><a class="erobank-logo" href="index.php"><p>Erosho</p></a></li>
            <li class="hideOnMobile"><a href="dashboard.php">Dashboard</a></li>
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
        </ul>
    </nav>



    
    <div class="product-catalog">
        <?php  foreach ($result as $product):  ?>
            <a href="product.php?id=<?php echo $product['id']; ?>" class="product-container">
                <img src="<?php echo $product['img'] ?>" alt="">
                <h1><?php echo $product['title']; ?></h1>

            </a>
        <?php endforeach; ?>
    </div>




    <script src="script.js"></script>
</body>
</html>