<?php 
session_start();
include('../database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($first_name) || empty($last_name) || empty($email) || empty($password)){
        echo 'Please fill in all fields';
        return;
    }
    else {
        $sql = "SELECT id, email, first_name, last_name, password, role FROM users WHERE email = :email;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            echo 'User already exists';
            return;
        }
        else {
            $_SESSION['new_user'] = [   
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),   
            ];

            $_SESSION['is_new_user'] = true; // Set session for new user registration

            // Generate verification code and send it
            $verification_code = rand(100000, 999999);
            $_SESSION['verification_code'] = $verification_code;
    
    
            // Send Verification Code via email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['EMAIL_USERNAME'];
                $mail->Password = $_ENV['EMAIL_PASSWORD'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
    
                $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Eroshop');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Verification Code for Erosho Registration';
                $mail->Body = "Your Verification Code is <b>$verification_code</b>";
                $mail->AltBody = "Your Verification Code is $verification_code";
    
                $mail->send();
    
                // Redirect to verification page
                header("Location: verification.php");
                exit();
            } catch (Exception $e) {
                echo "Error: {$mail->ErrorInfo}";
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
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="register_form">
        <form action="" method="POST">
            <div class="register_inputs">
                <label for="name">First name:</label>
                <input type="text" name="first_name" id="first_name" required>
                <label for="name">Last name:</label>
                <input type="text" name="last_name" id="last_name" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Register">
            </div>
        </form>
    </div>

</body>
</html>