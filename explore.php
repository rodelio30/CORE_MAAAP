<?php
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (!empty($_SESSION['user_id'])) {
    header("location: superadmin/index.php");
    exit;
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
        .model-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .model-container {
            /* border: 1px solid #ccc; */
            /* padding: 10px; */
            text-align: center;
            width: 100%;
        }
        .delete-btn {
            margin-top: 10px;
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
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

  
    
    <!-- Explore Section -->
    <section id="explore" class="services section light-background">
      <!-- Section Title -->
      <div class="container section-title">
        <h2>Explore Maps</h2>
      </div>
      <!-- End Section Title -->
      <div class="container">
          <div id="model-gallery" class="model-gallery"></div>
      </div>

    </section><!-- /Explore Section -->


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
  <!-- <script src="assets/vendor/swiper/swiper-bundle.min.js"></script> -->

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/navita.js"></script>

  <!-- Three.js and GLTFLoader -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three/examples/js/loaders/GLTFLoader.js"></script> -->

<!-- <script type="module"
    src="https://unpkg.com/@google/model-viewer@latest/dist/model-viewer.min.js">
</script> -->

</body>

</html>

<script>
    async function loadModels() {
        try {
            const response = await fetch('fetch_models.php'); // Get models from PHP
            const models = await response.json(); // Convert response to JSON
            
            models.forEach(renderModel); // Render each model
        } catch (error) {
            console.error("Error loading models:", error);
        }
    }

    function renderModel(model) {
        const modelContainer = document.createElement('div');
        modelContainer.className = 'model-container';

        const modelViewer = document.createElement('model-viewer');
        modelViewer.setAttribute('src', model.url);
        modelViewer.setAttribute('camera-controls', '');
        modelViewer.setAttribute('ar', '');
        modelViewer.setAttribute('ar-modes', 'scene-viewer webxr');
        modelViewer.setAttribute('alt', 'A 3D model');
        modelViewer.setAttribute('style', 'width: 100%; height: 650px;');

        // **Default Camera Position (Back View)**
    const defaultOrbit = '175deg 87deg 2.5m';
    modelViewer.setAttribute('camera-orbit', defaultOrbit);
    modelViewer.setAttribute('field-of-view', '90deg'); // Wider FOV for less zoom
    // modelViewer.setAttribute('min-camera-orbit', 'auto auto 2.0m'); // Adjust min zoom-out
    // modelViewer.setAttribute('max-camera-orbit', 'auto auto 0.5m'); // Adjust max zoom

    let inactivityTimer;

function resetModelPosition() {
    modelViewer.setAttribute('camera-orbit', defaultOrbit); // Reset position
    restartTimer(); // Restart timer so it resets again after another 60s of inactivity
}

function restartTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(resetModelPosition, 30000); // Reset after 30 seconds 
}

// **Track User Interaction**
modelViewer.addEventListener('interaction-start', restartTimer);
modelViewer.addEventListener('interaction-end', restartTimer);

// **Start Timer on Load**
inactivityTimer = setTimeout(resetModelPosition, 30000);

        const modelInfo = document.createElement('div');
        modelInfo.textContent = model.name;

        modelContainer.appendChild(modelViewer);
        modelContainer.appendChild(modelInfo);
        document.getElementById('model-gallery').appendChild(modelContainer);
    }

    loadModels(); // Fetch models when the page loads
</script>

<script type="module" src="https://unpkg.com/@google/model-viewer@latest/dist/model-viewer.min.js"></script>