<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

  // Check if form is submitted and file is uploaded
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profilepic'])) {
    // Get the uploaded file details
    $profilePic = $_FILES['profilepic'];
    $userId = $_SESSION['user_id'];
  
    // Handle file upload
    if (isset($_FILES['profilepic'])) {
      if ($_FILES['profilepic']['error'] == 0) {
          $profilepic = basename($_FILES['profilepic']['name']);
          $temp_profilepic = 'profiles/' . $profilepic;
      } else {
          echo "Error uploading file: " . $_FILES['profilepic']['error'];
      }
  } else {
      echo "No file uploaded.";
  }
  
    // SQL query to insert data
    $sql = "UPDATE users SET profile_pic = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $profilepic, $userId);
  
    if ($stmt->execute()) {
        if (move_uploaded_file($_FILES['profilepic']['tmp_name'], $temp_profilepic)) {
        // echo "File uploaded successfully.";
        echo "<script type='text/javascript'>alert('Profile picture updated successfully.');  document.location='index.php' </script>";
        } else {
        echo "Failed to move uploaded file.";
        }
    } else {
        echo "Error updating profile picture in the database.";
    }
  
    $stmt->close();
  
  // echo "Error: " . $sql . "<br>" . $conn->error;
  if ($conn->query($sql) === TRUE) {
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
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
    
    /* Login form container */
    .login-container {
      background-color: white;
      padding: 2rem 0;
      /* border-radius: 10px; */
      /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
      width: 95%;
      max-width: 750px; /* Adjusted form width */
      height: 90%;
      max-height: 300px;
    }

    .login-container .input-group {
      padding: 0 2rem !important;
    }

    /* Username and password input styles */
    .form-control {
      border: 2px solid #2e5a31 !important;
      border-radius: 0.25rem;
    }

    /* Sign-in button */
    .btn-success-profile {
      background-color: #2e5a31;
      width: 50%;
      border-radius: 5px;
      font-size: 1.5rem;
      margin: 1rem;
    }

    /* Eye icon for password */
    .eye-icon {
      right: 2.8rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    /* Forgot password link */
    a {
      color: black;
      font-size: 0.9rem;
      text-decoration: none;
    }
    .input-group-text {
      width: 13rem;
    } 
  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "profile";
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
    <section class="services section">
      <!-- Section Title -->
      <div class="container pt-2 d-flex justify-content-center align-items-center">
      <div class="login-container text-center">
      <h1>CHANGE PROFILE</h1>
      <hr class="hr-change-pass">
      <form method="POST" action="change_profile.php" enctype="multipart/form-data">
        <div class="input-group input-group-lg flex-nowrap mt-4">
            <label class="input-group-text" for="inputGroupFile01">Upload Profile</label>
            <input type="file" class="form-control" name="profilepic" id="inputGroupFile01" required>
        </div>
        <button type="submit" class="btn btn-success btn-success-profile mt-4">Confirm</button>
      </form>
    </div>
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
