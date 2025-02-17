
  <header id="header" class="header header-right">
    <nav id="navmenu" class="navmenu pt-4">
      <ul>
        <!-- <li>
            <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo" width="75">
        </li> -->
        <li data-tooltip="Maps"><a href="explore.php">
          <img src="assets/img/icon/explore.png"  alt="Core Guide Logo" width="70">
        </a></li>
        <li data-tooltip="Search"><a href="index.php">
          <img src="assets/img/icon/search.png"  alt="Core Guide Logo" class="nav-icon" width="70">
        </a></li>
        <li data-tooltip="Feedback"><a href="feedback.php">
          <img src="assets/img/icon/feedback.png"  alt="Core Guide Logo" width="70">
          </a></li>
        <li data-tooltip="About"><a href="about.php">
          <img src="assets/img/icon/about.png"  alt="Core Guide Logo" width="70">
          </a></li>
        <!-- <li data-tooltip="Team"><a href="team.php">
          <img src="assets/img/icon/team.png"  alt="Core Guide Logo" width="50">
        </a></li> -->
        <li data-tooltip="Login">
            <a href="login.php">
          <img src="assets/img/icon/login.png"  alt="Core Guide Logo Login" width="70">
        </a></li>

        <br> <br>
        <li>
          <img src="assets/img/icon/navita-v2.gif" alt="Chat Icon" id="chatIcon" width="150" class="corner-image-gif">
        </li>
        <!-- <li> </li>  -->
        <!-- Don't remove this last li -->
      </ul>
      <!-- <i class="mobile-nav-toggle d-xl-none bi bi-list"></i> -->
      <i class="mobile-nav-toggle"></i>
    </nav>
</header>
    <!-- <a class="btn-getstarted me-5" href="login.php">Login</a> -->


    <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
          <img src="assets/img/core/coreguide-logo.png" alt="Core Guide Logo" width="40" style="background-color: white;">
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
            >
            <button type="submit" id="submitButton" name="submit_navita" class="btn btn-success" disabled>
                <i class="bi bi-send"></i> <!-- Bootstrap icon -->
            </button>
        </div>
    </form>
</div> 
    </div>