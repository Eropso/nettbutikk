<?php
require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load .env if available (for local development)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Database credentials
$servername = $_ENV['DB_SERVERNAME'];
$username   = $_ENV['DB_USERNAME'];
$password   = $_ENV['DB_PASSWORD'];
$dbname     = $_ENV['DB_DBNAME'];

// Check if running in Azure (production)
$isProduction = getenv('WEBSITE_INSTANCE_ID') !== false;

// DSN string
$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

// Default PDO options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Enable SSL if in production
if ($isProduction) {
    $certPath = __DIR__ . '/ssl/BaltimoreCyberTrustRoot.crt.pem';

    if (!file_exists($certPath)) {
        die("SSL certificate not found at: $certPath");
    }

    $options[PDO::MYSQL_ATTR_SSL_CA] = $certPath;
    echo "SSL enabled (production)<br>";
} else {
    echo "Running in local environment - SSL not used<br>";
}

// Attempt connection
try {
    $conn = new PDO($dsn, $username, $password, $options);
    echo "Connected to database successfully<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
