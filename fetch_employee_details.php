<?php
header('Content-Type: application/json');
define('Imember', true); 

require('include/dbconnect.php'); // Ensure this file exists and connects properly

$response = ["success" => false]; // Default response

// Check if employee_id is received
if (!isset($_POST['employee_id'])) {
    $response["error"] = "Employee ID not received.";
    echo json_encode($response);
    exit;
}

$employee_id = $_POST['employee_id'];

// Check if the employee_id is numeric
if (!is_numeric($employee_id)) {
    $response["error"] = "Invalid Employee ID.";
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare("SELECT employee_id, name, department, position FROM employee_schedule WHERE employee_id = ?");

if (!$stmt) {
    $response["error"] = "SQL Error: " . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();


if ($row = $result->fetch_assoc()) {
    $response = [
        "success" => true,
        "employee_id" => $row['employee_id'],
        "name" => $row['name'],
        "department" => $row['department'],
        "position" => $row['position'],
        "schedule" => [] // Placeholder for schedule data
    ];

    // Fetch employee schedule
    // $sql_schedule = "
    //     SELECT real_time_schedule.day, real_time_schedule.time_in, real_time_schedule.time_out, real_time_schedule.subject
    //     FROM real_time_schedule
    //     WHERE real_time_schedule.employee_id = ?
    //     ORDER BY FIELD(real_time_schedule.day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'), real_time_schedule.time_in ASC;
    // ";

    // Fetch employee schedule with room name
$sql_schedule = "
SELECT 
    rts.day, 
    rts.time_in, 
    rts.time_out, 
    rts.subject, 
    room.room_name
FROM real_time_schedule rts
JOIN room ON rts.room_id = room.room_id
WHERE rts.employee_id = ?
ORDER BY FIELD(rts.day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'), rts.time_in ASC;
";


    $stmt_schedule = $conn->prepare($sql_schedule);
    $stmt_schedule->bind_param('i', $employee_id);
    $stmt_schedule->execute();
    $schedule_result = $stmt_schedule->get_result();
    
    // Organize schedule by day
$scheduleData = [];
while ($scheduleRow = $schedule_result->fetch_assoc()) {
    $day = $scheduleRow['day'];
    $scheduleData[$day][] = [
        "time_in" => $scheduleRow['time_in'],
        "time_out" => $scheduleRow['time_out'],
        "subject" => $scheduleRow['subject'],
        "room_name" => $scheduleRow['room_name']
    ];
}

$response["schedule"] = $scheduleData;

} else {
    $response["error"] = "No employee found.";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>