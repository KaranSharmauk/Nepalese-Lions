<?php
// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Include the DB connection
require_once 'db.php';

// Get raw POST data and decode JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing email or password']);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

// Prepare and execute SQL
$stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Compare plain text password (as per your current setup)
    if ($password === $user['password']) {
        echo json_encode([
            'status' => 'success',
            'user_id' => $user['user_id']
        ]);
    } else {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Incorrect password'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'User not found'
    ]);
}

$stmt->close();
$conn->close();
?>
