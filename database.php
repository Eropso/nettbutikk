<?php
    $db_server = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'nettbutikk';

    try {
        $dsn = "mysql:host=" . $db_server . ";dbname=" .  $db_name;
        $pdo = new PDO($dsn, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
 
?>