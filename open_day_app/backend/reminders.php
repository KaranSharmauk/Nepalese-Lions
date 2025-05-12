<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// ------------------------
// POST – Add a reminder
// ------------------------
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = intval($data['user_id'] ?? 0);
    $event_id = intval($data['event_id'] ?? 0);

    if (!$user_id || !$event_id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing user_id or event_id']);
        exit;
    }

    // Check for duplicate
    $check = $conn->prepare("SELECT reminder_id FROM Reminders WHERE user_id = ? AND event_id = ?");
    $check->bind_param("ii", $user_id, $event_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult && $checkResult->num_rows > 0) {
        echo json_encode(['status' => 'fail', 'message' => 'Reminder already exists']);
        exit;
    }

    // Insert new reminder
    $stmt = $conn->prepare("INSERT INTO Reminders (user_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save reminder']);
    }

    $stmt->close();
}

// ------------------------
// GET – Get reminders for user
// ------------------------
elseif ($method === 'GET') {
    $user_id = intval($_GET['user_id'] ?? 0);

    if (!$user_id) {
        echo json_encode([]);
        exit;
    }

    $sql = "
        SELECT r.reminder_id, e.event_time, e.event_name, e.location
        FROM Reminders r
        JOIN Events e ON r.event_id = e.event_id
        WHERE r.user_id = ?
        ORDER BY e.event_time
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reminders = [];
    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row;
    }

    echo json_encode($reminders);
    $stmt->close();
}

// ------------------------
// DELETE – Remove a reminder
// ------------------------
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $reminder_id = intval($data['reminder_id'] ?? 0);

    if (!$reminder_id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing reminder_id']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM Reminders WHERE reminder_id = ?");
    $stmt->bind_param("i", $reminder_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete reminder']);
    }

    $stmt->close();
}

$conn->close();
?>
