<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script> alert('User successfully deleted!'); window.location.href = '../accounts.php'; </script>";
    } else {
      echo "<script> alert('Error deleting user: ' . $conn->error); </script>";
    }

    $conn->close();
}
?>