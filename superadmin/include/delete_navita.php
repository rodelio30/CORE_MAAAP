<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

// Delete User
if (isset($_GET['navita_id'])) {
  $navita_id = $_GET['navita_id'];

  // Delete the Navita from the database
  $sql = "DELETE FROM navita WHERE navita_id = '$navita_id'";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('Navita successfully deleted!'); window.location.href = '../navita.php'; </script>";
  } else {
      echo "<script> alert('Error deleting navita: ' . $conn->error); </script>";
  }
}
?>