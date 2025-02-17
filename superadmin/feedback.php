<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

// Fetch feedbacks from the database
$sql = "SELECT * FROM feedback ORDER BY created_at DESC"; // Adjust as needed
$result = $conn->query($sql);

// Initialize $feedbacks as an empty array
$feedbacks = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row; // Populate $feedbacks with data
    }
}

if (isset($_POST['delete_single'])) {
  $feedback_id = $_POST['delete_single'];
  $sql = "DELETE FROM feedback WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $feedback_id);
  if ($stmt->execute()) {
      echo "<script>alert('Feedback deleted successfully!'); window.location.href = 'feedback.php';</script>";
  } else {
      echo "<script>alert('Error deleting feedback!');</script>";
  }
}

if (isset($_POST['delete_selected'])) {
  $selected_ids = $_POST['selected_feedbacks'];
  if (!empty($selected_ids)) {
      $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
      $sql = "DELETE FROM feedback WHERE id IN ($placeholders)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param(str_repeat('i', count($selected_ids)), ...$selected_ids);
      if ($stmt->execute()) {
          echo "<script>alert('Selected feedbacks deleted successfully!'); window.location.href = 'feedback.php';</script>";
      } else {
          echo "<script>alert('Error deleting selected feedbacks!'); window.location.href = 'feedback.php';</script>";
      }
  } else {
      echo "<script>alert('No feedback selected for deletion!'); window.location.href = 'feedback.php';</script>";
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
 /* Select All Section */
.select-all-container {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  font-size: 16px;
}

.select-all-checkbox {
  margin-right: 10px;
  cursor: pointer;
}

/* Feedback Card Styling */
.feedback-card {
  position: relative;
  display: flex;
  align-items: center;
  padding: 20px;
  background: #f9f9f9;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Checkbox Container (Centered on the Left) */
.checkbox-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
}

.delete-checkbox {
  width: 20px;
  height: 20px;
  cursor: pointer;
}

/* Feedback Content */
.feedback-content {
  flex-grow: 1;
  margin-left: 10px;
}

/* Delete Button */
.delete-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  border: none;
  background: red;
  color: white;
  font-size: 14px;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 50%;
}

.delete-btn:hover {
  background: darkred;
}

/* Bulk Delete Button */
.bulk-delete-btn {
  display: block;
  margin: 20px auto;
  background: red;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.bulk-delete-btn:hover {
  background: darkred;
}

.feedback-content .stars {
  margin-bottom: 1px;
}

.feedback-content .star {
  color: #f4d03f;
  font-size: 1.2em;
  margin-right: 5px;
}

.feedback-content .star.empty {
  color: #ddd;
}

  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "feedback";
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
      </div>
        <div class="col-md-4 text-center">
          <?php if (!empty($feedbacks)) : ?>
            <h1> <b> FEEDBACK </b> </h1>
          <?php elseif (empty($feedbacks)) : ?>
            <h1> <b> NO FEEDBACK </b> </h1>
          <?php endif; ?>
      </div>
      <div class="col-md-4">
      </div>
     </div>

      <div class="row pt-3">
      <div class="col-md-3"> </div>
      <div class="col-md-6">
        

      <form action="feedback.php" method="POST" id="feedback-form"  onsubmit="return confirmDelete(this);">
  <!-- Select All Checkbox -->
    <?php if (!empty($feedbacks)) : ?>
      <div class="select-all-container">
        <input type="checkbox" id="select-all" class="select-all-checkbox">
        <label for="select-all">Select All</label>
      </div>
      <?php endif; ?>
  <div class="feedback-list">
    <?php foreach ($feedbacks as $feedback) : ?>
      <div class="feedback-card">
        <!-- Centered Select Checkbox -->
        <div class="checkbox-container">
          <input type="checkbox" name="selected_feedbacks[]" value="<?php echo $feedback['id']; ?>" class="feedback-checkbox">
        </div>

        <!-- Feedback Content -->
        <div class="feedback-content">
          <!-- Delete Button -->
          <button type="submit" name="delete_single" value="<?php echo $feedback['id']; ?>" class="delete-btn">&times;</button>

          <h3>
            <span style="font-size: 12px;">Name: </span>
            <?php echo substr($feedback['name'], 0, 1) . str_repeat('*', strlen($feedback['name']) - 2) . substr($feedback['name'], -1); ?>
          </h3>

          <small>
            <span style="font-size: 12px;">Date: </span>
            <?php echo $feedback['created_at']; ?></small>
          <p class="mb-0 mt-2">
            <span style="font-size: 12px;">Message:  </span>
            <?php echo $feedback['message']; ?></p>
          
        <div class='stars'>
            <span style="font-size: 12px;">Rating: </span>
          <?php 
              for ($i = 1; $i <= 5; $i++) {
                if ($i <= $feedback['rating']) {
                  echo "<span class='star'>&#9733;</span>"; // Full star
                } else {
                  echo "<span class='star empty'>&#9734;</span>"; // Empty star
                }
              }
          ?>
        </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (!empty($feedbacks)) : ?>
      <!-- <div class="bulk-delete"> -->
      <div class="bulk-delete" style="display: none;">
        <button type="submit" name="delete_selected" class="bulk-delete-btn">Delete Selected</button>
      </div>
    <?php endif; ?>
    

  </div>

</form>
      </div>
      <div class="col-md-3"> </div>
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
// Select All functionality
document.getElementById('select-all').addEventListener('change', function () {
  const checkboxes = document.querySelectorAll('.delete-checkbox');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = this.checked;
  });
});
</script>

<script>
function confirmDelete(form) {
    return confirm("Are you sure you want to delete this feedback?");
}
</script>
<script>
  // Wait for the DOM to load
  document.addEventListener("DOMContentLoaded", function () {
    const bulkDeleteContainer = document.querySelector('.bulk-delete');
    const checkboxes = document.querySelectorAll('.feedback-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');

    // Function to check if any checkbox is selected
    const updateBulkDeleteVisibility = () => {
      const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
      bulkDeleteContainer.style.display = isAnyChecked ? 'block' : 'none';
    };

    // Add event listeners to each feedback checkbox
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', updateBulkDeleteVisibility);
    });

    // Handle "Select All" checkbox
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
          checkbox.checked = this.checked; // Toggle based on "Select All"
        });
        updateBulkDeleteVisibility();
      });
    }
  });
</script>