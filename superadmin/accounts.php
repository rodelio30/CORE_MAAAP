<?php
define('Imember', true); 
require('../include/dbconnect.php'); // Connect to the database

if (empty($_SESSION['user_id'])) {
    header("location: ../index.php");
    exit;
}
$user_type = $_SESSION["type"];

// Fetch user accounts from the database
    $user_id_checker = $_SESSION['user_id'];
    $sql = "SELECT user_id, name, username FROM users WHERE user_id != $user_id_checker";
    $result = $conn->query($sql);


// Insert the Accounts data once clicked the submit button
if (isset($_POST["submit_user"])) {
  // Retrieve form data
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Encrypt the password using MD5
  $hashed_password = md5($password);

  // Prepare and execute the insert query
  $sql = "INSERT INTO users (name, username, password, type, isActive, date_created, user_status) 
          VALUES ('$name', '$username', '$hashed_password', 'admin', 1, NOW(), 'new')";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('New user created successfully!'); window.location.href = 'accounts.php'; </script>";
  } else {
      echo "<script> alert('Error: " . $sql . "<br>' . $conn->error); </script>";
  }
}

if (isset($_POST["generate_password"])) {
  $user_id = $_POST['user_id'];
  $new_password = md5($_POST['new_password']); // Encrypt new password using MD5

  // Update the password in the database
  $sql = "UPDATE users SET password = '$new_password' WHERE user_id = '$user_id'";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('Password successfully updated!'); window.location.href = 'accounts.php'; </script>";
  } else {
      echo "<script> alert('Error updating password: ' . $conn->error); </script>";
  }
}
 
// Edit User Data
if (isset($_POST["edit_user"])) {
  $user_id = $_POST['user_id'];
  $name = $_POST['name'];
  $username = $_POST['username'];

  // Update only if a new password is provided
  $sql = "UPDATE users SET name='$name', username='$username' WHERE user_id='$user_id'";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('User updated successfully!'); window.location.href = 'accounts.php'; </script>";
      // Optionally redirect back to the list page
  } else {
      echo "<script> alert('Error updating user: ' . $conn->error); </script>";
  }
}

// Delete User
if (isset($_POST["delete_user"])) {
  $user_id = $_GET['user_id'];

  // Delete the user from the database
  $sql = "DELETE FROM users WHERE user_id = '$user_id'";

  if ($conn->query($sql) === TRUE) {
      echo "<script> alert('User successfully deleted!'); window.location.href = 'accounts.php'; </>";
  } else {
      echo "<script> alert('Error deleting user: ' . $conn->error); </script>";
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
  </style>
</head>

<body class="index-page">
  <?php
    $nav_active = "accounts";
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
                <?php if($user_type == 'Superadmin') { ?>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="color: white;">
                    Add New Account
                </button>
                <?php } ?>
            </div>
            <div class="col-md-4 text-center">
                <h1>
                  <b>
                  ACCOUNTS
                  </b>
                </h1>
            </div>
            <div class="col-md-4">
                <form method="GET" action="accounts.php">
                    <div class="search-input">
                        <!-- <i class="fas fa-search" style="font-size: 1.4rem;"></i> -->
                        <!-- <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search_keyword); ?>"> -->
                        <input class="form-control" type="text" id="searchInput" placeholder="Search" aria-label="Search" onkeyup="filterTable()" style="width: 99%;">
                    </div>
                </form>
            </div>
          </div>

    <?php
if ($result->num_rows > 0) {
  echo '<table class="table table-bordered border-success mt-4 text-center" id="accountTable"> <!-- Add an ID for the table -->
          <thead>
              <tr>
                  <th scope="col">Username</th>
                  <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody>';

  // Output data for each row
  while($row = $result->fetch_assoc()) {
    echo '<tr>
            <th><h4 class="mt-2">' . $row["username"] . '</h4></th>
            <td class="pt-2">';
    
    // Edit/View button (changes based on user type)
    echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal'. $row['user_id'] .'">';
    echo ($user_type == 'Superadmin') ? 'Edit' : 'View';
    echo '</button>';

    // Additional options for Superadmin
    if($user_type == 'Superadmin') {
        // echo ' <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generatePasswordModal' . $row["user_id"] . '">
              echo ' <button type="button" class="btn btn-success" onclick="confirmGeneratePassword(' . $row["user_id"] . ', \'' . htmlspecialchars($row["name"], ENT_QUOTES) . '\')">
                Generate New Password
              </button>
              <a href="include/delete_user.php?user_id=' . $row["user_id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
    }
    echo '</td></tr>';

                // Modal for editing user
                echo '<div class="modal fade" id="editModal' . $row["user_id"] . '" tabindex="-1" aria-labelledby="editModalLabel' . $row["user_id"] . '" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header" style="background-color: #2e5a31;">
                              <h5 class="modal-title" id="editModalLabel' . $row["user_id"] . '" style="color: white;">Edit User</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="POST" action="accounts.php">
                              <div class="modal-body">
                                  <input type="hidden" name="user_id" value="' . $row["user_id"] . '">
                                  <div class="input-group mt-3">
                                      <span class="input-group-text">Name</span>';
                                      if($user_type == 'Superadmin') {
                                        echo ' <input type="text" name="name" class="form-control" value="' . $row["name"] . '" required> ';
                                      } else {
                                        echo ' <input type="text" name="name" class="form-control" value="' . $row["name"] . '" disabled> ';
                                      }
                                      echo '
                                  </div>
                                  <div class="input-group mt-3">
                                      <span class="input-group-text">Username</span> ';
                                      if($user_type == 'Superadmin') {
                                        echo ' <input type="text" name="username" class="form-control" value="' . $row["username"] . '" required> ';
                                      } else {
                                        echo ' <input type="text" name="username" class="form-control" value="' . $row["username"] . '" disabled> ';
                                      }
                                      echo '
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button> ';
                                  if($user_type=='Superadmin') {
                                      echo' <button type="submit" name="edit_user" class="btn btn-success">Save Changes</button> ';
                                    }
                                  echo '
                              </div>
                          </form>
                      </div>
                  </div>
              </div>';
              // Modal for generating new password
              echo '<div class="modal fade" id="generatePasswordModal' . $row["user_id"] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header" style="background-color: #2e5a31;">
                                  <h5 class="modal-title" id="exampleModalLabel" style="color: white;">Generate New Password for ' . $row["name"] . '</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <form method="POST" action="accounts.php">
                                      <input type="hidden" name="user_id" value="' . $row["user_id"] . '">
                                      <div class="input-group mt-3">
                                          <span class="input-group-text">New Password</span>
                                          <input type="text" id="passwordInput' . $row["user_id"] . '" name="new_password" class="form-control" readonly>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                          <button type="submit" name="generate_password" class="btn btn-success">Generate</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                    </div>';
          }
          echo '</tbody></table>';
      } else {
          echo "No users found.";
      }
      ?>
     <!-- Viewing the list of Accounts -->
        </div>

    </section><!-- /Explore Section -->

<!-- Modal for adding new User -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2e5a31;">
        <h5 class="modal-title" id="staticBackdropLabel" style="color: white;">Adding Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="accounts.php">
        <div class="modal-body">
          <div class="input-group mt-3">
            <span class="input-group-text">Name</span>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Username</span>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="input-group mt-3">
            <span class="input-group-text">Generate Password</span>
            <input type="text" name="password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit_user" class="btn btn-success">Submit</button>
        </div>
      </form>
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
        var tds = trs[i].getElementsByTagName("th")[0]; // We are only searching in the 'Name' column
        if (tds) {
            var txtValue = tds.textContent || tds.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                trs[i].style.display = ""; // Show row if match found
            } else {
                trs[i].style.display = "none"; // Hide row if no match
            }
        }
    }
}
</script>


<script>
  // Function to confirm password generation and show the modal
  function confirmGeneratePassword(userId, userName) {
    if (confirm('Are you sure you want to generate a new password for ' + userName + '?')) {
      // Generate random password
      const password = generateRandomPassword(8);
      document.getElementById('passwordInput' + userId).value = password;

      // Show the modal
      const modal = new bootstrap.Modal(document.getElementById('generatePasswordModal' + userId));
      modal.show();
    }
  }

  // Function to generate random password
  function generateRandomPassword(length) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';
    for (let i = 0; i < length; i++) {
      password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return password;
  }
</script>