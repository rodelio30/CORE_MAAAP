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
  <link href="../assets/css/custom.css" rel="stylesheet">
  <link href="../assets/css/lightgallery.css" rel="stylesheet" />

  

</head>

<body class="index-page">
  <?php
    $nav_active = "index";
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

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <!-- <img src="../assets/img/hero-bg.jpg" alt="" data-aos="fade-in"> -->

      <div class="container">
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center align-items-center">
            <form>
              <div class="search-input text-center">
                <small style="color: red;" class="text-center m-0 mt-2 p-0">** Search for Room or Teacher’s Name **</small>
                <input id="searchInput" class="form-control" type="text" placeholder="Search here . . ." aria-label="Search" onkeyup="searchRoom()" autofocus>
              </div>
              <div id="roomList" class="pt-2">
              </div>
            </form>
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
  <div class="container">
    <div class="row align-items-xl-center gy-1">
      <h5 class="text-center" style="padding:0; margin: 0; font-weight: 400;" >
        <strong>
        Room Details
        </strong>
      </h5>
      <p id="roomDetails" class="text-center"></p>
      <small style="color: red;" class="text-center m-0 mt-2 p-0">** Click the image to view **</small>
      <!-- <div id="roomImages" class="d-flex justify-content-center flex-wrap lightgallery"> -->
      <div id="roomImages" class="d-flex justify-content-center flex-wrap lightgallery" style="width: 60%; margin: 0 auto;">
        <!-- Images for LightGallery will be dynamically injected here -->
      </div>
      <!-- Room Schedules -->
      <div id="roomScheduleSection" class="mt-4">
        <ul id="roomSchedule" class="list-group">
          <!-- Schedules will be dynamically injected here -->
        </ul>
      </div>
    </div>
  </div>
</section>
<!-- Employee Details Section -->
<section id="employeeSection" class="about section light-background pt-3" style="display: none;">
<div class="container">
    <!-- Employee Details Section -->
    <div class="row align-items-center justify-content-center mb-4">
      <h3 class="text-center"><strong>Employee Details</strong></h3>
    </div>
    <div class="row align-items-center justify-content-center">
      <div class="col-md-8 text-center">
        <h4><i class="bi bi-person-circle"></i> <strong>Name:</strong> <span id="employeeName"></span></h4>
        <h5><i class="bi bi-building"></i> <strong>Department:</strong> <span id="employeeDepartment"></span></h5>
        <h5><i class="bi bi-person-badge"></i> <strong>Position:</strong> <span id="employeePosition"></span></h5>
      </div>
    </div>

    <!-- Employee Schedule Section -->
    <div class="row justify-content-center mt-3">
      <div class="col-md-2"></div>
      <div id="employeeSchedule" class="col-md-8">
        <!-- This will be dynamically populated -->
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
</section>
<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">ROOM SCHEDULES</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="scheduleModalBody">
        <!-- Schedule details will be dynamically injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
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

  <!-- lightgallery.js script -->
  <script src="../assets/js/lightgallery.js"></script>
  <script>
    // Initialize lightGallery
    lightGallery(document.getElementById("lightgallery"));
  </script>


</body>

</html>
<script>

function convertTo12Hour(time) {
  if (!time) return ""; // Handle null/empty values
  const [hours, minutes] = time.split(":");
  const hour = parseInt(hours, 10);
  const period = hour >= 12 ? "PM" : "AM";
  const formattedHour = hour % 12 || 12; // Convert 0 to 12 for AM
  return `${formattedHour}:${minutes} ${period}`;
}
  function searchRoom() {
    const searchTerm = document.getElementById("searchInput").value;
    const heroSection = document.getElementById("hero");

    if (searchTerm.length > 0) {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "../search_room.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          document.getElementById("roomList").innerHTML = xhr.responseText;

          // const roomLinks = document.querySelectorAll(".room-link");
          // roomLinks.forEach(function (link) {
          //   link.addEventListener("click", function (event) {
          //     event.preventDefault();
          //     const roomId = this.getAttribute("data-room-id");
          //     fetchRoomDetails(roomId);
          //     heroSection.style.display = "none";
          //   });
          // });
          document.querySelectorAll(".view-details").forEach(function (button) {
                    button.addEventListener("click", function (event) {
                        event.preventDefault(); // Prevent default action

                        const roomId = this.getAttribute("data-room-id");
                        const employeeId = this.getAttribute("data-employee-id");

                        if (roomId) {
                            fetchRoomDetails(roomId);
                            heroSection.style.display = "none";
                        } else if (employeeId) {
                            fetchEmployeeDetails(employeeId);
                            heroSection.style.display = "none";
                        }
                    });
                });
        }
      };
      xhr.send("search=" + searchTerm);
    } else {
      document.getElementById("roomList").innerHTML = "";
    }
  }

function fetchEmployeeDetails(employeeId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../fetch_employee_details.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                console.log(data); // Debug: Log the response

                if (data.success) {
                    // Hide the room details section (if any)
                    document.getElementById("about").style.display = "none";

                    // Show the employee details section
                    document.getElementById("employeeSection").style.display = "block";
                    document.getElementById("employeeName").innerText = data.name;
                    document.getElementById("employeeDepartment").innerText = data.department;
                    document.getElementById("employeePosition").innerText = data.position;

                    // Display schedule
                    const scheduleContainer = document.getElementById("employeeSchedule");
                    scheduleContainer.innerHTML = ""; // Clear existing schedules

                    // Mapping short day names to full day names
                    const dayMapping = {
                        Mon: "Monday",
                        Tue: "Tuesday",
                        Wed: "Wednesday",
                        Thu: "Thursday",
                        Fri: "Friday",
                        Sat: "Saturday"
                    };

                    let scheduleHTML = `<div class="accordion" id="scheduleAccordion">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">`; // Flex container for buttons

// Loop through day mapping and check if data exists for each day
for (const [shortDay, fullDay] of Object.entries(dayMapping)) {
    if (data.schedule[shortDay] && data.schedule[shortDay].length > 0) {
        scheduleHTML += `
            <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${shortDay}" aria-expanded="false">
                ${fullDay}
            </button>`;
    }
}

scheduleHTML += `</div>`; // Close flex container

// Loop again to generate collapsible schedule details
for (const [shortDay, fullDay] of Object.entries(dayMapping)) {
    if (data.schedule[shortDay] && data.schedule[shortDay].length > 0) {
        scheduleHTML += `
            <div class="collapse mt-2" id="collapse${shortDay}" data-bs-parent="#scheduleAccordion">
                <div class="card card-body">
                    <div class="row">
                    <div class="col-md-7"><b>Time</b></div>
                        <div class="col-md-3"><b>Subject</b></div>
                        <div class="col-md-2"><b>Room</b></div>
                    </div>`;

        // Loop through the schedules for the current day
        data.schedule[shortDay].forEach(schedule => {
            scheduleHTML += `
                <div class="row">
                    <div class="col-md-7"><i class="bi bi-clock"></i>  <strong>Time In:</strong>${convertTo12Hour(schedule.time_in)} -  <strong>Time Out:</strong>${convertTo12Hour(schedule.time_out)}</div>
                    <div class="col-md-3"><i class="bi bi-book"></i> ${schedule.subject ? schedule.subject : 'N/A'}</div>
                    <div class="col-md-2"><i class="bi bi-door-open"></i> ${schedule.room_name ? schedule.room_name : 'N/A'}</div>
                </div>`;
        });

        scheduleHTML += `</div></div>`;
    }
}

scheduleHTML += `</div>`; // Close accordion
scheduleContainer.innerHTML = scheduleHTML;

                } else {
                    console.error(data.error || "Employee details not found.");
                    alert(data.error || "Employee details not found.");
                }
            } catch (e) {
                console.error("Error parsing JSON:", e, xhr.responseText);
                alert("An error occurred while fetching employee details.");
            }
        }
    };

    xhr.send("employee_id=" + encodeURIComponent(employeeId));
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

             // Update modal with real-time filtered schedules
          updateRealTimeSchedule(data.filtered_real_time_schedules); 

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




// Function to update real-time schedule data
function updateRealTimeSchedule(filteredSchedules) {
  const realTimeSection = document.getElementById("realTimeScheduleSection"); // Add this element in your HTML

  if (!realTimeSection) {
    console.error("Real-Time Schedule section not found.");
    return;
  }

  if (filteredSchedules.length > 0) {
    realTimeSection.innerHTML = `
      <h6 class="mt-3"><strong>Real-Time Schedule:</strong></h6>
      ${filteredSchedules.map(schedule => `
      
<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title mb-1">
              <i class="bi bi-person-circle"></i> <strong>Teacher Name:</strong> ${schedule.employee_name}
            </h5>
            <p class="card-text mb-1">
              <i class="bi bi-clock"></i> <strong>Time In:</strong> ${convertTo12Hour(schedule.time_in)} | <strong>Time Out:</strong> ${convertTo12Hour(schedule.time_out)}
            </p>
            ${schedule.position === 'teaching' ? `
            <p class="card-text mb-1">
              <i class="bi bi-book"></i> <strong>Subject:</strong> ${schedule.subject}
            </p>
            ` : ''}
          </div>
        </div>
  </div>
  <div class="col-md-3"></div>
</div>
      `).join("")}
    `;
  } else {
    realTimeSection.innerHTML = "<p>No real-time schedules available for this room.</p>";
  }
}

 
 


function updateScheduleModal(schedules) {
  const modalBody = document.getElementById("scheduleModalBody");

if (!modalBody) {
  console.error("Schedule modal body not found.");
  return;
}

if (schedules.length > 0) {
  const daysOfWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  const dayMap = {
    Mon: "Monday",
    Tue: "Tuesday",
    Wed: "Wednesday",
    Thu: "Thursday",
    Fri: "Friday",
    Sat: "Saturday"
  };

  // Group schedules by day
  const schedulesByDay = daysOfWeek.reduce((acc, day) => {
    acc[day] = schedules.filter(schedule => schedule.day === day);
    return acc;
  }, {});

  // Create the buttons for filtering schedules
  const buttonHTML = `
    <div class="d-flex justify-content-center mb-3">
      <button class="btn btn-outline-success me-2" onclick="filterSchedules('all')">All</button>
      ${daysOfWeek
        .map(
          day => `
        <button class="btn btn-outline-success me-2" onclick="filterSchedules('${day}')">${dayMap[day]}</button>
      `
        )
        .join("")}
    </div>
  `;

  // Create the schedule cards
  const scheduleCardsHTML = schedules
    .map(schedule => `
      <div class="card mb-3 schedule-card" data-day="${schedule.day}">
        <div class="card-body">
          <h5 class="card-title mb-1">
            <i class="bi bi-person-circle"></i> <strong>Name:</strong> ${schedule.employee_name}
          </h5>
          <p class="card-text mb-1">
            <i class="bi bi-clock"></i> <strong>Time In:</strong> ${convertTo12Hour(schedule.time_in)} | <strong>Time Out:</strong> ${convertTo12Hour(schedule.time_out)}
          </p>
         ${schedule.position === 'teaching' ? `
            <p class="card-text mb-1">
              <i class="bi bi-book"></i> <strong>Subject:</strong> ${schedule.subject}
            </p>
            ` : ''}
        </div>
      </div>
    `)
    .join("");


  // Combine the buttons and schedule cards
  modalBody.innerHTML = buttonHTML + scheduleCardsHTML;
} else {
  modalBody.innerHTML = "<p>No schedules available for this room.</p>";
}
}

// Filter schedules by day
function filterSchedules(day) {
const scheduleCards = document.querySelectorAll(".schedule-card");

scheduleCards.forEach(card => {
  if (day === "all" || card.getAttribute("data-day") === day) {
    card.style.display = "block";
  } else {
    // card.style.display = "none";
    card.style.display = "none";
  }
}); 
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
          <div id="realTimeScheduleSection" class="text-center"></div>
      <button id="viewSchedulesButton" class="btn btn-primary btn-sm">View Schedules</button>
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
      //       const firstImageLink = roomImagesElement.querySelector('a');
      // if (firstImageLink) {
      //   firstImageLink.click(); // Simulate a click on the first image
      // } else {
      //   console.error("No images found in the gallery.");
      // }
    }

    // Show the About Section
    aboutSection.style.display = "block";

    // Optionally, scroll to the section for better user experience
    aboutSection.scrollIntoView({ behavior: "smooth" });
  }


</script>