<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

if (isset($_GET['emp_id'])) {
  $employee_id = $_GET['emp_id'];

    // First, delete the corresponding real-time schedule entries
    $sql_real_time = "DELETE FROM real_time_schedule WHERE employee_id = ?";
    $stmt_real_time = $conn->prepare($sql_real_time);
    $stmt_real_time->bind_param("i", $employee_id);
    $stmt_real_time->execute();

    // Now, delete the employee from employee_schedule table
    $sql_employee = "DELETE FROM employee_schedule WHERE employee_id = ?";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("i", $employee_id);
    $stmt_employee->execute();

    // Redirect back to the main page with a success message
    echo "<script> alert('Employee successfully deleted!'); window.location.href = '../employee_schedule.php'; </script>";
}
?>