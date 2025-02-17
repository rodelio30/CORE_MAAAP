<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

// $sql = "
// SELECT room.room_id, room.room_name AS room_name, GROUP_CONCAT(employee_schedule.name SEPARATOR ', ') AS employees FROM real_time_schedule 
// JOIN employee_schedule ON real_time_schedule.employee_id = employee_schedule.employee_id 
// JOIN room ON real_time_schedule.room_id = room.room_id 
// AND CURRENT_TIME() BETWEEN real_time_schedule.time_in AND real_time_schedule.time_out
// AND real_time_schedule.day = CASE
//       WHEN DAYNAME(CURDATE()) = 'Monday' THEN 'Mon'
//       WHEN DAYNAME(CURDATE()) = 'Tuesday' THEN 'Tue'
//       WHEN DAYNAME(CURDATE()) = 'Wednesday' THEN 'Wed'
//       WHEN DAYNAME(CURDATE()) = 'Thursday' THEN 'Thu'
//       WHEN DAYNAME(CURDATE()) = 'Friday' THEN 'Fri'
//       WHEN DAYNAME(CURDATE()) = 'Saturday' THEN 'Sat'
//   END
// GROUP BY room.room_name;
//         ";

$sql = "
SELECT room.room_id, room.room_name AS room_name, COALESCE(GROUP_CONCAT(employee_schedule.name SEPARATOR ', '), 'N/A') AS employees FROM room LEFT JOIN real_time_schedule ON room.room_id = real_time_schedule.room_id AND CURRENT_TIME() BETWEEN real_time_schedule.time_in AND real_time_schedule.time_out AND real_time_schedule.day = CASE WHEN DAYNAME(CURDATE()) = 'Monday' THEN 'Mon' WHEN DAYNAME(CURDATE()) = 'Tuesday' THEN 'Tue' WHEN DAYNAME(CURDATE()) = 'Wednesday' THEN 'Wed' WHEN DAYNAME(CURDATE()) = 'Thursday' THEN 'Thu' WHEN DAYNAME(CURDATE()) = 'Friday' THEN 'Fri' WHEN DAYNAME(CURDATE()) = 'Saturday' THEN 'Sat' END LEFT JOIN employee_schedule ON real_time_schedule.employee_id = employee_schedule.employee_id GROUP BY room.room_id, room.room_name;        ";
// $sql = "
// SELECT room.room_id, room.room_name AS room_name, GROUP_CONCAT(employee_schedule.name SEPARATOR ', ') AS employees FROM real_time_schedule JOIN employee_schedule ON real_time_schedule.employee_id = employee_schedule.employee_id JOIN room ON real_time_schedule.room_id = room.room_id GROUP BY room.room_name;
//         ";


$result = $conn->query($sql);
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
    .add-border {
      border: 3px solid;
    }
.modal-view .row {
    display: flex;
    justify-content: space-between;
}

.modal-view .row:last-child {
    border-bottom: none;  /* Remove border for the last row */
}

.modal-view .modal-col {
    text-align: center;   /* Center text in columns */
    padding: 5px 10px;
    font-weight: 500;     /* Bold column content */
    border: 5px solid #2e5a31;
}

.modal-view .modal-col:first-child {
    text-align: left;     /* Align Room column to the left for better readability */
}

.modal-view .row:nth-child(odd) {
    background-color: #f9f9f9;  /* Alternate row background color */
    border-bottom: none;  /* Remove border for the last row */
}

.modal-view .row:nth-child(even) {
    background-color: #ffffff;
}

.card:hover {
    transform: none !important;
    transition: none !important;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
  }
  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "room";
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
      <div class="container" data-aos="fade-up">
     
       <!-- Button trigger modal -->
        <div class="row pt-3">
            <div class="col-md-4">
            </div>
            <div class="col-md-4 text-center">
              <h1>
                <b>
                  ROOM SCHEDULE
                </b>
              </h1>
            </div>
            <div class="col-md-4">
              <!-- <form>
                  <div class="search-input">
                      <i class="fas fa-search" style="font-size: 1.4rem;"></i> 
                      <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                  </div>
              </form> -->
              <form onsubmit="return false;">
                  <div class="search-input">
                      <!-- <i class="bi bi-search" style="font-size: 1.4rem;"></i> -->
                      <input class="form-control" type="text" id="searchInput" placeholder="Search" aria-label="Search" onkeyup="filterTable()" style="width: 99%;">
                  </div>
              </form>
            </div>
          <div>

     <table class="table table-bordered border-success mt-4 text-center"  id="accountTable">
  <thead>
    <tr>
      <th scope="col">Room Name</th>
      <th scope="col">Real-time Schedule</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php
  if ($result->num_rows > 0) {
    // Output data for each row
    while($row = $result->fetch_assoc()) {
      $room_id = 'room_' . $row['room_name']; // Unique room ID

      echo "<tr>";
      echo "<th><h6 class='mt-2'>" . $row['room_name'] . "</h6></th>";
      echo "<th><h6 class='mt-2'>" . $row['employees'] . "</h6></th>"; // Show all employees for the room
      echo "<th class='pt-2'>
              <div class='d-grid mt-1'>
                <button type='button' class='btn btn-success' data-room='" . $row['room_name'] . "' data-bs-toggle='modal' data-bs-target='#exampleModal".$row['room_name']."'>
                  View Schedule
                </button>
              </div>
            </th>";
      echo '
<div class="modal fade" id="exampleModal'.$row['room_name'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">SCHEDULE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" style="padding: 0;">
        <h4 class="p-2" style="background-color: #2e5a31; color: white;">Room '.$row['room_name'].'</h4>
         <p>
            <a class="btn btn-outline-success add-border" data-bs-toggle="collapse" href="#collapseMonday'.$room_id.'" role="button" aria-expanded="false" aria-controls="collapseMonday'.$room_id.'">Monday</a>
            <button class="btn btn-outline-success add-border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuesday'.$room_id.'" aria-expanded="false" aria-controls="collapseTuesday'.$room_id.'">Tuesday</button>
            <button class="btn btn-outline-success add-border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWednesday'.$room_id.'" aria-expanded="false" aria-controls="collapseWednesday'.$room_id.'">Wednesday</button>
            <button class="btn btn-outline-success add-border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThursday'.$room_id.'" aria-expanded="false" aria-controls="collapseThursday'.$room_id.'">Thursday</button>
            <button class="btn btn-outline-success add-border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFriday'.$room_id.'" aria-expanded="false" aria-controls="collapseFriday'.$room_id.'">Friday</button>
            <button class="btn btn-outline-success add-border" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSaturday'.$room_id.'" aria-expanded="false" aria-controls="collapseSaturday'.$room_id.'">Saturday</button>
          </p>

          <div class="accordion" id="scheduleAccordion'.$room_id.'">
          ';

// New query to fetch the weekly schedule based on room
$sql_weekly_schedule = "
 SELECT real_time_schedule.real_time_schedule_id, real_time_schedule.day, real_time_schedule.time_in, 
         real_time_schedule.time_out, employee_schedule.name AS employee_name, real_time_schedule.subject, 
         real_time_schedule.employee_id
  FROM real_time_schedule
  JOIN employee_schedule ON real_time_schedule.employee_id = employee_schedule.employee_id
  WHERE real_time_schedule.room_id  = ?
  ORDER BY FIELD(real_time_schedule.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), 
           real_time_schedule.time_in ASC;
";


$stmt = $conn->prepare($sql_weekly_schedule);
$stmt->bind_param('i', $row['room_id']); // Bind the room name
$stmt->execute();
$weekly_result = $stmt->get_result();

if ($weekly_result->num_rows > 0) {
    $weekly_schedule = [];
  
    // Mapping day abbreviations to full names
    $day_map = [
        'Mon' => 'Monday',
        'Tue' => 'Tuesday',
        'Wed' => 'Wednesday',
        'Thu' => 'Thursday',
        'Fri' => 'Friday',
        'Sat' => 'Saturday',
        'Sun' => 'Sunday'
    ];

    // Group the schedule by full day names
    while ($row = $weekly_result->fetch_assoc()) {
        // Check if the day is in the day_map to avoid undefined index error
        if (isset($day_map[$row['day']])) {
            $full_day_name = $day_map[$row['day']];
            // Store the schedule under the full day name
            $weekly_schedule[$full_day_name][] = $row;
        }
    }

    // Define the order of days for display
    $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // Generate the schedule display
    foreach ($days_of_week as $day) {
        echo '<div class="collapse" id="collapse'.$day.$room_id.'" data-bs-parent="#scheduleAccordion'.$room_id.'">
                <div class="card card-body">
                    <div class="modal-view">
                        <div class="row">
                            <div class="col-md-3 modal-col"><b>Time</b></div>
                            <div class="col-md-3 modal-col"><b>Employee</b></div>
                            <div class="col-md-3 modal-col"><b>Subject</b></div>
                            <div class="col-md-3 modal-col"><b>Actions</b></div>
                        </div>';

        // Check if there are schedules for this day
        if (!empty($weekly_schedule[$day])) {
            foreach ($weekly_schedule[$day] as $schedule) {
                $subject_update = ($schedule['subject'] == NULL) ? 'N/A' : $schedule['subject'];
                $subject_editable = ($subject_update == 'N/A') ? 'readonly' : 'required';

                echo '<form method="post" action="include/update_room_schedule.php">
                        <div class="row">
                            <div class="col-md-3 modal-col">
                                <input type="time" name="time_in" value="' . htmlspecialchars($schedule['time_in']) . '" required> 
                                - 
                                <input type="time" name="time_out" value="' . htmlspecialchars($schedule['time_out']) . '" required>
                            </div>
                            <div class="col-md-3 modal-col">
                                <input type="text" name="employee_name" value="' . htmlspecialchars($schedule['employee_name']) . '" required>
                            </div>
                            <div class="col-md-3 modal-col">
                                <input type="text" name="subject" value="' . htmlspecialchars($subject_update) . '" ' . $subject_editable . '>
                            </div>
                            <div class="col-md-3 modal-col">
                                <input type="hidden" name="employee_id" value="' . htmlspecialchars($schedule['employee_id']) . '">
                                <input type="hidden" name="rts_id" value="' . htmlspecialchars($schedule['real_time_schedule_id']) . '">
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </div>
                        </div>
                      </form>';
            }
        } else {
            echo '<p>No schedules for ' . $day . '.</p>';
        }

        echo '</div></div></div>'; // Closing the divs
    }
} else {
    echo '<p>No weekly schedule available.</p>';
}
echo '

          </div>
      </div>
    </div>
  </div>
</div>
      ';
      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='3'>No records found</td></tr>";
  }
  ?>
</tbody>
</table>
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