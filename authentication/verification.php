<?php 
session_start();
include(__DIR__ . '/../config/database.php');

$verification_code = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_NUMBER_INT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($verification_code)) {
        echo 'Please fill in Verification code';
        return;
    } else {
        if ($verification_code == $_SESSION['verification_code']) {
            if (isset($_SESSION['is_new_user']) && $_SESSION['is_new_user'] === true) {
                // Handle new user registration
                $first_name = $_SESSION['new_user']['first_name'];
                $last_name = $_SESSION['new_user']['last_name'];
                $email = $_SESSION['new_user']['email'];
                $password = $_SESSION['new_user']['password'];

                $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    // Fetch the newly created user
                    $user_id = $conn->lastInsertId();
                    $sql = "SELECT id, email, first_name, last_name, role FROM users WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id', $user_id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Set session variables
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'role' => $user['role']
                    ];
                    unset($_SESSION['verification_code']);
                    unset($_SESSION['new_user']);
                    unset($_SESSION['is_new_user']);

                    
                    if (isset($_SESSION['login-from-cart'])){
                        header("Location: ../public/checkout.php");
                        exit();
                    }
                    else{
                        header('Location: ../index.php');
                        exit();
                    }

                } else {
                    echo 'Error creating user';
                }
            } else {
                // Handle existing user verification
                $email = $_SESSION['email'];
                $sql = "SELECT id, email, first_name, last_name, role FROM users WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Set session variables
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'role' => $user['role']
                    ];
                    unset($_SESSION['verification_code']);
                    unset($_SESSION['email']);
                    unset($_SESSION['is_new_user']);


                    if (isset($_SESSION['login-from-cart'])){
                        header("Location: ../public/cart.php");
                        exit();
                    }
                    else{
                        header('Location: ../index.php');
                        exit();
                    }

                } else {
                    echo 'User not found';
                }
            }
        } else {
            echo 'Invalid Verification Code';
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
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="register_form">
        <form action="" method="POST">
            <div class="register_inputs">
                <label for="verification_code">Verification code:</label>
                <input type="verification_code" name="verification_code" id="verification_code" required>
                <input type="submit" value="Enter">
            </div>
        </form>
    </div>

</body>
</html>