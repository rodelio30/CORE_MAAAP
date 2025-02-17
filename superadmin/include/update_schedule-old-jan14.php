<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $schedule_id = $_POST['schedule_id']; // Hidden field from the modal form
    $name = $_POST['name'];
    $department = $_POST['department'];

    $position = $_POST['position'];

    echo "<script> alert('This is my position!".$position."' );</script>";

    if($position == 'teaching') {
        // Get the arrays of form input values
        $room_ids = $_POST['room_id']; // Added to capture hidden room_id
        $days = $_POST['day']; 
        $time_ins = $_POST['time_in'];
        $time_outs = ($_POST['position'] == 'teaching') ? $_POST['time_in'] : $_POST['time_out'];
        $subjects = isset($_POST['subject']) ? $_POST['subject'] : [];
        $real_time_schedule_ids = $_POST['real_time_schedule_id']; // Hidden field from modal

        // Loop through each schedule and update the details
        for ($i = 0; $i < count($room_ids); $i++) {
            $room_id = $room_ids[$i]; // Use room_id for the update
            $day = $days[$i]; 
            $time_in = $time_ins[$i];
            $time_out = $time_outs[$i];
            $subject = isset($subjects[$i]) ? $subjects[$i] : NULL;
            $rls_id = $real_time_schedule_ids[$i];

            // Build and execute the update query for the real_time_schedule table
            $query_update_sched = "
                UPDATE real_time_schedule 
                SET room_id = '$room_id', time_in = '$time_in', time_out = '$time_out', day = '$day', subject = '$subject' 
                WHERE real_time_schedule_id = '$rls_id'";

            // Execute the query
            if (!mysqli_query($conn, $query_update_sched)) {
                die("Error updating schedule: " . mysqli_error($conn));
            }
        }
    }

    // Update employee details
    $query_emp = "
        UPDATE employee_schedule 
        SET name = '$name', department = '$department'
        WHERE schedule_id = '$schedule_id'";

    // Execute the employee update query
    if (!mysqli_query($conn, $query_emp)) {
        die("Error updating employee: " . mysqli_error($conn));
    }

    // Redirect back or show success message
    echo "<script> alert('Employee Schedule Updated Successfully!'); window.location.href = '../employee_schedule.php'; </script>";
    exit();
  
}
?>