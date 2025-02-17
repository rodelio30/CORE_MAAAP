<?php
// Database connection

define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

// Get the JSON input from the fetch request
$data = json_decode(file_get_contents('php://input'), true);
$question = $conn->real_escape_string($data['question']);
$answer = NULL;
$status = 'pending';

$sql_insert = "INSERT INTO navita (question, answer, status, date_answered) VALUES ('$question', '$answer', '$status', NOW())";

if ($conn->query($sql_insert) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();

?>