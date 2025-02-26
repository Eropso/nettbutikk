<?php 
session_start();
include('../database.php');


$otp = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_NUMBER_INT);



if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($password)){
        echo 'Please fill in Verification code';
        return;
    }
    else {
        $sql = "SELECT id, email, first_name, last_name, password, role FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
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