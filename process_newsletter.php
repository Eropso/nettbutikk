<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}


$email = $_POST['email'];

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

    $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Eroshop');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to our newsletter';
    $mail->Body = "Your coupon code is ...";
    $mail->AltBody = "Your coupon code is ...";

    $mail->send();

    // Redirect to verification page
    header("Location: index.php");
    exit();
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}



?>