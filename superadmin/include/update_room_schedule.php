<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   // Get the posted data
   $employee_id = $_POST['employee_id'];
   $rts_id = $_POST['rts_id'];
   $time_in = $_POST['time_in'];
   $time_out = $_POST['time_out'];
   $employee_name = $_POST['employee_name'];
   $subject = $_POST['subject'];

   // Update query
   $sql = "UPDATE real_time_schedule
           SET time_in = ?, time_out = ?, subject = ?
           WHERE real_time_schedule_id = ?";
   
  //  Prepare and bind
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("sssi", $time_in, $time_out, $subject, $rts_id);

       // Error message
   if (!$stmt->execute()) {
      echo "<script> alert('Error: ') . $stmt->error; window.location.href = '../room-schedule.php'; </script>";
   }

  // Update employee details
  mysqli_query($conn, "update employee_schedule set name = '$employee_name' where employee_id = '$employee_id'") or die("Query Updating Room data is incorrect....");
   // Redirect back to the schedule page or handle as needed
    echo "<script> alert('Room Schedule Updated Successfully!'); window.location.href = '../room-schedule.php'; </script>";
}
?>