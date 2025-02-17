<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];

     // Prepare search term for SQL
     $searchWildcard = "%{$searchTerm}%";

// Fetch matching rooms from the database
$query = "
SELECT 
    r.room_id, 
    r.room_name, 
    r.description, 
    e.employee_id, 
    e.name AS employee_name,
    rs.time_in,
    rs.time_out,
    rs.day,
    rs.subject
FROM 
    room r
LEFT JOIN real_time_schedule rs ON r.room_id = rs.room_id
LEFT JOIN employee_schedule e ON rs.employee_id = e.employee_id
WHERE 
    r.room_name LIKE ? OR 
    r.description LIKE ? OR 
    r.other_description LIKE ? OR
    e.name LIKE ?";
// Check if search term is not empty and contains valid characters
if (!empty($_POST['search']) && preg_match('/[a-zA-Z]/', $_POST['search'])) {
    $searchTerm = trim($_POST['search']); // Remove leading/trailing white spaces
    $searchTerm = preg_replace('/\s+/', ' ', $searchTerm); // Normalize multiple spaces into one

    $query .= " GROUP BY e.name";  
}

// Bind parameters to the prepared statement
$stmt->bind_param('ssss', $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);
    $stmt->execute();
    $result = $stmt->get_result();

  // Display results as clickable list items
    if ($result->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
    
            // Check if the search result is a Room
            if (!empty($row['room_name']) && stripos($row['room_name'], $searchTerm) !== false) {
                echo '<li class="list-group-item">';
                echo '<a href="#" class="room-link" data-room-id="' . $row['room_id'] . '" style="text-decoration: none; color: black; display: flex; align-items: center;">';
                echo '<div>';
                echo '<span><i class="bi bi-door-open"></i> Room: ' . htmlspecialchars($row['room_name']) . '</span><br>';
                echo '<p><i class="bi bi-building"></i> Building: ' . htmlspecialchars($row['description']) . '</p>';
                echo '</div>';
    
                // VIEW button for rooms
                echo '<button class="btn btn-outline-success view-details" data-room-id="' . $row['room_id'] . '" style="margin-left: auto;">VIEW</button>';
            } 
            // Check if the search result is an Employee
            elseif (!empty($row['employee_name']) && stripos($row['employee_name'], $searchTerm) !== false) {
                echo '<li class="list-group-item">';
                echo '<a href="#" class="room-link" data-room-id="' . $row['room_id'] . '" style="text-decoration: none; color: black; display: flex; align-items: center;">';
                echo '<div>';
                echo '<span><i class="bi bi-person-circle"></i> <strong>Teacher Name </strong>: ' . htmlspecialchars($row['employee_name']) . '</span><br>';
                echo '</div>';
    
                // VIEW button for employees
                echo '<button class="btn btn-outline-success view-details" data-employee-id="' . $row['employee_id'] . '" style="margin-left: auto;">VIEW</button>';
            }
    
            echo '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="text-center">No Rooms or Teacher name found</p>';
    } 
}
?>