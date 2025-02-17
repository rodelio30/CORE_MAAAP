<?php
// Get user_id from session
$user_id = $_SESSION['user_id'];

$query = "SELECT profile_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// If profile_pic is empty, use a default image
$profile_pic = empty($profile_pic) ? '../img/user.png' : 'profiles/' . $profile_pic;

$dash_active = '';
$accounts_active = '';
$change_active = '';
$profile_active = '';
$navita_active = '';
$manage_active = '';
$room_active = '';
$employee_active = '';
$feed_active = '';


if($nav_active === 'index') {
    $dash_active = 'active';
}
if($nav_active === 'accounts') {
    $accounts_active = 'active';
}
if($nav_active === 'change') {
    $change_active = 'active';
}
if($nav_active === 'profile') {
    $profile_active = 'active';
}
if($nav_active === 'navita') {
    $navita_active = 'active';
}
if($nav_active === 'manage') {
    $manage_active = 'active';
}
if($nav_active === 'room') {
    $room_active = 'active';
}
if($nav_active === 'employee') {
    $employee_active = 'active';
}
if($nav_active === 'feedback') {
    $feed_active = 'active';
}
?>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="../assets/img/core/coreguide-logo.png" alt="Core Guide Logo">
        <!-- <h1 class="sitename">Append</h1><span>.</span> -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="<?=$dash_active?>">Home</a></li>
          <li><a href="accounts.php" class="<?=$accounts_active?>">Accounts</a></li>
          <!-- <li><a href="#">Manage Room</a></li>
          <li><a href="#">Employee Schedule</a></li>
          <li><a href="#">Room Schedule</a></li> -->
            <li class="dropdown"><a href="#"><span>Rooms</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="room-management.php" class="<?=$manage_active?>">Manage Room</a></li>
              <li><a href="room-schedule.php" class="<?=$room_active?>">Room Schedule</a></li>
              <li><a href="employee_schedule.php" class="<?=$employee_active?>">Employee Schedule</a></li>
            </ul>
          </li>
          
          <li><a href="navita.php" class="<?=$navita_active?>">Navita</a></li>

          <li><a href="change_password.php" class="<?=$change_active?>">Change Password</a></li>
          <li><a href="feedback.php" class="<?=$feed_active?>">Feedback</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="d-flex align-items-center">
        <h4 class="mt-2 me-2" style="color: black;"><b><?= $_SESSION["type"] == 'Superadmin' ? 'Super Admin':'Admin'; ?></b></h4>
        <a href="change_profile.php" class="me-2 <?=$profile_active?>">
            <img src="<?= $profile_pic; ?>" alt="User Icon" width="45" class="img-fluid" style="border-radius: 50%;">
        </a>
        <a class="btn-getstarted me-5" href="../include/signout.php" style="background-color: #d5b33c;">Logout</a>
      </div>

    </div>
  </header>