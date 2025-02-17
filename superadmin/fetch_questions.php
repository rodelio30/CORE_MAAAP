<?php
// Database connection

define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if (!empty($q)) {
    $stmt = $conn->prepare("SELECT question, answer FROM navita WHERE question LIKE CONCAT('%', ?, '%') AND status = 'answered'");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'question' => $row['question'],
            'answer' => $row['answer']
        ];
    }

    echo json_encode($questions);
} else {
    echo json_encode([]);
}
?>