<?php 
session_start();
include('../database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';



$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);


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
                $mail->Username = 'phpkuben@gmail.com';
                $mail->Password = 'srnq cqiy dqzu kyfl';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
    
                $mail->setFrom('phpkuben@gmail.com', 'Eroshop');
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