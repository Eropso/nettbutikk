<?php
session_start();

include("database.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}


$id = $_SESSION['user']['id'];
$sql = "SELECT id, email, first_name, last_name FROM users WHERE id = :id";
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
        $stmt->bindParam(':id', $user['id']);

        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['user'] = [
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ];

            echo "Profile updated successfully!";
            header("Location: settings.php");
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
    <form action="" method="POST">
        <div class="user-info">
            <div class="user-container">
                <label for="first-name">First Name:</label>
                <input type="text" name="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>" id="first-name">

                <label for="last-name">Last Name:</label>
                <input type="text" name="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>" id="last-name">

                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" id="email">

            </div>
            <div>
                <button type="submit" name="update">Update</button>
                <button type="submit" name="delete">Delete</button>
            </div>
        </div>
    </form>
</body>
</html>