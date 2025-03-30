<?php
// Set the Content-Type header to application/json to indicate we're sending a JSON response
header('Content-Type: application/json');

// Define the data you want to return as an API response
$data = array(
    'status' => 'success',
    'message' => 'The API is connected!',
    'event' => array(
        'name' => 'Open Day Companion',
        'time' => '10:00 AM',
        'location' => 'University of Wolverhampton'
    )
);

// Convert the data to JSON format and output it
echo json_encode($data);
?>
