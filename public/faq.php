<?php
session_start()
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
    
    <div class="faq-container">
        <div class="faq-content">
            <div id="faq-item">
                <h3>What is Erosho?</h3>
                <p>Erosho is an Ecommerce platform providing a wide range of products available for purchase</p>
            </div>


            <div id="faq-item">
                <h3>Do I have to make an account to use the service?</h3>
                <p>If you want to make an purchase, you have to be logged in.</p>
            </div>


            <div id="faq-item">
                <h3>Do you accept refunds?</h3>
                <p>Yes, you have 14 days after receiving the product to return it in its original packaging. Please note that return shipping costs are not covered by us.</p>
            </div>
            

            <div id="faq-item">

            </div>


            <div id="faq-item">
                <h3>How do I contact support?</h3>
                <p>If you have any questions or issues, you can contact us at <a href="mailto:phpkuben@gmail.com">phpkuben@gmail.com</a>. We're happy to assist you!</p>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>

</body>
</html>