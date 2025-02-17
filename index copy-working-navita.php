<?php
// Define Imember as Indoor Member for database, so that the user cannot search the include/dbconnect.php in the link
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (!empty($_SESSION['user_id'])) {
    header("location: ../superadmin/index.php");
    exit;
}
// Fetch questions and answers from the `navita` table
$sql = "SELECT * FROM navita WHERE status = 'answered'";
$result = $conn->query($sql);

if (isset($_POST["submit_navita"])) {
  $question = $conn->real_escape_string($_POST['question']);
  $answer = NULL;
  $status = 'pending';

  $sql_insert = "INSERT INTO navita (question, answer, status, date_answered) VALUES ('$question', '$answer', '$status', NOW())";

  if ($conn->query($sql_insert) === TRUE) {
      echo "<script>
          alert('Your question has been added successfully! Please wait for the admin to answer it.');
          window.location.href = 'index.php';
      </script>";
  } else {
      echo "<script>
          alert('Error: " . $conn->error . "');
          window.location.href = 'index.php';
      </script>";
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
      .modal {
    z-index: 1050; /* Bootstrap default */
}
    .search-input {
      position: relative;
    }

    .search-input input {
      /* padding-left: 2.5rem;  */
      padding: 0.8rem 1rem 0.8rem 2.5rem;
      background-color: transparent;
      border: 2px solid #2e5a31;
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
    .corner-image {
    position: fixed;
    right: 0;
    bottom: 0;
    width: 150px;  /* Adjust the size as needed */
    height: auto;
    margin: 3rem 5rem;  /* Add some margin if you want space from the edges */
    cursor: pointer;
}
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

#questionList {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    width: 100%;
    z-index: 1050;
    max-height: 200px;
    overflow-y: auto;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

#questionList .list-group-item {
    cursor: pointer;
}

#questionList .list-group-item:hover {
    background-color: #f8f9fa;
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

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo">
        <!-- <h1 class="sitename">Append</h1><span>.</span> -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#hero" class="active">Navigate</a></li>
          <li><a href="index.php#about-company">About</a></li>
          <li><a href="index.php#team">Team</a></li>
          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
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
        <!-- <h1>Room Details:</h1>
        <h1>Descriptions:</h1>
        <h1>Other Descriptions:</h1>
        <h1>Other Descriptions:</h1> -->
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
          <div class="sign-up-form text-center">
            <input type="text" placeholder="Search here . . ." readonly data-bs-toggle="modal" data-bs-target="#exampleModalNavigate" style="cursor: pointer;">
            <input type="submit" value="Search" style="color: white;" data-bs-toggle="modal" data-bs-target="#exampleModalNavigate">
          </div>
        </div>
      </div>

    </section>
    <!-- /Hero Section -->

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
<section id="about" class="about section dark-background" style="display: none;">
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


    <!-- About Section -->
    <section id="about-company" class="about section light-background">

      <div class="container pt-5" data-aos="fade-up" data-aos-delay="100">
         <!-- End Section Title -->
        <div class="row align-items-xl-center gy-5">

          <div class="col-xl-5 content">
            <h3>About Us</h3>
            <h2>CORE Guide</h2>
            <p>
            is envisioned to be a multi-platform application that will allow users to navigate the CGCI campus using an interactive indoor map with an intelligent chatbot. The application is intended to enhance student and employee productivity and improve the visitor experience in accessibility and indoor campus navigation.
            </p>
            <a href="#" class="read-more"><span>Search</span><i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="col-xl-7">
            <div class="row gy-4 icon-boxes">

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box">
                  <i class="bi bi-buildings"></i>
                  <h3>Eius provident</h3>
                  <p>Magni repellendus vel ullam hic officia accusantium ipsa dolor omnis dolor voluptatem</p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                  <i class="bi bi-clipboard-pulse"></i>
                  <h3>Rerum aperiam</h3>
                  <p>Autem saepe animi et aut aspernatur culpa facere. Rerum saepe rerum voluptates quia</p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                  <i class="bi bi-command"></i>
                  <h3>Veniam omnis</h3>
                  <p>Omnis perferendis molestias culpa sed. Recusandae quas possimus. Quod consequatur corrupti</p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="icon-box">
                  <i class="bi bi-graph-up-arrow"></i>
                  <h3>Delares sapiente</h3>
                  <p>Sint et dolor voluptas minus possimus nostrum. Reiciendis commodi eligendi omnis quideme lorenda</p>
                </div>
              </div> <!-- End Icon Box -->

            </div>
          </div>

        </div>
      </div>

    </section><!-- /About Section -->




    <!-- Team Section -->
    <section id="team" class="team section dark-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Team</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
            <div class="member-img">
              <img src="assets/img/core/team/team.png" class="img-fluid" alt="">
            </div>
            <div class="member-info text-center">
              <h4>Nessie Inocencio </h4>
              <span>Bachelor of Science in Computer Science</span>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="200">
            <div class="member-img">
              <img src="assets/img/core/team/team.png" class="img-fluid" alt="">
            </div>
            <div class="member-info text-center">
              <h4>Raymond Dela Cruz</h4>
              <span>Bachelor of Science in Computer Science</span>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
            <div class="member-img">
              <img src="assets/img/core/team/team.png" class="img-fluid" alt="">
            </div>
            <div class="member-info text-center">
              <h4>Marjorie Encarnacion</h4>
              <span>Bachelor of Science in Computer Science</span>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="200">
            <div class="member-img">
              <img src="assets/img/core/team/team.png" class="img-fluid" alt="">
            </div>
            <div class="member-info text-center">
              <h4>Analie Grace Tumbaga</h4>
              <span>Bachelor of Science in Computer Science</span>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
            <div class="member-img">
              <img src="assets/img/core/team/team.png" class="img-fluid" alt="">
            </div>
            <div class="member-info text-center">
              <h4>Emman San Pedro</h4>
              <span>Bachelor of Science in Computer Science</span>
            </div>
          </div><!-- End Team Member -->


        </div>

      </div>

    </section><!-- /Team Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section" style="background-color: #f5f5f5;">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6">

            <div class="row gy-4">
              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Address</h3>
                  <p>Core Gateway College</p>
                  <p>San Jose, Nueva Ecija, Philippines 3121</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Call Us</h3>
                  <p>+1 5589 55488 55</p>
                  <p>+1 6678 254445 41</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Us</h3>
                  <p>info@example.com</p>
                  <p>contact@example.com</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="500">
                  <i class="bi bi-clock"></i>
                  <h3>Open Hours</h3>
                  <p>Monday - Friday</p>
                  <p>9:00AM - 05:00PM</p>
                </div>
              </div><!-- End Info Item -->

            </div>

          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                </div>

                <div class="col-12">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                </div>

                <div class="col-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->
    
    <!-- Explore Section -->
    <section id="explore" class="services section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Explore Mpas</h2>
      </div>
      <!-- End Section Title -->
      <div class="container" id="lightgallery">
        <a href="assets/img/core/homepage.png">
          <img src="assets/img/core/homepage.png" alt="HomePage Core" class="w-100 img-fluid">
        </a>
      </div>

    </section><!-- /Explore Section -->


    <!-- <img src="../img/navita.png" alt="Chat Icon" class="corner-image" id="chatIcon"> -->
  <img src="assets/img/icon/navita.gif" alt="Chat Icon" class="corner-image-gif" id="chatIcon">
 <!-- Chat pop-up window -->
   <!-- Chat pop-up window -->
   <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
          <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo" width="40" style="background-color: white;">
            <h3 class="m-2" style="color: white;">Navita</h3>
            <button class="close-btn" id="closeBtn">&times;</button>
        </div>
        <div class="chat-body">
        <p>Ask your question below:</p>
        <form id="questionForm" action="index.php" method="post">
            <div class="mb-2 position-relative">
                <input 
                    type="text" 
                    id="questionInput" 
                    class="form-control" 
                    name="question" 
                    placeholder="Type your question..." 
                    autocomplete="off" 
                    required
                >
                <ul id="questionList" class="list-group" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                    <!-- Matching questions will appear here -->
                </ul>
            </div>
            <div id="answerDisplay" class="mt-2" style="display: none;">
                <div class="alert alert-info"></div>
            </div>
            <button type="submit" id="submitButton" name="submit_navita" class="btn btn-primary mt-2" disabled>Submit</button>
        </form>
    </div>
    </div>
  </main>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

  <!-- lightgallery.js script -->
  <script src="assets/js/lightgallery.js"></script>
  <script>
    // Initialize lightGallery
    lightGallery(document.getElementById("lightgallery"));
  </script>


</body>

</html>


<script>
// Get elements
const chatIcon = document.getElementById('chatIcon');
const chatPopup = document.getElementById('chatPopup');
const closeBtn = document.getElementById('closeBtn');

window.onload = function() {
    chatPopup.style.display = 'none';
};

// Show the chat popup when clicking the chat icon
chatIcon.addEventListener('click', function() {
    // chatPopup.style.display = 'block';
    chatPopup.style.display = 'inline-block';
});

// Hide the chat popup when clicking the close button
closeBtn.addEventListener('click', function() {
    chatPopup.style.display = 'none';
});

</script>

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

<script>
const questionInput = document.getElementById('questionInput');
const questionList = document.getElementById('questionList');
const answerDisplay = document.getElementById('answerDisplay');
const submitButton = document.getElementById('submitButton');

questionInput.addEventListener('input', function () {
    const query = this.value.trim();

    if (query.length > 0) {
        // Fetch matching questions dynamically
        fetch('fetch_questions.php?q=' + encodeURIComponent(query))
            .then((response) => response.json())
            .then((data) => {
                questionList.innerHTML = '';
                if (data.length > 0) {
                    questionList.style.display = 'block';
                    answerDisplay.style.display = 'none';
                    data.forEach((item) => {
                        const li = document.createElement('li');
                        li.textContent = item.question;
                        li.className = 'list-group-item list-group-item-action';
                        li.addEventListener('click', () => {
                            questionInput.value = li.textContent;
                            questionList.style.display = 'none';
                            displayAnswer(item.answer);
                            submitButton.disabled = true; // Disable submit if a match is found
                        });
                        questionList.appendChild(li);
                    });
                } else {
                    questionList.style.display = 'none';
                    answerDisplay.style.display = 'none';
                    submitButton.disabled = false; // Enable submit if no matches are found
                }
            })
            .catch((error) => {
                console.error('Error fetching questions:', error);
                questionList.style.display = 'none';
            });
    } else {
        questionList.style.display = 'none';
        answerDisplay.style.display = 'none';
        submitButton.disabled = true; // Disable submit if input is empty
    }
});

// Function to display the answer below the input field
function displayAnswer(answer) {
    const answerAlert = answerDisplay.querySelector('.alert');
    answerAlert.textContent = answer || 'No answer available for this question.';
    answerDisplay.style.display = 'block';
}

// Hide the question list when clicking outside
document.addEventListener('click', function (e) {
    if (!questionList.contains(e.target) && !questionInput.contains(e.target)) {
        questionList.style.display = 'none';
    }
});
</script>