<?php
header('Content-Type: application/json');

define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

try {
    if (isset($_POST['room_id'])) {
        $roomId = (int)$_POST['room_id'];

        // Fetch room details
        $query = "SELECT room_name, description, other_description FROM room WHERE room_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $details = "<h1>Room Name: " . htmlspecialchars($row['room_name']) . "</h1>" .
                       "<p>Description: " . htmlspecialchars($row['description']) . "</p>" .
                       "Other Description: " . htmlspecialchars($row['other_description']);

            // Fetch room images
            $imageQuery = "SELECT image_path FROM room_images WHERE room_id = ?";
            $imageStmt = $conn->prepare($imageQuery);
            $imageStmt->bind_param("i", $roomId);
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();

            $images = [];
            while ($imageRow = $imageResult->fetch_assoc()) {
                $images[] = "../assets/img/uploads/rooms/" . htmlspecialchars($imageRow['image_path']);
            }

            echo json_encode(["success" => true, "details" => $details, "images" => $images]);
        } else {
            echo json_encode(["success" => false, "message" => "Room not found."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "An error occurred.", "error" => $e->getMessage()]);
}
?>