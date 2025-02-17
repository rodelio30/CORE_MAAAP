<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
$user_type = $_SESSION["type"];

// Fetch all rooms from the database
    $sql = "SELECT room_id, room_name, description FROM room ORDER BY room_id DESC";
    $result = $conn->query($sql);


// Insert the Room data once clicked the submit button
if (isset($_POST["submit_room"])) {
    // Retrieve form data

    // Capture form data
    $room_name = $conn->real_escape_string($_POST['room_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $other_description = $conn->real_escape_string($_POST['other_description']);
    
    // Insert room data
    $query = "INSERT INTO room (room_name, description, other_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $room_name, $description, $other_description);

    if ($stmt->execute()) {
        // Get the last inserted room_id
        $room_id = $stmt->insert_id;

        // Handle multiple file uploads
        $images = $_FILES['images'];
        $total_images = count($images['name']);

        // Directory for image uploads
        $target_dir = "../assets/img/uploads/rooms/";

        for ($i = 0; $i < $total_images; $i++) {
            if (!empty($images['name'][$i])) {
                $image_name = time() . "_" . basename($images['name'][$i]);
                $target_file = $target_dir . $image_name;

                // Move the uploaded file
                if (move_uploaded_file($images['tmp_name'][$i], $target_file)) {
                    // Insert image path into room_images table
                    $image_query = "INSERT INTO room_images (room_id, image_path) VALUES (?, ?)";
                    $image_stmt = $conn->prepare($image_query);
                    $image_stmt->bind_param("is", $room_id, $image_name);
                    $image_stmt->execute();
                    $image_stmt->close();
                } else {
                    echo "Sorry, there was an error uploading image " . $images['name'][$i];
                    echo "<script> alert('Sorry, there was an error uploading image ');</script>" . $images['name'][$i];
                }
            }
        }
      echo "<script> alert('New room and images added successfully!'); window.location.href = 'room-management.php'; </script>";
    } else {
        echo "<script> alert('Error: ' . $query . '<br> ' . $conn->error); window.location.href = 'room-management.php'; </script>";
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
      padding: 0.8rem 1rem 0.8rem 1rem;
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
              <div class="col-md-3">
                  <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="color: white;">
                      Add New Room
                  </button>
              </div>
              <div class="col-md-5 text-left">
                  <h1>
                    <b>
                      ROOM MANAGEMENT
                    </b>
                  </h1>
              </div>
              <div class="col-md-4">
                  <form method="GET" action="accounts.php">
                      <div class="search-input">
                          <!-- <i class="bi bi-search" style="font-size: 1.4rem;"></i> -->
                          <!-- <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search_keyword); ?>"> -->
                          <input class="form-control" type="text" id="searchInput" placeholder="Search" aria-label="Search" onkeyup="filterTable()" style="width: 99%;">
                      </div>
                  </form>
              </div>
          </div>


    <?php
if ($result->num_rows > 0) {
  echo '<table class="table table-bordered border-success mt-2 text-center" id="accountTable"> <!-- Add an ID for the table -->
          <thead>
              <tr>
                  <th scope="col">Room Name/Number</th>
                  <th scope="col">Building</th>
                  <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody>';

        // Output data for each row
        while($row = $result->fetch_assoc()) {
          echo '<tr>
            <th><h6 class="mt-2">' . $row["room_name"] . '</h6></th>
            <th><h6 class="mt-2">' . $row["description"] . '</h6></th>
            <th>
            <a href="room-management-view.php?room_id=' . $row["room_id"] . '" class="btn btn-success">Edit</a>
            <a href="include/delete_room.php?room_id=' . $row["room_id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this room?\')">Delete</a>
            </th>';
          }
      } else {
          echo "No Room found.";
      }
      ?>



      <div>

    </section><!-- /Explore Section -->

<!-- Modal for adding new User -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2e5a31; color: white;">
      <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Add Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="room-management.php" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="input-group mt-3">
            <span class="input-group-text">Room Name/Number</span>
            <input type="text" name="room_name" class="form-control" required>
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Building</span>
            <input type="text" name="description" class="form-control" required>
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Floor</span>
            <input type="text" name="other_description" class="form-control" required>
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Room Images</span>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            <small style="margin: 0 auto; color: #ff5757;">** Important Note: You Can Select Multiple Image **</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit_room" class="btn btn-success">Submit</button>
        </div> 
      </form>
    </div>
  </div>
</div>

  </main>

  <?php
    include 'include/footer-files.php';
  ?>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/navita.js"></script>
</body>

</html>


<script>
// JavaScript function to filter table rows based on input
function filterTable() {
    // Get the value of the search input
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("accountTable");
    var trs = table.getElementsByTagName("tr");

    // Loop through all rows (excluding the table header)
    for (var i = 1; i < trs.length; i++) {
        var roomCell = trs[i].getElementsByTagName("th")[0]; // Room Name column
        var scheduleCell = trs[i].getElementsByTagName("th")[1]; // Real-time Schedule column

        if (roomCell && scheduleCell) {
            var roomValue = roomCell.textContent || roomCell.innerText;
            var scheduleValue = scheduleCell.textContent || scheduleCell.innerText;
            // Check if the input matches either the room name or the schedule
            if (roomValue.toLowerCase().indexOf(filter) > -1 || scheduleValue.toLowerCase().indexOf(filter) > -1) {
                trs[i].style.display = ""; // Show row if match found
            } else {
                trs[i].style.display = "none"; // Hide row if no match
            }
        }
    }
}
</script>