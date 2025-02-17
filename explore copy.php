<?php
// Define Imember as Indoor Member for database, so that the user cannot search the include/dbconnect.php in the link
define('Imember', true); 
require('include/dbconnect.php'); // Connect to the database

if (!empty($_SESSION['user_id'])) {
    header("location: superadmin/index.php");
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


// Handle form submission
if (isset($_POST["submit_feedback"])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Insert data into the feedback table
  $sql = "INSERT INTO feedback (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('Feedback submitted successfully!'); </script>";
  } else {
      echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
      echo "<script> alert('Error: " . $conn->error . "'); </script>";
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
  <link href="assets/css/custom.css" rel="stylesheet">
  <link href="assets/css/lightgallery.css" rel="stylesheet" />
  
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
      <div class="container" id="lightgallery">
        <a href="assets/img/core/homepage.png">
          <img src="assets/img/core/homepage.png" alt="HomePage Core" class="w-100 img-fluid">
        </a>
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
      xhr.send("search=" + encodeURIComponent(searchTerm));
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
            updateHeroSection(data.details, data.images, data.schedules);

             // Update the modal with schedule details
            updateScheduleModal(data.schedules);

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
    xhr.send("room_id=" + encodeURIComponent(roomId));
  }

  function updateScheduleModal(schedules) {
  const modalBody = document.getElementById("scheduleModalBody");

  if (!modalBody) {
    console.error("Schedule modal body not found.");
    return;
  }

  if (schedules.length > 0) {
      // Populate the schedule modal with fetched data
  modalBody.innerHTML = schedules.map(schedule => `
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title mb-1">
          <i class="bi bi-person-circle"></i> <strong>Name:</strong> ${schedule.employee_name}
        </h5>
        <p class="card-text mb-1">
          <i class="bi bi-building"></i> <strong>Department:</strong> ${schedule.department}
        </p>
        <p class="card-text mb-1">
          <i class="bi bi-briefcase"></i> <strong>Position:</strong> ${schedule.position}
        </p>
        ${schedule.position === 'teaching' ? `
          <p class="card-text mb-1">
            <i class="bi bi-calendar3"></i> <strong>Day:</strong> ${schedule.day}
          </p>
          <p class="card-text mb-1">
            <i class="bi bi-clock"></i> <strong>Time In:</strong> ${schedule.time_in} | <strong>Time Out:</strong> ${schedule.time_out}
          </p>
          <p class="card-text mb-1">
            <i class="bi bi-book"></i> <strong>Subject:</strong> ${schedule.subject}
          </p>
        ` : ''}
      </div>
    </div>
  `).join("");
  } else {
    modalBody.innerHTML = "<p>No schedules available for this room.</p>";
  }

  // Show the modal
  // const modal = new bootstrap.Modal(document.getElementById("scheduleModal"));
  // modal.show();
}

function fetchSchedulesForModal() {
  // Ensure you fetch the schedules dynamically when the button is clicked
  const modal = new bootstrap.Modal(document.getElementById("scheduleModal"));
  modal.show();
}

  function updateHeroSection(details, images, schedules) {
    const aboutSection = document.getElementById("about");
    const roomDetailsElement = document.getElementById("roomDetails");
    const roomImagesElement = document.getElementById("roomImages");
    const roomScheduleElement = document.getElementById("roomSchedule");

    if (!aboutSection || !roomDetailsElement || !roomImagesElement || !roomScheduleElement) {
      console.error("About section or its elements not found.");
      return;
    }


     // Format room details as a string (assuming details is an object)
  let formattedDetails = "";
  if (typeof details === "object" && details !== null) {
    for (const [key, value] of Object.entries(details)) {
      formattedDetails += `<strong>${key}:</strong> ${value}<br>`;
    }
  } else {
    formattedDetails = details || "No details available."; // Fallback if details is not an object or is null
  }

  // Populate the room details
  roomDetailsElement.innerHTML = `
    ${formattedDetails}
    <div class="mt-3 text-center">
      <button id="viewSchedulesButton" class="btn btn-primary">View Schedules</button>
    </div>
  `;

  // Add event listener to the "View Schedules" button
  const viewSchedulesButton = document.getElementById("viewSchedulesButton");
  viewSchedulesButton.addEventListener("click", function () {
    fetchSchedulesForModal();
  });
    // roomDetailsElement.innerHTML = details;

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
      // Automatically "click" the first image link
      const firstImageLink = roomImagesElement.querySelector('a');
      if (firstImageLink) {
        firstImageLink.click(); // Simulate a click on the first image
      } else {
        console.error("No images found in the gallery.");
      }
    }


    // Show the About Section
    aboutSection.style.display = "block";

    // Optionally, scroll to the section for better user experience
    aboutSection.scrollIntoView({ behavior: "smooth" });
  }
</script>
