<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

// Delete Image
// Check if room_id is set in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Fetch all image paths associated with the room from the database
    $sql = "SELECT image_path FROM room_images WHERE room_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Loop through all images and delete them from the server
        while ($row = $result->fetch_assoc()) {
            $image_path = "../../assets/img/uploads/rooms/" . $row['image_path']; // Full path to the image file

            if (file_exists($image_path)) {
                if (!unlink($image_path)) {
                    echo "<script>alert('Error deleting image file: $image_path');</script>";
                }
            }
        }

        // Delete all records of images associated with the room from the database
        $delete_images_sql = "DELETE FROM room_images WHERE room_id = ?";
        $delete_images_stmt = $conn->prepare($delete_images_sql);

        if ($delete_images_stmt) {
            $delete_images_stmt->bind_param("i", $room_id);

            if ($delete_images_stmt->execute()) {
                // After deleting images, delete the room itself
                $delete_room_sql = "DELETE FROM room WHERE room_id = ?";
                $delete_room_stmt = $conn->prepare($delete_room_sql);

                if ($delete_room_stmt) {
                    $delete_room_stmt->bind_param("i", $room_id);

                    if ($delete_room_stmt->execute()) {
                        echo "<script>alert('Room and associated images deleted successfully!'); window.location.href = '../room-management.php';</script>";
                    } else {
                        echo "<script>alert('Error deleting room from the database.'); window.location.href = '../room-management.php';</script>";
                    }
                } else {
                    echo "<script>alert('Error preparing room delete statement.'); window.location.href = '../room-management.php';</script>";
                }
            } else {
                echo "<script>alert('Error deleting images from the database.'); window.location.href = '../room-management.php';</script>";
            }
        } else {
            echo "<script>alert('Error preparing image delete statement.'); window.location.href = '../room-management.php';</script>";
        }
    } else {
        echo "<script>alert('Error preparing statement to fetch images.'); window.location.href = '../room-management.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. Room ID is missing.'); window.location.href = '../room-management.php';</script>";
}
?>