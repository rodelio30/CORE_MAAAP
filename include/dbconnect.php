<?php
session_start();
date_default_timezone_set("Asia/Manila");

if(!defined('Imember')){
    header('location: ../index.php');
    die();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "indoor_map";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>