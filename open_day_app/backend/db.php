<?php
$host = 'localhost';
$port = 3306; // Your custom MySQL port
$dbname = 'open_day_app';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}
?>
