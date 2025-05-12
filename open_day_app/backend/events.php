<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include DB connection
require_once 'db.php';

// SQL query to fetch all events
$sql = "SELECT event_id, event_time, event_name, location, location_url FROM Events ORDER BY event_time ASC";
$result = $conn->query($sql);

$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode($events);
} else {
    // Return an empty array if no events found
    echo json_encode([]);
}

$conn->close();
?>
