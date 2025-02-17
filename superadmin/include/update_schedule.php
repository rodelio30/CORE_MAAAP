<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $employee_id = $_POST['employee_id']; // Hidden field from the modal form
    $name = $_POST['name'];
    $department = $_POST['department'];

    $position = $_POST['position'];


    if($position == 'teaching') {
        // Get form data
        $real_time_schedule_ids = $_POST['real_time_schedule_id'];
        $room_ids = $_POST['room_id'];
        $days = $_POST['day'];
        $time_ins = $_POST['time_in'];
        $time_outs = $_POST['time_out'] ?? [];
        $subjects = $_POST['subject'] ?? []; // Optional field

        // Ensure all arrays have the same length
        $row_count = count($real_time_schedule_ids);
        if (
            count($room_ids) !== $row_count ||
            count($days) !== $row_count ||
            count($time_ins) !== $row_count ||
            count($time_outs) !== $row_count
        ) {
            die("Error: Mismatched array lengths.");
        }

        // Prepare the update query using placeholders
        $stmt = $conn->prepare("
            UPDATE real_time_schedule
            SET room_id = ?, time_in = ?, time_out = ?, day = ?, subject = ?
            WHERE real_time_schedule_id = ?
        ");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        // Loop through schedules and update
        for ($i = 0; $i < $row_count; $i++) {
            $real_time_schedule_id = $real_time_schedule_ids[$i];
            $room_id = $room_ids[$i];
            $day = $days[$i];
            $time_in = $time_ins[$i];
            $time_out = $time_outs[$i] ?? NULL; // Handle missing time_out
            $subject = $subjects[$i] ?? NULL;   // Handle missing subject

            // Bind parameters: room_id (int), time_in/out/day/subject (string), real_time_schedule_id (int)
            $stmt->bind_param("issssi", $room_id, $time_in, $time_out, $day, $subject, $real_time_schedule_id);

            // Execute the statement
            if (!$stmt->execute()) {
                die("Error updating schedule for ID $real_time_schedule_id: " . $stmt->error);
            }
        }

        // Close the statement
        $stmt->close(); 
    }

    // Update employee details
    $query_emp = "
        UPDATE employee_schedule 
        SET name = '$name', department = '$department'
        WHERE employee_id = '$employee_id'";

    // Execute the employee update query
    if (!mysqli_query($conn, $query_emp)) {
        die("Error updating employee: " . mysqli_error($conn));
    }

    // Redirect back or show success message
    echo "<script> alert('Employee Schedule Updated Successfully!'); window.location.href = '../employee_schedule.php'; </script>";
    exit();
  
}
?>