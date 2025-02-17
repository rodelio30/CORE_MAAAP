<?php
define('Imember', true); 
require('../../include/dbconnect.php'); // Connect to the database

// Delete Image

// Check if image_id and room_id are set in the URL
if (isset($_GET['image_id']) && isset($_GET['room_id'])) {
    $image_id = $_GET['image_id'];
    $room_id = $_GET['room_id'];

    // Fetch the image path from the database
    $sql = "SELECT image_path FROM room_images WHERE image_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $image = $result->fetch_assoc();
            $image_path = "../../assets/img/uploads/rooms/" . $image['image_path']; // Full path to the image file

            // Delete the image file from the server
            if (file_exists($image_path)) {
                if (unlink($image_path)) {
                    // File deleted successfully, now delete the record from the database
                    $delete_sql = "DELETE FROM room_images WHERE image_id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);

                    if ($delete_stmt) {
                        $delete_stmt->bind_param("i", $image_id);

                        if ($delete_stmt->execute()) {
                            // Redirect back to the room details page
                            echo "<script>alert('Image Deleted Successfully'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Error deleting image from the database.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
                        }
                    } else {
                        echo "<script>alert('Error preparing delete statement.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
                    }
                } else {
                    echo "<script>alert('Error deleting the image file.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
                }
            } else {
                echo "<script>alert('Image file does not exist.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
            }
        } else {
            echo "<script>alert('Image not found.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement.'); window.location.href = '../room-management-view.php?room_id=" . $room_id . "';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '../room-management-view.php';</script>";
}
?>