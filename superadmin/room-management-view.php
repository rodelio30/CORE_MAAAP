<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}


// Get the room_id from the URL
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

// Update room details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room_id = $_POST['room_id'];
  $room_name = $_POST['room_name'];
  $description = $_POST['description'];
  $other_description = $_POST['other_description'];

  $sql = "UPDATE room SET room_name = ?, description = ?, other_description = ? WHERE room_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssi", $room_name, $description, $other_description, $room_id);
  
  if ($stmt->execute()) {
      // Handle image upload if files are selected
      if (isset($_FILES['room_images']) && count($_FILES['room_images']['name']) > 0) {
          foreach ($_FILES['room_images']['name'] as $key => $image_name) {
              $tmp_name = $_FILES['room_images']['tmp_name'][$key];
              $target_dir = "../assets/img/uploads/rooms/";
              $target_file = $target_dir . basename($image_name);

              if (move_uploaded_file($tmp_name, $target_file)) {
                  // Insert the image path into the room_images table
                  $insert_image_sql = "INSERT INTO room_images (room_id, image_path) VALUES (?, ?)";
                  $image_stmt = $conn->prepare($insert_image_sql);
                  $image_stmt->bind_param("is", $room_id, $image_name);
                  $image_stmt->execute();
                  $image_stmt->close();
              }
          }
      }
      echo "<script>alert('Room Details Updated Successfully'); window.location.href = 'room-management-view.php?room_id=" . $room_id . "';</script>";
  } else {
      echo "Error updating room details.";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Indoor Map Modern</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../assets/img/core/coreguide-logo.png" rel="icon">
  <link href="../assets/img/core/coreguide-logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
  <link href="../assets/css/custom.css" rel="stylesheet">
  <link href="../assets/css/lightgallery.css" rel="stylesheet" />

  <style>
    .search-input {
      position: relative;
    }

    .form-control {
      border: 5px solid #2e5a31 !important;
      border-radius: 0.25rem;
    }

    .search-input input {
      /* padding-left: 2.5rem;  */
      padding: 0.8rem 1rem 0.8rem 2.5rem;
      /* background-color: #2e5a31; */
    }
    input::placeholder {
      color: black !important;
      opacity: 1; /* Ensures the placeholder is fully visible */
    }

    .search-input .fa-search {
      position: absolute;
      left: 10px;  /* Position the icon on the left */
      top: 50%;
      transform: translateY(-50%);
      color: black;
    }

    .input-group-text {
      width: 11rem;
    }
    th {
      border: 5px solid #2e5a31 !important;
    }
  </style>

</head>

<body class="index-page">
  <?php
    $nav_active = "manage";
    include 'include/navbar.php';
  ?>

  <main class="main">
    <div class="mt-2">
      <h1 class="text-center">
      <img src="../assets/img/core/coreguide-logo.png" alt="Core Guide Logo" class="mb-2" width="60">
          <b>
            <span class="pt-3">
              CORE GUIDE
            </span>
          </b>
      </h1>
    </div>
    <!-- Explore Section -->
    <section class="services section light-background">
      <!-- Section Title -->
      <div class="container">
      <!-- Button trigger modal -->
    <div class="row pt-3">
        <div class="col-md-4">
              <a href="room-management.php" class="btn btn-outline-success">< Back</a>
        </div>
        <div class="col-md-4 text-left">
            <h1 class="text-center"><b>ROOM DETAILS</b></h1>
        </div>
        <div class="col-md-4">
        </div>
    </div>
    <?php
if ($room_id > 0) {
  // Fetch room details
  $room_sql = "SELECT room_name, description, other_description FROM room WHERE room_id = ?";
  $stmt = $conn->prepare($room_sql);
  $stmt->bind_param("i", $room_id);
  $stmt->execute();
  $room_result = $stmt->get_result();
  
  if ($room_result->num_rows > 0) {
      $room = $room_result->fetch_assoc();
      
      // Start the form to edit room details
      echo '<form method="POST" action="room-management-view.php" enctype="multipart/form-data">';
      echo '<input type="hidden" name="room_id" value="' . $room_id . '">'; // Pass room_id to the form
      
      // Room Name/Number input
      echo '<div class="mb-3">';
      echo '<label for="room_name" class="form-label"><strong>Room Name/Number:</strong></label>';
      echo '<input type="text" class="form-control" id="room_name" name="room_name" value="' . $room['room_name'] . '" required>';
      echo '</div>';
      
      // Description input
      echo '<div class="mb-3">';
      echo '<label for="description" class="form-label"><strong>Building:</strong></label>';
      echo '<input type="text" class="form-control" id="description" name="description" value="' . $room['description'] . '" required>';
      echo '</div>';

      // Other Description input
      echo '<div class="mb-3">';
      echo '<label for="other_description" class="form-label"><strong>Floor:</strong></label>';
      echo '<input type="text" class="form-control" id="other_description" name="other_description" value="' . $room['other_description'] . '" required>';
      echo '</div>';

      // Fetch room images
      $image_sql = "SELECT image_id, image_path FROM room_images WHERE room_id = ?";
      $image_stmt = $conn->prepare($image_sql);
      $image_stmt->bind_param("i", $room_id);
      $image_stmt->execute();
      $image_result = $image_stmt->get_result();

      if ($image_result->num_rows > 0) {
          echo '<div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">';
          echo '<div class="carousel-indicators">';

          $image_paths = [];
          $index = 0;
          while ($image = $image_result->fetch_assoc()) {
              $active_class = $index === 0 ? 'active' : '';
              echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $index . '" class="' . $active_class . '" aria-label="Slide ' . ($index + 1) . '"></button>';
              $image_paths[] = ['path' => $image["image_path"], 'id' => $image['image_id']];
              $index++;
          }
          echo '</div>'; // Close carousel-indicators

          echo '<div class="carousel-inner">';

          foreach ($image_paths as $key => $image) {
              $active_class = $key === 0 ? 'active' : '';
              echo '<div class="carousel-item ' . $active_class . '">';
              echo '<img src="../assets/img/uploads/rooms/' . $image['path'] . '" class="d-block w-100" alt="Room Image" height="600">';
              
              // Provide an option to delete the image
              echo '<div class="carousel-caption d-none d-md-block">';
              echo '<a href="include/delete_image.php?image_id=' . $image['id'] . '&room_id=' . $room_id . '" class="btn btn-danger">Delete Image</a>';
              echo '</div>';
              echo '</div>';
          }

          echo '</div>'; // Close carousel-inner
          echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">';
          echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
          echo '<span class="visually-hidden">Previous</span>';
          echo '</button>';
          echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">';
          echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
          echo '<span class="visually-hidden">Next</span>';
          echo '</button>';

          echo '</div>'; // Close carousel
      } else {
          echo "No images available for this room.";
      }

      // Upload new image input
      echo '<div class="mb-3">';
      echo '<label for="room_images" class="form-label"><strong>Upload new images:</strong></label>';
      echo '<input type="file" class="form-control" id="room_images" name="room_images[]" multiple required>';
      echo '</div>';

      // Submit button
      echo '<div class="text-center">';
      echo '<button type="submit" class="btn btn-success mb-2">Save Changes</button>';
      echo '</div>';
      echo '</form>'; // End the form
  } else {
      echo "Room not found.";
  }

  $stmt->close();
  $image_stmt->close();
} else {
  echo "Invalid room ID.";
}
?>
      <div>

    </section><!-- /Explore Section -->


  </main>

  <?php
    include 'include/footer-files.php';
  ?>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/navita.js"></script>
</body>

</html>
