<?php
// Define Imember as Indoor Member for database, so that the user cannot search the include/dbconnect.php in the link
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
// Fetch questions and answers from the `navita` table
$sql = "SELECT * FROM navita WHERE status = 'answered'";
$result = $conn->query($sql);

if (isset($_POST["submit_navita"])) {
  $question = $_POST['question'];
  $answer = NULL;
  $status = 'pending';

  $sql_insert = "INSERT INTO navita (question, answer, status, date_answered) VALUES ('$question', '$answer', '$status', NOW())";
  
  if ($conn->query($sql_insert) === TRUE) {
      echo "<script> alert('Your Question Added successfully! please wait the admin to answer it.'); window.location.href = 'index.php'; </script>";
      exit();
  } else {
      echo "" . $sql_insert . "<br>" . $conn->error;
      echo "<script> alert('Error: ' . $sql_insert . '<br>' . $conn->error); window.location.href = 'navita.php'; </script>";
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
  <link href="../assets/css/lightgallery.css" rel="stylesheet" />

  <style>
        .corner-image-gif {
    position: fixed;
    right: 0;
    bottom: 0;
    /* width: 300px;  */
    width: 20rem; 
    height: auto;
    margin: 0.5rem 2rem;  /* Add some margin if you want space from the edges */
    cursor: pointer;
    z-index: 999; /* Ensure it stays in front of other elements */

}
/* Hidden chat popup window */
.chat-popup {
    display: none;
    position: fixed;
    bottom: 90px;
    right: 10px;
    width: 500px;
    height: 55%;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    z-index: 1000;
    display: flex;
    flex-direction: column;  /* Ensure flexbox handles layout */
    overflow: hidden;
}
/* .chat-popup {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 10px;
    width: 500px;
    height: 600px;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    z-index: 1000;
    display: flex;
    flex-direction: column;  
    overflow: hidden;
} */

.chat-header {
    background-color: #2e5a31;
    color: white;
    padding: 10px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-body {
    flex-grow: 1;  
    padding: 10px;
    overflow-y: auto;  /* Make the body scrollable */
    max-height: calc(100% - 170px); /* Adjust based on footer height */
}

.chat-footer {
    padding: 10px 15px;
    border-top: 1px solid #ccc;
    height: 100%;
    background-color: white;
    display: flex;
    flex-direction: column;
}

.chat-footer input {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 100%;
}

.chat-footer button {
    padding: 10px;
    border-radius: 5px;
    border: none;
    background-color: #2e5a31;
    color: white;
    cursor: pointer;
    width: 100%;
}

.chat-footer button:hover {
    background-color: #213923;
}

textarea {
    flex-grow: 1;
    margin-top: 10px;
    padding: 10px;
    resize: none;
    border: 1px solid #ccc;
    border-radius: 5px;
}

textarea:focus {
    outline: none;
    border-color: #0078FF;
}
.navi {
  background-color: #2e5a31; 
  width: 65%; 
  margin: 0 auto; 
  border-radius: 45px; 
  padding: 1rem;
}
  </style>

</head>

<body class="index-page">
  <?php
    $nav_active = "index";
    include 'include/navbar.php';
  ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <!-- <img src="../assets/img/hero-bg.jpg" alt="" data-aos="fade-in"> -->

      <div class="container">
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
              <div class="sign-up-form text-center">
                <input type="text" placeholder="Search here . . ." readonly data-bs-toggle="modal" data-bs-target="#exampleModalNavigate" style="cursor: pointer;">
                <input type="submit" value="Search" style="color: white;" data-bs-toggle="modal" data-bs-target="#exampleModalNavigate">
              </div>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->
    <!-- Modal -->
<div class="modal fade" id="exampleModalNavigate" tabindex="-1" aria-labelledby="exampleModalNavigateLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalNavigateLabel">Navigate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="search-input">
            <i class="fas fa-search"></i>
            <input id="searchInput" class="form-control" type="text" placeholder="Search" aria-label="Search" onkeyup="searchRoom()" autofocus>
          </div>
          <div id="roomList" class="pt-2">
            <!-- Room list will appear here dynamically -->
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  <!-- About Section -->
<section id="about" class="about section light-background" style="display: none;">
  <div class="container pt-5" data-aos="fade-up" data-aos-delay="100">
    <div class="row align-items-xl-center gy-5">
      <h1 class="text-center">
        <b>
        Room Details
        </b>
      </h1>
      <p id="roomDetails"></p>
      <div id="roomImages" class="d-flex justify-content-center flex-wrap lightgallery">
        <!-- Images for LightGallery will be dynamically injected here -->
      </div>
    </div>
  </div>
</section>


 <!-- Explore Section -->
 <section id="explore" class="services section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Explore Mpas</h2>
      </div>
      <!-- End Section Title -->
      <div class="container" id="lightgallery">
        <a href="../assets/img/core/homepage.png">
          <img src="../assets/img/core/homepage.png" alt="HomePage Core" class="w-100 img-fluid">
        </a>
      </div>

    </section><!-- /Explore Section -->

 
  </main>

  <?php
    include 'include/footer-files.php';
  ?>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>

  <!-- lightgallery.js script -->
  <script src="../assets/js/lightgallery.js"></script>
  <script>
    // Initialize lightGallery
    lightGallery(document.getElementById("lightgallery"));
  </script>


</body>

</html>


<script>
  function searchRoom() {
    const searchTerm = document.getElementById("searchInput").value;

    if (searchTerm.length > 0) {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "search_room.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          document.getElementById("roomList").innerHTML = xhr.responseText;

          const roomLinks = document.querySelectorAll(".room-link");
          roomLinks.forEach(function (link) {
            link.addEventListener("click", function (event) {
              event.preventDefault();
              const roomId = this.getAttribute("data-room-id");
              fetchRoomDetails(roomId);
            });
          });
        }
      };
      xhr.send("search=" + searchTerm);
    } else {
      document.getElementById("roomList").innerHTML = "";
    }
  }


  function fetchRoomDetails(roomId) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "fetch_room_details.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        const data = JSON.parse(xhr.responseText); // Parse JSON response
        if (data.success) {
          updateHeroSection(data.details, data.images);

          // Hide the modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalNavigate'));
          if (modal) modal.hide();
        } else {
          alert(data.message || "No details found for this room.");
        }
      } catch (e) {
        console.error("Error parsing JSON:", e);
      }
    }
  };
  xhr.send("room_id=" + roomId);
} 

function updateHeroSection(details, images) {
  const aboutSection = document.getElementById("about");
  const roomDetailsElement = document.getElementById("roomDetails");
  const roomImagesElement = document.getElementById("roomImages");

  if (!aboutSection || !roomDetailsElement || !roomImagesElement) {
    console.error("About section or its elements not found.");
    return;
  }

  // Populate the room details
  roomDetailsElement.innerHTML = details;

// Populate the room images for LightGallery
const imageElements = images.map((img, index) => {
  const displayStyle = index === 0 ? "" : 'style="display: none;"'; // Show only the first image
  return `
    <a href="${img}" data-src="${img}" class="m-1" ${displayStyle}>
      <img src="${img}" alt="Room Image" class="img-fluid">
    </a>
  `;
}).join("");
  roomImagesElement.innerHTML = imageElements;

  // Initialize LightGallery (ensure LightGallery is loaded before calling this)
  if (window.lightGallery) {
    lightGallery(roomImagesElement, {
      thumbnail: true, // Enable thumbnails
      zoom: true, // Enable zoom functionality
      mode: 'lg-fade', // Animation mode
    });
  }

  // Show the About Section
  aboutSection.style.display = "block";

  // Optionally, scroll to the section for better user experience
  aboutSection.scrollIntoView({ behavior: "smooth" });
}


</script>

