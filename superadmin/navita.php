<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
if (isset($_POST["submit_navita"])) {
  $question = $_POST['question'];
  $answer = $_POST['answer'];
  $status = 'answered';

  $sql = "INSERT INTO navita (question, answer, status, date_answered) VALUES ('$question', '$answer', '$status', NOW())";
  
  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('New Q and A added successfully!'); window.location.href = 'navita.php'; </script>";
      exit();
  } else {
      echo "" . $sql . "<br>" . $conn->error;
      echo "<script> alert('Error: ' . $sql . '<br>' . $conn->error); window.location.href = 'navita.php'; </script>";
  }
}

// Edit
if (isset($_POST['update_navita'])) {
  $navita_id = $_POST['navita_id'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];

  $sql = "UPDATE navita SET question='$question', answer='$answer', status='answered', date_answered=NOW() WHERE navita_id=$navita_id";
  
  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('Q and A updated successfully!'); window.location.href = 'navita.php'; </script>";
      exit();
  } else {
      echo "<script> alert('Error: ' . $sql . '<br>' . $conn->error); window.location.href = 'navita.php'; </script>";
  }
}

// Answering Question
if (isset($_POST['submit_answer'])) {
  $question_id = $_POST['question_id'];
  $answer = $_POST['answer'];
  $status = 'answered';

  // Update the navita table
  $sql_update = "UPDATE navita SET answer = '$answer', status = '$status', date_answered = NOW() WHERE navita_id = '$question_id'";
  
  if ($conn->query($sql_update) === TRUE) {
      echo "<script> alert('Answer submitted successfully.'); window.location.href = 'navita.php'; </script>";
  } else {
      echo "<script> alert('Error updating record: " . $conn->error . "'); window.location.href = 'navita.php'; </script>";
  }
}

// Reject Questions
if (isset($_GET['reject_id'])) {
  $question_id = $_GET['reject_id'];
  $status = 'rejected';

  // Update the navita table to set the status as rejected
  $sql_reject = "UPDATE navita SET status = '$status', date_answered = NOW() WHERE navita_id = '$question_id'";

  if ($conn->query($sql_reject) === TRUE) {
      echo "<script> alert('Question rejected successfully.'); window.location.href = 'navita.php'; </script>";
  } else {
      echo "<script> alert('Error rejecting question: " . $conn->error . "'); window.location.href = 'navita.php'; </script>";
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
    .search-input {
      position: relative;
    }

    .form-control {
      border: 5px solid #2e5a31 !important;
      border-radius: 0.25rem;
    }

    .search-input input {
      /* padding-left: 2.5rem;  */
      padding: 0.8rem 1rem 0.8rem 2.5rem;
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
    .border-data {
      margin: 0; 
      border: 5px solid #2e5a31; 
      border-bottom: 0px; 
    }
  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "navita";
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
      <div class="row pt-3">
      <div class="col-md-4">
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="color: white;">
          Add Question
        </button>
      </div>
      <div class="col-md-4 text-center">
        <h1>
          <b>
            NAVITA
          </b>
        </h1>
      </div>
      <div class="col-md-4">
      </div>
     </div>
     <div class="row">
      <div class="col-md-8">
        <h4 class="text-center w-100 border-data"><b>Data</b></h4>
        <table class="table table-bordered border-success">
        <thead class="text-center">
            <tr>
              <th scope="col"><h4 class="m-0">Question</h4></th>
              <th scope="col"><h4 class="m-0">Answer</h4></th>
              <th scope="col"><h4 class="m-0">Actions</h4></th>
            </tr>
          </thead>
          <tbody>
          <?php
        // Fetch all questions and answers from the database
        $sql = "SELECT * FROM navita WHERE status='answered'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $updated_ans = nl2br($row['answer']);
                echo "<tr>
                        <th>{$row['question']}</th>
                        <th>{$updated_ans}</th>
                        <th class='text-center'>
                        <a class='btn btn-outline-success btn-sm'  data-bs-toggle='modal' data-bs-target='#answerModal". $row['navita_id'] ."'>
                            <img src='../assets/img/icon/edit.png' width='20'>
                        </a>
                            <a href='include/delete_navita.php?navita_id={$row['navita_id']}'  onclick='return confirm(\"Are you sure you want to delete this Q&A?\")' class='btn btn-outline-danger btn-sm'>
                              <img src='../assets/img/icon/delete.png' width='20'>
                            </a>
                        </th>
                    </tr>";

                    echo '<div class="modal fade" id="answerModal' . $row["navita_id"] . '" tabindex="-1" aria-labelledby="answerModalLabel' . $row["navita_id"] . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="answerModalLabel' . $row["navita_id"] . '">Editing Question and Answer</h5>
                                  <h5 class="modal-title" id="editModalLabel" style="color: white;"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form action="navita.php" method="POST">
                              <div class="modal-body">
                                <input type="hidden" id="edit_id" value="' . $row["navita_id"] . '" name="navita_id">
                                <div class="input-group mt-3">
                            <span class="input-group-text">Question</span>
                            <input type="text" name="question" class="form-control" value="' . htmlspecialchars($row["question"]) . '">
                        </div>
                        <div class="input-group mt-3">
                            <span class="input-group-text">Answer</span>
                            <textarea name="answer" class="form-control" rows="4">' . htmlspecialchars($row["answer"]) . '</textarea>
                        </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_navita" class="btn btn-success">Update</button>
                              </div>
                            </form>
                                
                            </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo "<tr><td colspan='3'>No questions available</td></tr>";
        }
        ?>
          </tbody>
        </table>
        
      </div>
      
      <div class="col-md-4">
    <h4 class="text-center w-100 border-data"><b>New Question/s</b></h4>
    <table class="table table-bordered border-success">
        <tbody>
            <?php
            // Fetch questions with 'pending' status from the navita table
            $sql_fetch = "SELECT navita_id, question FROM navita WHERE status = 'pending'";
            $result = $conn->query($sql_fetch);

            if ($result->num_rows > 0) {
                // Output each question in a table row
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th>' . htmlspecialchars($row["question"]) . '
                          <div class="float-end">
                              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#answerModal'. $row['navita_id'] .'">Answer</button>
                              <button type="button" class="btn btn-danger" onclick="rejectQuestion('. $row['navita_id'] .')">Reject</button>
                          </div>
                          </th>';
                    echo '</tr>';
                    echo '<div class="modal fade" id="answerModal' . $row["navita_id"] . '" tabindex="-1" aria-labelledby="answerModalLabel' . $row["navita_id"] . '" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="answerModalLabel' . $row["navita_id"] . '">Answer Question: <br>' . htmlspecialchars($row["question"]) . '</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="navita.php">
                                            <input type="hidden" name="question_id" value="' . $row["navita_id"] . '">
                                            <div class="mb-3">
                                                <label for="answer" class="form-label">Your Answer</label>
                                                <textarea name="answer" class="form-control" rows="4" required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="submit_answer" class="btn btn-success">Submit Answer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo '<tr><th class="text-center" colspan="2">No new questions available.</th></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
     </div>
      <div>

<!-- Modal for adding new User -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2e5a31; color: white;">
        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Adding Question and Answer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="navita.php" method="POST">
      <div class="modal-body">
        <div class="input-group mt-3">
          <span class="input-group-text">Question</span>
          <input type="text" aria-label="First name" name="question" class="form-control" required>
        </div>
        <div class="input-group mt-3">
          <span class="input-group-text">Answer</span>
          <!-- <input type="text" aria-label="First name" name="answer" class="form-control" required> -->
          <textarea aria-label="First name" name="answer" class="form-control" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="submit_navita" class="btn btn-success">Submit</button>
      </div>
        </form>
    </div>
  </div>
</div>

<!-- Edit Modal for updating Q&A -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2e5a31; color: white;">
        <h5 class="modal-title" id="editModalLabel" style="color: white;">Editing Question and Answer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Form for editing -->
      <form action="navita.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="navita_id">
          <div class="input-group mt-3">
            <span class="input-group-text">Question</span>
            <input type="text" id="edit_question" name="question" class="form-control">
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Answer</span>
            <!-- <input type="text" id="edit_answer" name="answer" class="form-control"> -->
            <textarea id="edit_answer" name="answer" class="form-control" rows="4"></textarea>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="update_navita" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
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
  function fillEditModal(id, question, answer) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_question').value = question;

    // Replace all variations of <br> with \n
    document.getElementById('edit_answer').value = answer
        .replaceAll("&lt;br&gt;", "\n") // Handles encoded <br>
        .replaceAll("<br>", "\n"); // Handles actual <br>

    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}
</script>

<script type="text/javascript">
function rejectQuestion(questionId) {
    if (confirm('Are you sure you want to reject this question?')) {
        // Redirect to the reject_question.php page with the question ID
        window.location.href = 'navita.php?reject_id=' + questionId;
    }
}
</script>