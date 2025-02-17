<?php 
sleep(1);
session_start(); // Initialize the session

$_SESSION = [];
session_unset();
session_destroy();

header("Location: ../explore.php");
?>