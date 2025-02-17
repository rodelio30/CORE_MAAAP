<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

// Handle form submission
if (isset($_POST["submit_feedback"])) {
  $name = $_POST['name'];
  $message = $_POST['message'];
  $rating = intval($_POST['rating']); // Convert rating to an integer

  // Insert data into the feedback table
  $sql = "INSERT INTO feedback (name, message, rating) VALUES ('$name', '$message', $rating)";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('Feedback submitted successfully!'); window.location.href = 'feedback_admin.php'; </script>";
  } else {
      echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
      echo "<script> alert('Error: " . $conn->error . "'); </script>";
  }
}

// Fetch the average rating and count of each star rating
$sql = "
SELECT 
    ROUND(AVG(rating), 1) AS avg_rating, 
    COUNT(CASE WHEN rating = 5 THEN 1 END) AS five_star,
    COUNT(CASE WHEN rating = 4 THEN 1 END) AS four_star,
    COUNT(CASE WHEN rating = 3 THEN 1 END) AS three_star,
    COUNT(CASE WHEN rating = 2 THEN 1 END) AS two_star,
    COUNT(CASE WHEN rating = 1 THEN 1 END) AS one_star,
    COUNT(*) AS total_ratings
FROM feedback";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Extract data
$avg_rating = $data['avg_rating']; // Average rating
$five_star = $data['five_star'];
$four_star = $data['four_star'];
$three_star = $data['three_star'];
$two_star = $data['two_star'];
$one_star = $data['one_star'];
$total_ratings = $data['total_ratings'];
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
.rating {
  direction: rtl; /* Align stars right-to-left for better user experience */
  unicode-bidi: bidi-override;
  display: inline-flex;
}

.rating input {
  display: none; /* Hide the radio buttons */
}

.rating label {
  font-size: 30px;
  color: #ddd;
  cursor: pointer;
}

.rating input:checked ~ label {
  color: gold; /* Highlight stars that are selected */
}

.rating label:hover,
.rating label:hover ~ label {
  color: gold; /* Highlight stars on hover */
}

.rating-summary {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px; /* Adjust spacing as needed */
}
/* .rating-summary {
  width: 100%;
  max-width: 600px;
  margin: auto;
  text-align: center;
  font-family: Arial, sans-serif;
} */

.average-rating {
  text-align: center;
  flex: 1;
}

.average-rating h1 {
  font-size: 3rem;
  margin: 0;
}

.stars {
  margin-top: 10px;
}

.star {
  font-size: 1.5rem;
  color: gold;
}

.star.empty {
  color: #ccc;
}

.rating-breakdown {
  flex: 2;
}

.rating-bar {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.star-number {
  font-size: 1rem;
  width: 20px;
  text-align: center;
}

.bar {
  flex: 1;
  height: 8px;
  background: #e0e0e0;
  margin: 0 10px;
  position: relative;
  border-radius: 4px;
  overflow: hidden;
}

.bar .fill {
  height: 100%;
  background: #4caf50;
}

.count {
  width: 30px;
  text-align: right;
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
      <div class="container section-title">
        <h2>Feedback</h2>
        <!-- <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p> -->
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-md-6">

              <form action="feedback_admin.php" method="POST" class="php-email-form">
          <p class="text-center">-- Help improve the Core Guide! --</p>
              <div class="row gy-4">
                <div class="col-md-12">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>
                <div class="col-12">
                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                </div>

              <!-- Star Rating (5 stars only) -->
              <div class="col-12 text-center">
                  <p class="mb-0">Star Rating:</p>
                  <div class="rating">
                    <!-- Radio buttons hidden; stars will represent the rating -->
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5" title="5 stars">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 stars">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 stars">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 stars">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 star">★</label>
                  </div>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" name="submit_feedback" class="btn btn-success">Send Feedback</button>
                </div>
              </div>
            </form>
            </div>
          <div class="col-md-6">
          <div class="rating-summary">
            <!-- Average Rating -->
            <div class="average-rating">
              <h1><?php echo $avg_rating; ?></h1>
              <div class="stars">
                <?php
                $full_stars = floor($avg_rating);
                $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $full_stars) {
                    echo '<span class="star">&#9733;</span>'; // Full star
                  } elseif ($half_star && $i == $full_stars + 1) {
                    echo '<span class="star">&#9734;</span>'; // Half star
                  } else {
                    echo '<span class="star empty">&#9734;</span>'; // Empty star
                  }
                }
                ?>
              </div>
            </div>

            <!-- Star Rating Breakdown -->
            <div class="rating-breakdown">
              <?php
              $stars = [
                5 => $five_star,
                4 => $four_star,
                3 => $three_star,
                2 => $two_star,
                1 => $one_star
              ];
              foreach ($stars as $star => $count) {
                $percentage = ($total_ratings > 0) ? ($count / $total_ratings) * 100 : 0;
                echo "
                <div class='rating-bar'>
                  <span class='star-number'>$star</span>
                  <div class='bar'>
                    <div class='fill' style='width: {$percentage}%;'></div>
                  </div>
                  <span class='count'>$count</span>
                </div>";
              }
              ?>
            </div>
          </div> 

          <div class="feedback-list">
  <?php
  // Fetch feedback from the database
  $sql = "SELECT name, message, rating, created_at FROM feedback ORDER BY created_at DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Mask the user's name
      $name_parts = explode(' ', $row['name']);
      $masked_name = '';
      foreach ($name_parts as $part) {
        $masked_name .= substr($part, 0, 1) . str_repeat('*', strlen($part) - 2) . substr($part, -1) . ' ';
      }
      $masked_name = trim($masked_name);

      // Display the card
      echo "
      <div class='feedback-card'>
        <h3 class='mb-0'>$masked_name</h3>
        <div class='stars'>";
      // Display stars based on rating
      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $row['rating']) {
          echo "<span class='star'>&#9733;</span>"; // Full star
        } else {
          echo "<span class='star empty'>&#9734;</span>"; // Empty star
        }
      }
      echo "
        </div>
        <p class='message mb-0'>{$row['message']}</p>
        <p class='date'>{$row['created_at']}</p>
      </div>";
    }
  } else {
    echo "<p style='margin: 2rem auto;'>-- No feedback available. --</p>";
  }
  ?>
</div>
                    
          </div>
          <!-- End Contact Form -->

        </div>

      </div>
       <br>
       <br>
       <br>

    </section><!-- /Contact Section -->


  </main>

  <?php
    include 'include/footer-files.php';
  ?>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/navita.js"></script>
</body>

</html>

