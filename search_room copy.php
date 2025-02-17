<?php
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];

     // Prepare search term for SQL
     $searchWildcard = "%{$searchTerm}%";

     // Fetch matching rooms from the database
     $query = "SELECT room_id, room_name, description FROM room WHERE room_name LIKE ? OR description LIKE ? OR other_description LIKE ?";
     $stmt = $conn->prepare($query);
     $stmt->bind_param('sss', $searchWildcard, $searchWildcard, $searchWildcard); 
    $stmt->execute();
    $result = $stmt->get_result();

  // Display results as clickable list items
    if ($result->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item">';
            echo '<a href="#" class="room-link" data-room-id="' . $row['room_id'] . '" style="text-decoration: none; color: black; display: flex; align-items: center;">';
            // The room_id is included as data-room-id, needed for fetching images
            echo '<div>';
            echo '<span>' . htmlspecialchars($row['room_name']) . '</span><br>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
            echo '<button class="btn btn-outline-success" style="margin-left: auto;">VIEW</button>';
            echo '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No rooms found</p>';
    } 
}
?>