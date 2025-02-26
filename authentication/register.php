<?php 
session_start();
include('../database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



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
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phpkuben@gmail.com';
            $mail->Password = 'srnq cqiy dqzu kyfl';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('from@example.com', 'EroZone');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Verification Code for EroZone Registration';
            $mail->Body = "Your Verification Code is <b>$otp</b>";
            $mail->AltBody = "Your Verification Code is $otp";

            $mail->send();

            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
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