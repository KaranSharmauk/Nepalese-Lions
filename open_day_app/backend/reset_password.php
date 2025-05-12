<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$newPassword = trim($data['new_password'] ?? '');

if (!$email || !$newPassword) {
    echo json_encode(['status' => 'error', 'message' => 'Email and new password required']);
    exit;
}

$check = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'fail', 'message' => 'Email not found']);
    exit;
}

$stmt = $conn->prepare("UPDATE Users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $newPassword, $email);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to reset password']);
}

$stmt->close();
$conn->close();
?>
