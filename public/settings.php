<?php
session_start();

include(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['user']['id'])) {
    echo "User ID is not set in the session.";
    exit();
}

$id = $_SESSION['user']['id'];
$sql = "SELECT id, email, first_name, last_name, role FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if(isset($_POST["update"])){
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['email'];

    if(empty($first_name) || empty($last_name) || empty($email)){
        echo ("Please fill in all fields");
    }
    else{
        $sql = "UPDATE users SET first_name=:first_name, last_name=:last_name, email=:email WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['user'] = [
                'id' => $id,
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'role' => $user['role']
            ];

            header("Location: settings.php");
            exit();
        } else {
            echo "Error updating profile";
        }

    }
}
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

    <form action="" method="POST">
        <div class="user-info">
            <div class="user-container">
                <label for="first-name">First Name:</label>
                <input type="text" name="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>" id="first-name">

                <label for="last-name">Last Name:</label>
                <input type="text" name="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>" id="last-name">

                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" id="email">
                <div>
                    <button type="submit" name="update">Update</button>
                    <button type="submit" name="delete">Delete</button>
                </div>
            </div>

            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $twofa_checked = isset($_POST['twofa_checked']) ? 1 : 0;
                    $sql = 'UPDATE users SET 2fa_enabled = : 2fa_enabled WHERE id = :id';
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':2fa_enabled', $twofa_checked);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                }

                $sql = 'SELECT 2fa_enabled FROM users WHERE id = :id';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $verify_result = $stmt -> fetch(pdo::FETCH_ASSOC);


            ?>


    </form>
    <div>
        <form action="" method='POST'>
            <label>2FA</label>
            <input name='twofa_checked' type="checkbox" <?php echo $verify_result['2fa_enabled'] ? 'checked' : '' ;?>>
            <button type="submit">Save</button>
        </form>
    </div>
    



    <script src="script.js"></script>
</body>
</html>