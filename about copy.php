<?php
// Define Imember as Indoor Member for database, so that the user cannot search the include/dbconnect.php in the link
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (!empty($_SESSION['user_id'])) {
    header("location: superadmin/index.php");
    exit;
}
// Fetch about sections
$query = "SELECT * FROM about_sections";
$result = $conn->query($query);
$about_sections = [];
while ($row = $result->fetch_assoc()) {
    $about_sections[] = $row;
}

// Fetch core values
$query = "SELECT * FROM core_values WHERE section_id = 3 ORDER BY `order` ASC";
$result = $conn->query($query);
$core_values = [];
while ($row = $result->fetch_assoc()) {
    $core_values[] = $row;
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
  <link href="assets/css/custom.css" rel="stylesheet">
  <link href="assets/css/lightgallery.css" rel="stylesheet" />
  
  <style>
    .address-content {
      background-color: white; 
      padding: 1.2rem;
      border-radius: 10px;
    }
  </style>
</head>

<body class="index-page">
  <?php
    include 'index-header.php';
  ?>

  <main class="main">
    <div class="mt-2">
      <h1 class="text-center">
        <a href="explore.php">
          <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo" class="mb-2" width="60">
        </a>
          <b>
            <span class="pt-3">
              CORE GUIDE
            </span>
          </b>
      </h1>
    </div>


    <!-- About Section -->
    <section id="about-company" class="about section" style="background-color: #f5f5f5;">

      <div class="container">
         <!-- End Section Title -->
      <div class="container section-title">
        <h2>About</h2>
      </div>
        <div class="row gy-5">

          <div class="col-xl-7 content">
            <h2>CORE Guide</h2>
            <p>
            is envisioned to be a multi-platform application that will allow users to navigate the CGCI campus using an interactive indoor map with an intelligent chatbot. The application is intended to enhance student and employee productivity and improve the visitor experience in accessibility and indoor campus navigation.
            </p>

            <div class="row gy-4">
              <div class="col-md-6">
                <div class="address-content">
                <div class="info-item">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Address</h3>
                  <p>Core Gateway College</p>
                  <p>San Jose, Nueva Ecija, Philippines 3121</p>
                </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="address-content">
                <div class="info-item">
                  <i class="bi bi-telephone"></i>
                  <h3>Call Us</h3>
                  <p>+1 5589 55488 55</p>
                  <p>+1 6678 254445 41</p>
                </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="address-content">
                <div class="info-item">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Us</h3>
                  <p>navita_info@example.com</p>
                  <p>navita@example.com</p>
                </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="address-content">
                <div class="info-item">
                  <i class="bi bi-clock"></i>
                  <h3>Open Hours</h3>
                  <p>Monday - Friday</p>
                  <p>9:00AM - 05:00PM</p>
                </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-5">
            <div class="row gy-4 icon-boxes">

              <div class="col-md-12">
                <div class="icon-box">
                  <h3>Vision</h3>
                  <p>
                    Core Gateway College, Inc. envisions itself as the leading educational institution that provides inclusive and excellent education responsive to the call of the time.
                  </p>
                </div>
              </div> 

              <div class="col-md-12">
                <div class="icon-box">
                  <h3>Mission</h3>
                  <p>
                    CGCI is committed to providing well-rounded academic programs combined with excellent leadership in teaching, research, community service, and production relevant to last changing communities locally and globally.
                  </p>
                </div>
              </div> 
              
              <div class="col-md-12">
                <div class="icon-box">
                  <h3>Core Values</h3>
                  <div class="card p-2 mb-2">
                    <h5 class="mb-0">Excellence</h5>
                    <p><b>Learning to soar</b></p>
                    <p>
                      we are an exemplary learning community that educates learners to achieve their best.
                    </p>
                  </div>

                  <div class="card p-2 mb-2">
                    <h5 class="mb-0">
                    Integrity
                    </h5>
                    <p>
                      <b>
                      Seeking Truth Always
                      </b>
                    </p>
                    <p>
                    we stand for what is just and right. We shape learners with character and humor.
                    </p>
                  </div>

                  <div class="card p-2 mb-2">
                    <h5 class="mb-0">
                    Respect
                    </h5>
                    <p>
                      <b>
                      Every Student Matter
                      </b>
                    </p>
                    <p>
                    We educate learners in the richness of their past, the diversity oof their present, and the possibilities of their future.
                    </p>
                  </div>

                  <div class="card p-2 mb-2">
                    <h5 class="mb-0">
                    Responsibility
                    </h5>
                    <p>
                      <b>
                      Leaders Grow Here 
                      </b>
                    </p>
                    <p>
                    We develop responsible learners who are actively growing and helping one another succeed.
                    </p>
                  </div>
                </div>
              </div> 


            </div>
          </div>

        </div>
      </div>

    </section><!-- /About Section -->

      </main>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="assets/vendor/php-email-form/validate.js"></script> -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/navita.js"></script>

  <!-- lightgallery.js script -->
  <script src="assets/js/lightgallery.js"></script>

  <script>
    // Initialize lightGallery
    lightGallery(document.getElementById("lightgallery"));
  </script>


</body>

</html>


