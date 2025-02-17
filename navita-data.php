<?php
// Database connection

define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

// Query to get the latest 5 answered questions
$sql = "SELECT question, answer 
        FROM navita 
        WHERE status = 'answered' 
        -- ORDER BY date_answered DESC 
                ORDER BY RAND() 
        LIMIT 5";
$result = $conn->query($sql);

// Prepare data for output
$suggestions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
}
$conn->close();

// Output as JSON for frontend use
header('Content-Type: application/json');
echo json_encode($suggestions);
?>
