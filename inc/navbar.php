<?php

// This is where we will program in the navbar

if (isset($_SESSION['toast']) && $_SESSION['toast'] !== NULL) {
    
  $toastArray = $_SESSION['toast'];

  $toastType = $toastArray[0];
  $toastTitle = $toastArray[1];
  $toastBody = $toastArray[2];
  
  if ($toastType !== "" && $toastTitle !== "" && $toastBody !== "") {

      if ( $toastType == "success" ) {
          echo '<div id="us-toast" class="us-success">
            <div class="us-toast-header">
              <i class="fas fa-check-circle fa-lg me-2"></i>
              <strong>' . $toastTitle . '</strong>
              <div class="us-toast-right">
                <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
              </div>
            </div>
            <div class="us-toast-content">'. $toastBody .'</div>
          </div>';
      } elseif ( $toastType == "warn" ) {
          echo '<div id="us-toast" class="us-warn">
            <div class="us-toast-header">
              <i class="fas fa-exclamation-circle fa-lg me-2"></i>
              <strong>' . $toastTitle . '</strong>
              <div class="us-toast-right">
                <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
              </div>
            </div>
            <div class="us-toast-content">'. $toastBody .'</div>
          </div>';
      } elseif ( $toastType == "error" ) {
          echo '<div id="us-toast" class="us-error">
            <div class="us-toast-header">
              <i class="fas fa-times-circle fa-lg me-2"></i>
              <strong>' . $toastTitle . '</strong>
              <div class="us-toast-right">
                <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
              </div>
            </div>
            <div class="us-toast-content">'. $toastBody .'</div>
          </div>';
      } elseif ( $toastType == "info" ) {
          echo '<div id="us-toast" class="us-info">
            <div class="us-toast-header">
              <i class="fas fa-info-circle fa-lg me-2"></i>
              <strong>' . $toastTitle . '</strong>
              <div class="us-toast-right">
                <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
              </div>
            </div>
            <div class="us-toast-content">'. $toastBody .'</div>
          </div>';
      }
      
      $_SESSION['toast'] = array("", "", "");
      
  }
  
}

echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
<!-- Container wrapper -->
<div class="container-fluid">
  <!-- Toggle button -->
  <button
    class="navbar-toggler"
    type="button"
    data-mdb-toggle="collapse"
    data-mdb-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <i class="fas fa-bars"></i>
  </button>

  <!-- Collapsible wrapper -->
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Navbar brand -->
    <a class="navbar-brand mt-2 mt-lg-0" href="#">
      <img
        src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp"
        height="15"
        alt="MDB Logo"
        loading="lazy"
      />
    </a>
    <!-- Left links -->
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Forum Index</a>
      </li>
      <!--<li class="nav-item">
        <a class="nav-link" href="#">Staff</a>
      </li>-->
      <li class="nav-item">
        <a class="nav-link" href="users.php">Users</a>
      </li>
    </ul>
    <!-- Left links -->
  </div>
  <!-- Collapsible wrapper -->';

    if (isset($_SESSION['u_loggedin']) && $_SESSION['u_loggedin'] == 1) {
        echo '<!-- Right elements -->
        <div class="d-flex align-items-center">';
          //<div class="pe-2">Username</div>
          if (isset($_SESSION['u_permission'])) {
            require 'inc/db.inc.php';
            $Curusername = $globalUser['u_username'];
            $CurID = $globalUser['u_id'];

            $permDisplay = $perm['perm_display'];
            $display = "" . str_replace("{USERNAME}", $Curusername, $permDisplay);
          }

          $unreadReceived = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM messages WHERE ms_user='$CurID' AND ms_viewed='0' AND ms_main='1'"));
          $unreadSent = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM messages WHERE ms_sendby='$CurID' AND ms_senderview='0' AND ms_main='1'"));

          $unreadMessage = $unreadReceived + $unreadSent;
          if ($unreadMessage == "0") {
            $unreadMessage = "";
          }

          echo '<div class="pe-2">' . $display . '</div>';
          echo '<!-- Icon -->
      
          <!-- Notifications -->
          <div class="dropdown">
            <a
              class="text-reset me-3 dropdown-toggle hidden-arrow"
              href="profile.php?messages"
            >
              <i class="fas fa-bell"></i>
              <span class="badge rounded-pill badge-notification bg-danger">'.$unreadMessage.'</span>
            </a>
          </div>
          <!-- Avatar -->
          <div class="dropdown">
            <a
              class="dropdown-toggle d-flex align-items-center hidden-arrow"
              href="#"
              id="navbarDropdownMenuAvatar"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                src="'. $globalUser['u_avatar'] .'"
                class="rounded-circle"
                height="25"
                alt="Black and White Portrait of a Man"
                loading="lazy"
                style="height: 25px; width: 25px;"
              />
            </a>
            <ul
              class="dropdown-menu dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuAvatar"
            >
              <li>
                <a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i>My profile</a>
              </li>';
              if ($perm['perm_all'] == "1" || $perm['perm_admin']) {
                echo '<li><a class="dropdown-item" href="admin"><i class="fas fa-gavel me-2"></i>Admin Dashboard</a></li>';
              }
              echo '<li>
                <a class="dropdown-item" href="inc/logout.inc.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
              </li>
            </ul>
          </div>
        </div>
        <!-- Right elements -->';
    } else {
        echo '<!-- Right elements -->
        <div class="d-flex align-items-center">
            <a href="login.php" class="btn btn-primary mx-2">Login</a> <a href="register.php" class="btn btn-primary mx-2">Register</a>
        </div>
        <!-- Right elements -->';
    }
  echo '
  
</div>
<!-- Container wrapper -->
</nav>';

if (isset($_SESSION['u_loggedin']) && $_SESSION['u_loggedin'] == 1) {
  if ($globalUser['u_emailverified'] == "0") {

    echo '<div class="container bg-danger mb-2 py-2 text-center" style="color: white;">Email is not verified! <a href="inc/resendVerify.php?userID='.$globalUser['u_id'].'" style="color: white;"><b>Click here to re-send verification email!</b></a></div>';

  }
}

?>