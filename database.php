
<?php
require 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$servername = $_ENV['DB_SERVERNAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_DBNAME'];
$certPath = __DIR__ . '/ssl/BaltimoreCyberTrustRoot.crt.pem';


$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::MYSQL_ATTR_SSL_CA => $certPath,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO($dsn, $username, $password, $options);
    echo "Connected successfully with SSL!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
