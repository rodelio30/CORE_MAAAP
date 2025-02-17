<?php
// Define Imember as Indoor Member for database, so that the user cannot search the include/dbconnect.php in the link
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (!empty($_SESSION['user_id'])) {
    header("location: superadmin/index.php");
    exit;
}

if (isset($_POST["submit_admin"])) {

  $username = $_POST['username'];
  $password = $_POST["password"];

  $query = "SELECT * FROM users WHERE username = ?"; 
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $username); 
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      if (hash('md5', $password) === $row['password']) {
          $_SESSION["login"] = true;
          $_SESSION["user_id"] = $row["user_id"];
          $_SESSION["username"] = $row["username"];
          $_SESSION["type"] = $row["type"];
          $_SESSION["profile_pic"] = $row["profile_pic"];

          sleep(1);
          header("Location: superadmin/index.php");
          exit;
      } else {
          echo "<script> alert('Wrong Password'); </script>";
      }
  } else {
      echo "<script> alert('User Not Registered'); </script>";
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
  <link href="assets/img/core/coreguide-logo.png" rel="icon">
  <link href="assets/img/core/coreguide-logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <link href="assets/css/lightgallery.css" rel="stylesheet" />
  <style>
      section,
      .section {
        padding: 0;
      }
      .search-input {
      position: relative;
    }

    .search-input input {
      /* padding-left: 2.5rem;  */
      padding: 0.8rem 1rem 0.8rem 2.5rem;
      background-color: #2e5a31;
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

    /* Eye icon for password */
    .eye-icon {
      right: .8rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

  </style>

</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo">
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#hero" class="active">Navigate</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#team">Team</a></li>
          <li><a href="index.php#contact">Contact</a></li>
          <li><a href="index.php#explore">Explore Map</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted me-5" href="login.php">Login</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <div class="container">
          <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
          <div class="login-container text-center">
            <form action="" method="post" style="padding: 2rem; border-radius: 15px; border: 2px solid #2e5a31;">
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">Username</span>
                <input type="text" class="form-control" name="username" aria-describedby="addon-wrapping" style="border-color: #2e5a31 !important;" required autofocus>
              </div>
              <div class="input-group flex-nowrap mt-3 position-relative">
                <span class="input-group-text" id="addon-wrapping">Password&nbsp</span>
                <input type="password" id="login_password" class="form-control" name="password" aria-describedby="addon-wrapping" style="border-color: #2e5a31 !important; border-radius: 0.25rem;" required>
                <span class="position-absolute eye-icon" onclick="togglePassword('login_password', 'eye_login')">
                  <i class="bi bi-eye-fill" id="eye_login"></i>
                </span>
              </div>
              <button type="submit" name="submit_admin" class="btn btn-success mt-4" style="background-color: #2e5a31;">Sign in</button>
              <!-- <a href="create_superadmin.php" class="d-block mt-2"><u>Create Super Admin Account?</u></a> -->
            </form>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->
  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
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