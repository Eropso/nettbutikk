<?php
session_start();

include("database.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}


$userid = $_SESSION['user']['id'];
$sql = "SELECT id, email, first_name, last_name FROM users WHERE id = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <div class="all-users">
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
                </div>
            </div>
        </div>
    </form>
</body>
</html>