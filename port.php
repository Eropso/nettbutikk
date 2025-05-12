<?php
$host = 'smtp.gmail.com';
$ports = [25, 465, 587];

foreach ($ports as $port) {
    echo "Testing $host:$port ... ";
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if (is_resource($connection)) {
        echo "SUCCESS<br>";
        fclose($connection);
    } else {
        echo "FAILED - $errstr ($errno)<br>";
    }
}
