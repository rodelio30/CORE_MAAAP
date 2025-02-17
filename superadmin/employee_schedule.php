<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
function convertTo24Hour($time) {
    return date("H:i", strtotime($time));
}

if (isset($_POST['submit_employee'])) {
  // Retrieve form data
  $name = trim($_POST['name']);
  $department = trim($_POST['department']);
  $position = trim($_POST['position']); // 'teaching' or 'non-teaching'

  // Check if the employee already exists in the employee_schedule table
  $checkStmt = $conn->prepare("SELECT employee_id FROM employee_schedule WHERE name = ?");
  if ($checkStmt) {
      $checkStmt->bind_param("s", $name);
      $checkStmt->execute();
      $checkStmt->store_result();

      if ($checkStmt->num_rows > 0) {
          // Employee already exists, fetch the employee ID
          $checkStmt->bind_result($employee_id);
          $checkStmt->fetch();
          $checkStmt->close();

          // Append new schedules to the existing employee
          if (!empty($_POST['schedules'])) {
              $schedules = json_decode($_POST['schedules'], true);
              if (is_array($schedules)) {
                  foreach ($schedules as $schedule) {
                      // Prepare schedule data
                      // $room = $conn->real_escape_string($schedule['room']);
                      $room_id = intval($schedule['room']); 

                      $time_in = convertTo24Hour($schedule['time_in']);
                      $time_out = convertTo24Hour(isset($schedule['time_out']) ? $schedule['time_out'] : null);

                      $days = $schedule['day']; // Array of days
                      $subject = isset($schedule['subject']) ? $conn->real_escape_string($schedule['subject']) : null;
                      $position_schedule = $schedule['type']; // 'teaching' or 'non-teaching'

                      foreach ($days as $day) {
                          // Prepare and bind
                          $schedule_stmt = $conn->prepare("INSERT INTO real_time_schedule (room_id, time_in, time_out, day, subject, employee_id, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
                          if ($schedule_stmt) {
                              $schedule_stmt->bind_param("issssis", $room_id, $time_in, $time_out, $day, $subject, $employee_id, $position_schedule);
                              if (!$schedule_stmt->execute()) {
                                  // Handle execution error
                                  echo "<script>alert('Error inserting schedule for $name: " . $schedule_stmt->error . "'); window.location.href='employee_schedule.php';</script>";
                                  exit();
                              }
                              $schedule_stmt->close();
                          } else {
                              // Handle preparation error
                              echo "<script>alert('Error preparing statement for schedule insertion: " . $conn->error . "'); window.location.href='employee_schedule.php';</script>";
                              exit();
                          }
                      }
                  }
              } else {
                  echo "<script>alert('Invalid schedule data.'); window.location.href='employee_schedule.php';</script>";
                  exit();
              }
          }

          // Success message for appending schedules
          echo "<script>alert('Employee exists. New schedules appended successfully!'); window.location.href='employee_schedule.php';</script>";
          exit();

      } else {
          // Employee doesn't exist, proceed with insertion
          $checkStmt->close();

          // Insert into employee_schedule table
          $stmt = $conn->prepare("INSERT INTO employee_schedule (name, department, position) VALUES (?, ?, ?)");
          if ($stmt) {
              $stmt->bind_param("sss", $name, $department, $position);
              if ($stmt->execute()) {
                  $employee_id = $stmt->insert_id; // Get the inserted employee ID
                  $stmt->close();

                  // Insert new schedules
                  if (!empty($_POST['schedules'])) {
                      $schedules = json_decode($_POST['schedules'], true);

                      if (is_array($schedules)) {
                          foreach ($schedules as $schedule) {
                              // Prepare schedule data
                              // $room = $conn->real_escape_string($schedule['room']);
                              $room_id = intval($schedule['room']); 

                              $time_in = convertTo24Hour($schedule['time_in']);
                              $time_out = convertTo24Hour(isset($schedule['time_out']) ? $schedule['time_out'] : null);
                              $days = $schedule['day']; // Array of days
                              $subject = isset($schedule['subject']) ? $conn->real_escape_string($schedule['subject']) : null;
                              $position_schedule = $schedule['type']; // 'teaching' or 'non-teaching'

                              foreach ($days as $day) {
                                  // Prepare and bind
                                  $schedule_stmt = $conn->prepare("INSERT INTO real_time_schedule (room_id, time_in, time_out, day, subject, employee_id, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                  if ($schedule_stmt) {
                                      $schedule_stmt->bind_param("issssis", $room_id, $time_in, $time_out, $day, $subject, $employee_id, $position_schedule);
                                      if (!$schedule_stmt->execute()) {
                                          // Handle execution error
                                          echo "<script>alert('Error inserting schedule for $name: " . $schedule_stmt->error . "'); window.location.href='employee_schedule.php';</script>";
                                          exit();
                                      }
                                      $schedule_stmt->close();
                                  } else {
                                      // Handle preparation error
                                      echo "<script>alert('Error preparing statement for schedule insertion: " . $conn->error . "'); window.location.href='employee_schedule.php';</script>";
                                      exit();
                                  }
                              }
                          }
                      } else {
                          echo "<script>alert('Invalid schedule data.'); window.location.href='employee_schedule.php';</script>";
                          exit();
                      }
                  }

                  // Success message for new employee and schedules
                  echo "<script>alert('New Employee and Schedules added successfully!'); window.location.href='employee_schedule.php';</script>";
                  exit();
              } else {
                  // Handle execution error
                  echo "<script>alert('Error inserting employee: " . $stmt->error . "'); window.location.href='employee_schedule.php';</script>";
                  exit();
              }
          } else {
              // Handle preparation error
              echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='employee_schedule.php';</script>";
              exit();
          }
      }
  } else {
      // Handle preparation error for check statement
      echo "<script>alert('Error preparing check statement: " . $conn->error . "'); window.location.href='employee_schedule.php';</script>";
      exit();
  }
}

$sql_list = "
  SELECT 
      es.name, 
      es.department, 
      es.position, 
      rs.room_id, 
      rs.subject
  FROM 
      employee_schedule es
  LEFT JOIN 
      real_time_schedule rs 
  ON 
      es.employee_id = rs.employee_id
";

// Execute query
$result_list = $conn->query($sql_list);

$rooms = [];
$roomQuery = "SELECT room_id, room_name FROM room ORDER BY room_name ASC";
$result = $conn->query($roomQuery);

if ($result) {
  while ($row = $result->fetch_assoc()) {
      $rooms[] = $row;
  }
} else {
  echo "<script>alert('Error fetching rooms: " . $conn->error . "');</script>";
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
    th,td {
      border: 5px solid #2e5a31 !important;
    }
    .add-sched {
      text-align: center;
      background-color: #2e5a31; 
      padding-bottom: 0.5rem;
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

/* Optional: Customize modal header to make it stand out more */
/* For overall padding and spacing */
  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "employee";
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
        <div class="col-md-3">
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="color: white;">
                Add Employee
            </button>
        </div>
        <div class="col-md-5 text-left">
            <h1>
              <b>
              TEACHER SCHEDULE
              </b>
            </h1>
        </div>
        <div class="col-md-4">
            <form>
                <div class="search-input">
                    <i class="fas fa-search" style="font-size: 1.4rem;"></i> 
                    <input class="form-control" type="text" id="searchInput" placeholder="Search" aria-label="Search" onkeyup="filterTable()" style="width: 99%;">
                </div>
            </form>
        </div>
    </div>

    <!-- Table List -->
    <table class="table table-bordered border-success mt-4 text-center" id="employeeTable">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Room Schedule</th>
                <th scope="col">Department</th>
                <th scope="col">Position</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
$query = "
SELECT e.employee_id, e.name, e.department, e.position, GROUP_CONCAT(DISTINCT CONCAT('Room: ', rm.room_name, ' (', r.day, ')', IF(r.subject IS NOT NULL AND r.subject <> '', CONCAT(' Subject: ', r.subject), ''), IF(r.position = 'non-teaching', CONCAT(' Time In: ', r.time_in, ' Time Out: ', r.time_out), CONCAT(' Time In: ', r.time_in))) SEPARATOR ', ') AS schedules FROM employee_schedule e LEFT JOIN real_time_schedule r ON e.employee_id = r.employee_id LEFT JOIN room rm ON r.room_id = rm.room_id GROUP BY e.employee_id ORDER BY e.employee_id ASC;
    ";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $modal_id = 'modal_' . $row['employee_id'];

    echo "<tr>";
        echo "<th><h6 class='mt-2'>{$row['name']}</h6></th>";

        if($row['position'] == 'teaching') {
            // echo "<th class='pt-3'>{$row['schedules']}</th>";
            $limited_text = strlen($row['schedules']) > 50 ? substr($row['schedules'], 0, 50) . '...' : $row['schedules'];
            echo "<th class='pt-3'>$limited_text</th>";
        } else {
            echo "<th class='pt-3'>N/A</th>";
        }

        echo "<th class='pt-3'>{$row['department']}</th>";
        echo "<th class='pt-3'>" . ucfirst($row['position']) . "</th>";
        echo "<th class='pt-2'>
                <button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#{$modal_id}'>
                    View 
                </button>
                <a href='include/delete_employee.php?emp_id={$row['employee_id']}' class='btn btn-danger'>Delete</a>
              </th>";
        echo "</tr>";
        // Modal structure for View/Edit
        echo '<div class="modal fade" id="'.$modal_id.'" tabindex="-1" aria-labelledby="modalLabel'.$row['employee_id'].'" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel'.$row['employee_id'].'"><b>View / Edit SCHEDULE</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="include/update_schedule.php"> <!-- Assuming you create update_schedule.php for updating -->
                            <input type="hidden" name="employee_id" value="'.$row['employee_id'].'">
                            <input type="hidden" name="position" value="'.$row['position'].'">
                            <div class="mb-3">
                                <label for="name" class="form-label"><b>Name</b></label>
                                <input type="text" class="form-control" id="name" name="name" value="'.$row['name'].'" >
                            </div>
                            <div class="mb-3">
                                <label for="department" class="form-label"><b>Department</b></label>
                                <input type="text" class="form-control" id="department" name="department" value="'.$row['department'].'">
                            </div>';

                        $schedule_query = "
                           SELECT r.real_time_schedule_id, room.room_id, room.room_name, r.time_in, r.time_out, r.day, r.subject, r.position 
                            FROM real_time_schedule r 
                            LEFT JOIN room ON r.room_id = room.room_id 
                            WHERE r.employee_id = '{$row['employee_id']}' 
                            ORDER BY room.room_name ASC; 
                            ";
                        $schedule_result = mysqli_query($conn, $schedule_query);
                        
                        while ($schedule = mysqli_fetch_assoc($schedule_result)) {
                            $sched_subject = !empty($schedule['subject']) ? $schedule['subject'] : "N/A";
                        
                        if($schedule['position'] == 'teaching') {
                            echo '
                                <div class="modal-view row mb-3">
                                    <input type="hidden" name="real_time_schedule_id[]" value="' . $schedule["real_time_schedule_id"] . '">
                                    <input type="hidden" name="room_id[]" value="' . $schedule["room_id"] . '"> 
                                    <div class="col-md-2">
                                        <label for="room" class="form-label"><b>Room</b></label>
                                        <input type="text" class="form-control" name="room[]" value="'.$schedule['room_name'].'">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="time_in" class="form-label"><b>Time In</b></label>
                                        <input type="time" class="form-control" name="time_in[]" value="'.$schedule['time_in'].'">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="time_out" class="form-label"><b>Time Out</b></label>
                                        <input type="time" class="form-control" name="time_out[]" value="'.$schedule['time_out'].'">
                                    </div>
                                    ';
                        
                                    if($schedule['position'] != 'teaching') {
                                        echo '
                                        <div class="col-md-3">
                                            <label for="time_out" class="form-label"><b>Time Out</b></label>
                                            <input type="time" class="form-control" name="time_out[]" value="'.$schedule['time_out'].'">
                                        </div>';
                                    } 
                                    echo '
                                    <div class="col-md-3">
                                        <label for="day" class="form-label"><b>Day</b></label>
                                        <select class="form-control" name="day[]">
                                            <option value="Mon" '.($schedule['day'] == 'Mon' ? 'selected' : '').'>Monday</option>
                                            <option value="Tue" '.($schedule['day'] == 'Tue' ? 'selected' : '').'>Tuesday</option>
                                            <option value="Wed" '.($schedule['day'] == 'Wed' ? 'selected' : '').'>Wednesday</option>
                                            <option value="Thu" '.($schedule['day'] == 'Thu' ? 'selected' : '').'>Thursday</option>
                                            <option value="Fri" '.($schedule['day'] == 'Fri' ? 'selected' : '').'>Friday</option>
                                            <option value="Sat" '.($schedule['day'] == 'Sat' ? 'selected' : '').'>Saturday</option>
                                        </select>
                                    </div>
                                    ';
                                    if($schedule['position'] == 'teaching') {
                        
                                    echo '
                                    <div class="col-md-3">
                                        <label for="subject" class="form-label"><b>Subject</b></label>
                                        <input type="text" class="form-control" name="subject[]" value="'.$sched_subject.'">
                                    </div>';
                                    }
                        
                        
                                    echo '
                                </div>';
                                    } 
                        }

        echo '</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
    </div>
    </div>';
}
?> 
        </tbody>
    </table>

  <!-- Modal for adding new Employee Schedule -->
<!-- Employee Schedule Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: #2e5a31; color: white;">
                <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Add Employee Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Schedule Form -->
            <form method="post" action="employee_schedule.php">
                <div class="modal-body">
                    <!-- Employee Name -->
                    <div class="input-group mt-3">
                        <span class="input-group-text">Name</span>
                        <input type="text" name="name" aria-label="Name" class="form-control" id="employeeName" required>
                    </div>

                    <!-- Department -->
                    <div class="input-group mt-3">
                        <span class="input-group-text">Department</span>
                        <input type="text" name="department" aria-label="Department" class="form-control" id="department" required>
                    </div>

                    <!-- Position Selection -->
                    <div class="text-center mt-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="position" id="teachingRadio" value="teaching" required>
                            <label class="form-check-label" for="teachingRadio">Teaching</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="position" id="nonTeachingRadio" value="non-teaching" required>
                            <label class="form-check-label" for="nonTeachingRadio">Non-Teaching</label>
                        </div>
                    </div>

                    <!-- Teaching Schedule Form -->
                    <div id="teachingForm" class="schedule-form d-none">
                        <h5 class="mt-4">Teaching Schedule</h5>
                        <div class="row mt-3">
                            <!-- Room Selection -->
                            <div class="col-md-2">
                                <label for="teachingRoom" class="form-label">Room</label>
                                <select class="form-select" name="room_teaching[]" id="teachingRoom">
                                    <option value="" selected disabled>Select Room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?php echo $room['room_id'] . '|' . $room['room_name']; ?>">
                                            <?php echo htmlspecialchars($room['room_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Time In -->
                            <div class="col-md-2">
                                <label for="teachingTimeIn" class="form-label">Time In</label>
                                <input type="time" class="form-control" name="time_in_teaching[]" id="teachingTimeIn">
                            </div>
                            <!-- Time Out -->
                            <div class="col-md-2">
                                <label for="nonTeachingTimeOut" class="form-label">Time Out</label>
                                <input type="time" class="form-control" name="time_out_teaching[]" id="teachingTimeOut">
                            </div>

                            <!-- Days Selection -->
                            <div class="col-md-3">
                                <label class="form-label">Days</label>
                                <div class="d-flex flex-wrap">
                                    <!-- Day Checkboxes -->
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Mon" id="mon_teaching">
                                        <label class="form-check-label" for="mon_teaching">Mon</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Tue" id="tue_teaching">
                                        <label class="form-check-label" for="tue_teaching">Tue</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Wed" id="wed_teaching">
                                        <label class="form-check-label" for="wed_teaching">Wed</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Thu" id="thu_teaching">
                                        <label class="form-check-label" for="thu_teaching">Thu</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Fri" id="fri_teaching">
                                        <label class="form-check-label" for="fri_teaching">Fri</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_teaching[]" value="Sat" id="sat_teaching">
                                        <label class="form-check-label" for="sat_teaching">Sat</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Selection -->
                            <div class="col-md-3">
                                <label for="subjectSelect" class="form-label">Subject</label>
                                <input type="text" name="subject_teaching[]" aria-label="Department" class="form-control" id="subjectSelect">
                                <!-- <select class="form-select" name="subject_teaching[]" id="subjectSelect">
                                    <option value="" selected disabled>Select Subject</option>
                                    <option value="CS 100">CS 100</option>
                                    <option value="CS 101">CS 101</option>
                                    <option value="CS 102">CS 102</option>
                                </select> -->
                            </div>
                        </div>
                    </div>

                    <!-- Non-Teaching Schedule Form -->
                    <div id="nonTeachingForm" class="schedule-form d-none">
                        <h5 class="mt-4">Non-Teaching Schedule</h5>
                        <div class="row mt-3">
                            <!-- Room Selection -->
                            <div class="col-md-3">
                                <label for="nonTeachingRoom" class="form-label">Room</label>
                                <select class="form-select" name="room_non_teaching[]" id="nonTeachingRoom">
                                    <option value="" selected disabled>Select Room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?php echo $room['room_id'] . '|' . $room['room_name']; ?>">
                                            <?php echo htmlspecialchars($room['room_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Time In -->
                            <div class="col-md-3">
                                <label for="nonTeachingTimeIn" class="form-label">Time In</label>
                                <input type="time" class="form-control" name="time_in_non_teaching[]" id="nonTeachingTimeIn">
                            </div>

                            <!-- Time Out -->
                            <div class="col-md-3">
                                <label for="nonTeachingTimeOut" class="form-label">Time Out</label>
                                <input type="time" class="form-control" name="time_out_non_teaching[]" id="nonTeachingTimeOut">
                            </div>

                            <!-- Days Selection -->
                            <div class="col-md-3">
                                <label class="form-label">Days</label>
                                <div class="d-flex flex-wrap">
                                    <!-- Day Checkboxes -->
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Mon" id="mon_nonTeaching">
                                        <label class="form-check-label" for="mon_nonTeaching">Mon</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Tue" id="tue_nonTeaching">
                                        <label class="form-check-label" for="tue_nonTeaching">Tue</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Wed" id="wed_nonTeaching">
                                        <label class="form-check-label" for="wed_nonTeaching">Wed</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Thu" id="thu_nonTeaching">
                                        <label class="form-check-label" for="thu_nonTeaching">Thu</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Fri" id="fri_nonTeaching">
                                        <label class="form-check-label" for="fri_nonTeaching">Fri</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="day_non_teaching[]" value="Sat" id="sat_nonTeaching">
                                        <label class="form-check-label" for="sat_nonTeaching">Sat</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Schedule Button -->
                    <div class="d-grid mt-3">
                        <button class="btn btn-success" type="button" id="addSchedule">Add Schedule</button>
                    </div>

                    <!-- Schedule Table -->
                    <table class="table table-bordered mt-4" id="scheduleTable">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Room</th>
                                <th scope="col">Time In</th>
                                <th scope="col">Time Out</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Days</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamically Added Schedules will appear here -->
                        </tbody>
                    </table>

                    <!-- Hidden input to hold schedule data -->
                    <input type="hidden" name="schedules" id="schedules">

                    <!-- Modal Footer -->
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit_employee" class="btn btn-success">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

  <!-- Modal for adding new Employee Schedule -->

    

    <!-- Modal for Non-Teachers -->
<div class="modal fade" id="exampleModalNon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">SCHEDULE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4><b>Name:</b> ()</h4>
        <h4><b>Department:</b> OAD</h4>
        <table class="table table-bordered mt-2">
          <thead>
            <tr>
              <th scope="col">Room</th>
              <th scope="col">Time in</th>
              <th scope="col">Time out</th>
              <th scope="col">Day</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">508</th>
              <th>8:00</th>
              <th>12:00</th>
              <th>
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                <label class="form-check-label" for="inlineCheckbox1">Mon</label>
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                <label class="form-check-label" for="inlineCheckbox1">Tue</label>
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                <label class="form-check-label" for="inlineCheckbox1">Wed</label>
              </th>
            </tr>
            <tr>
              <th scope="row">510</th>
              <th>1:00</th>
              <th>5:00</th>
              <th>
              </th>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Save changes</button>
      </div>
    </div>
  </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const teachingRadio = document.getElementById('teachingRadio');
        const nonTeachingRadio = document.getElementById('nonTeachingRadio');
        const teachingForm = document.getElementById('teachingForm');
        const nonTeachingForm = document.getElementById('nonTeachingForm');
        const addScheduleButton = document.getElementById('addSchedule');
        const scheduleTableBody = document.querySelector('#scheduleTable tbody');
        const schedulesInput = document.getElementById('schedules');

        // Initialize schedule count (optional, useful for unique IDs if needed)
        let scheduleCount = 0;

        /**
         * Function to toggle the visibility of schedule forms based on selected position
         */
        function toggleScheduleForm() {
            if (teachingRadio.checked) {
                teachingForm.classList.remove('d-none');
                nonTeachingForm.classList.add('d-none');
            } else if (nonTeachingRadio.checked) {
                teachingForm.classList.add('d-none');
                nonTeachingForm.classList.remove('d-none');
            }
        }

        // Initial toggle on page load
        toggleScheduleForm();

        // Event listeners for position radio buttons
        teachingRadio.addEventListener('change', toggleScheduleForm);
        nonTeachingRadio.addEventListener('change', toggleScheduleForm);

        /**
         * Function to capitalize the first letter of a string
         * @param {string} string
         * @returns {string}
         */
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        /**
         * Function to update the hidden input with the current schedules in JSON format
         */
        function updateSchedules() {
            const schedules = [];
            scheduleTableBody.querySelectorAll('tr').forEach(function(row) {
                const cells = row.querySelectorAll('td');
                const type = cells[0].innerText.toLowerCase();
                const roomName = cells[1].innerText;
                const timeIn = cells[2].innerText;
                const timeOut = cells[3].innerText !== 'N/A' ? cells[3].innerText : null;
                const subject = cells[4].innerText !== 'N/A' ? cells[4].innerText : null;
                const days = cells[5].innerText.split(', ');

                // Retrieve room_id from a data attribute or hidden input in the row
                const roomId = row.getAttribute('data-room-id') || null;

                // Construct schedule object based on type
                if (type === 'teaching') {
                    schedules.push({ type, room: roomId, time_in: timeIn, time_out: timeOut, subject, day: days });
                } else if (type === 'non-teaching') {
                    schedules.push({ type, room: roomId, time_in: timeIn, time_out: timeOut, subject: null, day: days });
                }
            });
            schedulesInput.value = JSON.stringify(schedules);
        }

        /**
         * Function to clear the schedule form fields after adding a schedule
         */
        function clearScheduleFields() {
            if (teachingRadio.checked) {
                document.getElementById('teachingRoom').value = '';
                document.getElementById('teachingTimeIn').value = '';
                document.getElementById('teachingTimeOut').value = '';
                document.getElementById('subjectSelect').value = '';
                // Clear teaching day checkboxes
                document.querySelectorAll('input[name="day_teaching[]"]').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            } else if (nonTeachingRadio.checked) {
                document.getElementById('nonTeachingRoom').value = '';
                document.getElementById('nonTeachingTimeIn').value = '';
                document.getElementById('nonTeachingTimeOut').value = '';
                // Clear non-teaching day checkboxes
                document.querySelectorAll('input[name="day_non_teaching[]"]').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        }

        /**
         * Event Handler for "Add Schedule" Button
         */
        addScheduleButton.addEventListener('click', function() {
            let schedule = {};
            let type = '';
            let room = '';
            let roomId = '';
            let timeIn = '';
            let timeOut = '';
            let subject = '';
            let days = [];

            // Determine the type of position selected
            if (teachingRadio.checked) {
                type = 'teaching';
                // room = document.getElementById('teachingRoom').value;
                 // Extract room_id and room_name from the selected option
                const selectedRoom = document.getElementById('teachingRoom').value;
                if (selectedRoom) {
                    [roomId, roomName] = selectedRoom.split('|');
                }

                timeIn = document.getElementById('teachingTimeIn').value;
                timeOut = document.getElementById('teachingTimeOut').value;
                subject = document.getElementById('subjectSelect').value;

                // Collect selected days for teaching
                const dayCheckboxes = document.querySelectorAll('input[name="day_teaching[]"]:checked');
                dayCheckboxes.forEach(function(checkbox) {
                    days.push(checkbox.value);
                });

                // Validation for Teaching Schedule
                if (!roomId || !timeIn || !timeOut || !subject || days.length === 0) {
                    alert('Please fill in all Teaching schedule fields.');
                    return;
                }

                // schedule = { type, room, timeIn, timeOut, subject, days };
                schedule = { type, roomId, roomName, timeIn, timeOut, subject, days };

            } else if (nonTeachingRadio.checked) {
                type = 'non-teaching';
                // room = document.getElementById('nonTeachingRoom').value;
                 // Extract room_id and room_name from the selected option
                const selectedRoom = document.getElementById('nonTeachingRoom').value;
                if (selectedRoom) {
                    [roomId, roomName] = selectedRoom.split('|');
                }
                timeIn = document.getElementById('nonTeachingTimeIn').value;
                timeOut = document.getElementById('nonTeachingTimeOut').value;

                // Collect selected days for Non-Teaching
                const dayCheckboxes = document.querySelectorAll('input[name="day_non_teaching[]"]:checked');
                dayCheckboxes.forEach(function(checkbox) {
                    days.push(checkbox.value);
                });

                // Validation for Non-Teaching Schedule
                if (!roomId || !timeIn || !timeOut || days.length === 0) {
                    alert('Please fill in all Non-Teaching schedule fields.');
                    return;
                }

                // schedule = { type, room, timeIn, timeOut, days };
                schedule = { type, roomId, roomName, timeIn, timeOut, days };

            } else {
                alert('Please select a position (Teaching or Non-Teaching).');
                return;
            }

            // Create a new table row for the schedule
            const row = document.createElement('tr');
            // Add the room_id as a data attribute to the row
            row.setAttribute('data-room-id', schedule.roomId);

            // Populate table cells based on schedule type
            const typeCell = `<td>${capitalizeFirstLetter(schedule.type)}</td>`;
            // const roomCell = `<td>${schedule.room}</td>`;
            const roomCell = `<td>${schedule.roomName}</td>`; // Display room_name

            const timeInCell = `<td>${formatTimeTo12Hour(schedule.timeIn)}</td>`;
            const timeOutCell = `<td>${formatTimeTo12Hour(schedule.timeOut)}</td>`;
            const subjectCell = schedule.type === 'teaching' ? `<td>${schedule.subject}</td>` : `<td>N/A</td>`;
            const daysCell = `<td>${schedule.days.join(', ')}</td>`;
            const actionCell = `<td><button type="button" class="btn btn-danger btn-sm deleteSchedule">Delete</button></td>`;

            // Combine all cells into the row
            row.innerHTML = typeCell + roomCell + timeInCell + timeOutCell + subjectCell + daysCell + actionCell;

            // Append the row to the schedule table
            scheduleTableBody.appendChild(row);

            // Update the hidden input with the current schedules
            updateSchedules();

            // Add event listener to the delete button
            row.querySelector('.deleteSchedule').addEventListener('click', function() {
                row.remove();
                updateSchedules();
            });

            // Increment schedule count for unique IDs (optional)
            scheduleCount++;

            // Clear the form fields after adding the schedule
            clearScheduleFields();
        });
    });
    function formatTimeTo12Hour(time) {
    let [hour, minute] = time.split(':');
    hour = parseInt(hour);
    let period = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12; // Convert 0 to 12
    return `${hour}:${minute} ${period}`;
}
</script>

<!-- JavaScript for Search Function -->
<script>
function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("employeeTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("th"); // Get all <th> elements in the row

        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break; // If a match is found, display the row and break
                }
            }
        }
    }
}
</script>