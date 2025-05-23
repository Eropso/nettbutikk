<?php 
session_start();
include(__DIR__ . '/../config/database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password');


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($email) || empty($password)){
        echo 'Please fill in all fields';
        return;
    }
    else {
        $sql = "SELECT id, email, first_name, last_name, password, role FROM users WHERE email = :email;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $sql = 'SELECT 2fa_enabled FROM users WHERE email = :email';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $verify_result=$stmt->fetch(pdo::FETCH_ASSOC);

            if ($verify_result['2fa_enabled'] == 1){
                $verification_code = rand(100000, 999999);
                $_SESSION['verification_code'] = $verification_code;
                $_SESSION['email'] = $email;
                $_SESSION['is_new_user'] = false;
                $_SESSION['user_id'] = $user['id']; 

                // Send Verification Code via email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['EMAIL_USERNAME'];;
                    $mail->Password = $_ENV['EMAIL_PASSWORD'];;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
        
                    $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Erosho');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Verification Code for Erosho Login';
                    $mail->Body = "Your Verification Code is <b>$verification_code</b>";
                    $mail->AltBody = "Your Verification Code is $verification_code";
        
                    $mail->send();
        
                    // Redirect to verification page

                    header("Location: verification.php");
                    exit();
                } catch (Exception $e) {
                    echo "Error: {$mail->ErrorInfo}";
                }
            } else{
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
                    header("Location: ../public/checkout.php");
                    exit();
                }
                else{
                    header('Location: ../index.php');
                    exit();
                }
            }

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
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Login">
                <a href="register.php" class="register-link">New user? Register</a>
            </div>
        </form>
        
    </div>

</body>
</html>