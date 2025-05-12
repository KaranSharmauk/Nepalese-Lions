<?php
// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// DB connection
require_once 'db.php';

// Read and decode the JSON body
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit;
}

// Check if user already exists
$check = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(['status' => 'fail', 'message' => 'An account with this email already exists.']);
    exit;
}

// Insert new user (plain text password for now)
$stmt = $conn->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create account.']);
}

$stmt->close();
$conn->close();
?>
