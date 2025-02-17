<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];  // Can be either about_id or core_id
    $field = $data['field'];
    $value = $data['value'];

    // Validate field name to prevent SQL injection
    $validFields = ['about_description', 'core_description'];
    if (!in_array($field, $validFields)) {
        echo json_encode(['success' => false, 'message' => 'Invalid field']);
        exit;
    }

    // Determine the table based on the field name
    if ($field === 'about_description') {
        $query = "UPDATE about_sections SET about_description = ? WHERE about_id = ?";
    } elseif ($field === 'core_description') {
        $query = "UPDATE core_values SET core_description = ? WHERE core_id = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid update operation']);
        exit;
    }

    // Execute the update
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $value, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
    $conn->close();
}
?>