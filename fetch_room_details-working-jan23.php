<?php
header('Content-Type: application/json');

define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

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
            $details = [
                "<h5>Room Name/Number" => htmlspecialchars($row['room_name']),
                "</h5>Building" => htmlspecialchars($row['description']),
                "Floor" => htmlspecialchars($row['other_description']),
            ];

            // Fetch room images
            $imageQuery = "SELECT image_path FROM room_images WHERE room_id = ?";
            $imageStmt = $conn->prepare($imageQuery);
            $imageStmt->bind_param("i", $roomId);
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();

            $images = [];
            while ($imageRow = $imageResult->fetch_assoc()) {
                $images[] = "assets/img/uploads/rooms/" . htmlspecialchars($imageRow['image_path']);
            }

              
            // Fetch room schedules and assigned employees
            $scheduleQuery = "
                SELECT es.name, es.department, es.position, rts.time_in, rts.time_out, rts.day, rts.subject 
                FROM real_time_schedule AS rts
                JOIN employee_schedule AS es ON rts.employee_id = es.schedule_id
                WHERE rts.room_id = ?
            ";
            $scheduleStmt = $conn->prepare($scheduleQuery);
            $scheduleStmt->bind_param("i", $roomId);
            $scheduleStmt->execute();
            $scheduleResult = $scheduleStmt->get_result();

            $schedules = [];
            while ($scheduleRow = $scheduleResult->fetch_assoc()) {
                $schedules[] = [
                    "employee_name" => htmlspecialchars($scheduleRow['name']),
                    "department" => htmlspecialchars($scheduleRow['department']),
                    "position" => htmlspecialchars($scheduleRow['position']),
                    "day" => htmlspecialchars($scheduleRow['day']),
                    "time_in" => htmlspecialchars($scheduleRow['time_in']),
                    "time_out" => htmlspecialchars($scheduleRow['time_out']),
                    "subject" => htmlspecialchars($scheduleRow['subject']),
                ];
            }

            echo json_encode([
                "success" => true,
                "details" => $details,
                "images" => $images,
                "schedules" => $schedules
            ]);
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