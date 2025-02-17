<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $current_password = md5($_POST['current_password']); // Encrypt current password using MD5
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];
  
  // Assume user ID is stored in session
  $user_id = $_SESSION['user_id'];
  
  // Fetch current password from database
  $sql = "SELECT password FROM users WHERE user_id = '$user_id'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $db_password = $row['password'];
      
      // Check if current password matches
      if ($current_password === $db_password) {
          // Check if new password and confirm password match
          if ($new_password === $confirm_password) {
              // Encrypt the new password using MD5
              $new_password_encrypted = md5($new_password);
              
              // Update the password in the database
              $update_sql = "UPDATE users SET password = '$new_password_encrypted', user_status = 'old' WHERE user_id = '$user_id'";
             
              if ($conn->query($update_sql) === TRUE) {
                  // echo "<script> alert('Password successfully updated!'); window.location.href = 'index.php'; </script>";
                    echo "<script>
                            window.onload = function() {
                              var myModal = new bootstrap.Modal(document.getElementById('loginChoiceModal'));
                              myModal.show();
                            };
                          </script>";
              } else {
                  echo "<script> alert('Error updating password: ' . $conn->error); </script>";
              }
          } else {
              echo "<script> alert('New password and confirmation do not match.'); </script>";
          }
      } else {
          echo "<script> alert('Current password is incorrect.'); </script>";
      }
  } else {
      echo "<script> alert('User not found.'); </script>";
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
      max-height: 500px;
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
    $nav_active = "change";
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
      <div class="container d-flex justify-content-center align-items-center">

      <div class="login-container text-center">
      <h1>
        <b>
          CHANGE PASSWORD
        </b>
      </h1>
      <hr class="hr-change-pass">
      <form method="POST" action="change_password.php">
      <div class="input-group input-group-lg flex-nowrap mt-4">
        <span class="input-group-text" id="addon-wrapping">Current Password</span>
        <input type="password" id="current_password" name="current_password" class="form-control" style="border-color: #2e5a31 !important;" required>
        <span class="position-absolute eye-icon" onclick="togglePassword('current_password', 'eye1')">
          <i class="bi bi-eye-fill" id="eye1"></i>
        </span>
      </div>

      <div class="input-group input-group-lg flex-nowrap mt-4 position-relative">
        <span class="input-group-text" id="addon-wrapping">New Password</span>
        <input type="password" id="new_password" name="new_password" class="form-control" style="border-color: #2e5a31 !important;" required>
        <span class="position-absolute eye-icon" onclick="togglePassword('new_password', 'eye2')">
          <i class="bi bi-eye-fill" id="eye2"></i>
        </span>
      </div>

      <div class="input-group input-group-lg flex-nowrap mt-4 position-relative">
        <span class="input-group-text" id="addon-wrapping">Re-enter Password</span>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" style="border-color: #2e5a31 !important;" required>
        <span class="position-absolute eye-icon" onclick="togglePassword('confirm_password', 'eye3')">
          <i class="bi bi-eye-fill" id="eye3"></i>
        </span>
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



<script>
  document.getElementById('stayLoggedInBtn').addEventListener('click', function() {
    window.location.href = 'index.php'; // Redirect to the dashboard or home page
  });

  document.getElementById('logoutBtn').addEventListener('click', function() {
    // Perform logout operation (destroy session and redirect to login)
    window.location.href = '../include/signout.php'; // Assuming you have a logout script
  });
</script>

<script>
  function togglePassword(fieldId, eyeId) {
    var passwordField = document.getElementById(fieldId);
    var eyeIcon = document.getElementById(eyeId);

    if (passwordField.type === "password") {
      passwordField.type = "text";
      eyeIcon.classList.remove('bi-eye-fill');
      eyeIcon.classList.add('bi-eye-slash'); // Change the icon to an eye with a slash
    } else {
      passwordField.type = "password";
      eyeIcon.classList.remove('bi-eye-slash');
      eyeIcon.classList.add('bi-eye-fill'); // Change the icon back to the regular eye
    }
  }
</script>