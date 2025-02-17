<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}

// Fetch about sections
$query = "SELECT * FROM about_sections";
$result = $conn->query($query);
$about_sections = [];
while ($row = $result->fetch_assoc()) {
    $about_sections[] = $row;
}

// Fetch core values
$query = "SELECT * FROM core_values WHERE about_id = 3 ORDER BY `order` ASC";
$result = $conn->query($query);
$core_values = [];
while ($row = $result->fetch_assoc()) {
    $core_values[] = $row;
}

$query_guide = "SELECT * FROM about_sections WHERE about_title = 'CORE Guide'";
$result_guide = $conn->query($query_guide);

$core_guide = null;
if ($result_guide->num_rows > 0) {
    $core_guide = $result_guide->fetch_assoc();
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
    .address-content {
      background-color: white; 
      padding: 1.2rem;
      border-radius: 10px;
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

    <!-- About Section -->
    <section id="about-company" class="about section" style="background-color: #f5f5f5;">

      <div class="container">
          <!-- End Section Title -->
        <div class="container section-title">
          <h2>About</h2>
        </div>

        <p class="m-0" style="color:rgb(232, 108, 108);">
  -- Click the description to edit or update it. --
</p>
        <div class="row gy-5">

    <div class="col-xl-5 content">
        <div class="address-content">
            <?php if ($core_guide): ?>
                <div class="core-guide-section">
                    <h2><?php echo htmlspecialchars($core_guide['about_title']); ?></h2>
                    <p 
                        class="editable" 
                        contenteditable="true" 
                        data-id="<?php echo $core_guide['about_id']; ?>" 
                        data-field="about_description">
                        <?php echo htmlspecialchars($core_guide['about_description']); ?>
                    </p>
                </div>
            <?php else: ?>
                <p>No content available for CORE Guide.</p>
            <?php endif; ?>
        </div>
        <br>

        <div class="row gy-4">
            <?php foreach ($about_sections as $section): ?>
                <?php if (!in_array($section['about_title'], ['CORE Guide', 'Core Values', 'Vision', 'Mission'])): ?>
                    <div class="col-md-6">
                        <div class="address-content">
                            <div class="info-item">
                                <i class="<?php echo $section['about_icon']; ?>"></i>
                                <h3><?php echo htmlspecialchars($section['about_title']); ?></h3>
                                <p 
                                    class="editable" 
                                    contenteditable="true" 
                                    data-id="<?php echo $section['about_id']; ?>" 
                                    data-field="about_description">
                                    <?php echo htmlspecialchars($section['about_description']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="row gy-4 icon-boxes">
            <?php foreach ($about_sections as $section): ?>
                <?php if (in_array($section['about_title'], ['Vision', 'Mission'])): ?>
                    <div class="col-md-12">
                        <div class="icon-box">
                            <h3><?php echo htmlspecialchars($section['about_title']); ?></h3>
                            <p 
                                class="editable" 
                                contenteditable="true" 
                                data-id="<?php echo $section['about_id']; ?>" 
                                data-field="about_description">
                                <?php echo htmlspecialchars($section['about_description']); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
    <div class="col-xl-4">
        <div class="row gy-4 icon-boxes">
            <div class="col-md-12">
                <div class="icon-box">
                    <h3>Core Values</h3>
                    <?php foreach ($core_values as $value): ?>
                        <div class="card p-2 mb-2">
                            <h5 class="mb-0"><?php echo htmlspecialchars($value['core_title']); ?></h5>
                            <p><b><?php echo htmlspecialchars($value['subtitle']); ?></b></p>
                            <p 
                                class="editable" 
                                contenteditable="true" 
                                data-id="<?php echo $value['core_id']; ?>" 
                                data-field="core_description">
                                <?php echo htmlspecialchars($value['core_description']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>
       
      </div>
    </section><!-- /About Section -->

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



<!-- <script>
  document.addEventListener('DOMContentLoaded', () => {
    const editableElements = document.querySelectorAll('.editable');

    editableElements.forEach(element => {
        element.addEventListener('blur', () => {
            const id = element.getAttribute('data-id');
            const field = element.getAttribute('data-field');
            const newValue = element.textContent.trim();

            // Send updated data to the server
            fetch('about_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    field: field,
                    value: newValue,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('The description has been successfully updated!');
                } else {
                    alert('Failed to update the description. Please try again.');
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        });

        // Optional: Prevent line breaks when pressing Enter
        element.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                element.blur(); // Trigger blur event to save
            }
        });
    });
});
</script> -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const editableElements = document.querySelectorAll('.editable');

    editableElements.forEach(element => {
        element.addEventListener('blur', () => {
            const id = element.getAttribute('data-id');
            const field = element.getAttribute('data-field');
            const newValue = element.textContent.trim();

            if (newValue === '') {
                alert("Field cannot be empty!");
                return;
            }

            // Show confirmation alert
            if (confirm("Are you sure you want to update this content?")) {
                // Send updated data to the server
                fetch('about_update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        field: field,
                        value: newValue,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('The description has been successfully updated!');
                    } else {
                        alert('Failed to update the description. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            } else {
                // If user cancels, revert the change (optional)
                element.textContent = element.getAttribute('data-original');
            }
        });

        // Store the original content before editing (for cancel case)
        element.addEventListener('focus', () => {
            element.setAttribute('data-original', element.textContent.trim());
        });

        // Prevent line breaks when pressing Enter
        element.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                element.blur(); // Trigger blur event to save
            }
        });
    });
});
</script>
