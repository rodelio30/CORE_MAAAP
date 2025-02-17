<?php
// Get user_id from session
$user_id = $_SESSION['user_id'];
$user_type_nav = $_SESSION["type"];

$query = "SELECT profile_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// If profile_pic is empty, use a default image
$profile_pic = empty($profile_pic) ? '../img/user.png' : 'profiles/' . $profile_pic;
?>
 
  <header id="header" class="header header-right">
    <nav id="navmenu" class="navmenu pt-4">
      <ul>

          <!-- Profile -->
          <li data-tooltip="Profile" class="dropdown"><a href="#"><span>
          <img src="../assets/img/icon/profile.png"  alt="Core Guide Logo" width="50">
            </span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li data-tooltip="Change Profile" style="z-index: 999;">
                <a href="change_profile.php" style="margin: 0 !important;">
                <img src="<?= $profile_pic; ?>"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li data-tooltip="Change Password" style="z-index: 999;">
                <a href="change_password.php" style="margin: 0 !important;">
                <img src="../assets/img/icon/change.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li></li>
            </ul>
          </li>

        <!-- <li data-tooltip="Home"><a href="index.php">
          <img src="../assets/img/icon/navigate.png"  alt="Core Guide Logo" class="nav-icon" width="50">
        </a></li> -->
        <!-- All Icon for Public -->
        <li data-tooltip="Public Icon" class="dropdown"><a href="#"><span>
          <img src="../assets/img/icon/all-public.png"  alt="Core Guide Logo" width="50">
            </span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li data-tooltip="Maps" style="z-index: 999;">
                <a href="explore.php" style="margin: 0 !important;">
                <img src="../assets/img/icon/explore.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li data-tooltip="Search" style="z-index: 999;">
                <a href="index.php" style="margin: 0 !important;">
                <img src="../assets/img/icon/search.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li data-tooltip="Feedback" style="z-index: 999;">
                <a href="feedback_admin.php" style="margin: 0 !important;">
                <img src="../assets/img/icon/feedback.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li data-tooltip="About" style="z-index: 999;">
                <a href="about_admin.php" style="margin: 0 !important;">
                <img src="../assets/img/icon/about.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
                </a>
              </li>
              <li></li>
            </ul>
          </li>

        <li data-tooltip="Rooms" class="dropdown"><a href="#"><span>
            <!-- Rooms -->
          <img src="../assets/img/icon/rooms.png"  alt="Core Guide Logo" width="50">
            </span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li data-tooltip="Manage Rooms" style="z-index: 999;"><a href="room-management.php" class="<?=$manage_active?>" style="margin: 0 !important;">
                <img src="../assets/img/icon/room_manage.png"  alt="Core Guide Logo" width="50" style="margin: 0 !important;">
              </a></li>
              <li data-tooltip="Room Schedule"><a href="room-schedule.php" class="<?=$room_active?>">
                <img src="../assets/img/icon/sched.png"  alt="Core Guide Logo" width="50">
              </a></li>
              <li data-tooltip="Teacher Schedule" style="z-index: 999;"><a href="employee_schedule.php" class="<?=$employee_active?>">
                <img src="../assets/img/icon/emp.png"  alt="Core Guide Logo" width="50">
              </a></li>
              <li></li>
            </ul>
          </li>
        <li data-tooltip="Navita"><a href="navita.php">
          <img src="../assets/img/icon/navita.png"  alt="Core Guide Logo" width="50">
        </a></li>
        <!-- <li data-tooltip="Explore"><a href="explore.php">
          <img src="../assets/img/icon/explore.png"  alt="Core Guide Logo" width="50">
        </a></li> -->

        <li data-tooltip="Feedback"><a href="feedback.php">
          <img src="../assets/img/icon/feedback.png"  alt="Core Guide Logo" width="50">
          </a></li>
        <li data-tooltip="About"><a href="about.php">
          <img src="../assets/img/icon/about.png"  alt="Core Guide Logo" width="50">
        </a></li>
        <?php if($user_type_nav == 'Superadmin') { ?>
          <li data-tooltip="Accounts"><a href="accounts.php">
            <img src="../assets/img/icon/accounts.png"  alt="Core Guide Logo" width="50">
            </a></li>
        <?php } ?>
        <li data-tooltip="Logout">
            <a href="../include/signout.php">
          <img src="../assets/img/icon/logout.png"  alt="Core Guide Logo Login" width="50">
        </a></li>
        <li>
          <img src="../assets/img/icon/navita-v2.gif" alt="Chat Icon" id="chatIcon" width="70" class="corner-image-gif">
        </li>
        <!-- <li> </li>  -->
        <!-- Don't remove this last li -->
      </ul>
      <!-- <i class="mobile-nav-toggle d-xl-none bi bi-list"></i> -->
      <i class="mobile-nav-toggle"></i>
    </nav>
</header>

<!-- <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
          <img src="../assets/img/core/coreguide-logo.png" alt="Core Guide Logo" width="40" style="background-color: white;">
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
                </ul>
            </div>
            <div id="answerDisplay" class="mt-2" style="display: none;">
                <div class="alert alert-info"></div>
            </div>
            <button type="submit" id="submitButton" name="submit_navita" class="btn btn-primary mt-2" disabled>Submit</button>
        </form>
    </div>
    </div> -->
    <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
          <img src="../assets/img/core/coreguide-logo.png" alt="Core Guide Logo" width="40" style="background-color: white;">
            <h3 class="m-2" style="color: white;">Navita</h3>
            <button class="close-btn" id="closeBtn">&times;</button>
        </div>
        <!-- <div class="chat-body"> -->
        <div class="chat-container">
    <div class="chat-body" id="chatContainer">
        <div class="welcome-message mb-2">
            <p>Hi! 👋 I'm Navita, your virtual assistant. Let me help you! You can select a frequently asked question below or type your own question to get started. 😊</p>
        </div>
        <div id="suggestions" class="mb-2">
            <p style="font-size: 14px;"><strong>Frequently Asked Questions:</strong></p>
            <!-- Suggestions dynamically added here -->
        </div>
    </div>

    <form id="questionForm" action="index.php" method="post" class="chat-footer" style="display: flex; align-items: center; gap: 10px;">
        <div class="input-group">
            <input
                type="text"
                id="questionInput"
                class="form-control"
                name="question"
                placeholder="Type your question..."
                autocomplete="off"
                required
                style="border: 1px solid gray !important;"
            >
            <button type="submit" id="submitButton" name="submit_navita" class="btn btn-success" disabled>
                <i class="bi bi-send"></i> <!-- Bootstrap icon -->
            </button>
        </div>
    </form>
</div> 
    </div>