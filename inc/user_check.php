<?php

//           _         _              ___     ___   
//   _  _   | |__     (_)    _ _     | _ )   | _ )  
//  | +| |  | '_ \    | |   | ' \    | _ \   | _ \  
//   \_,_|  |_.__/   _|_|_  |_||_|   |___/   |___/  
// _|"""""|_|"""""|_|"""""|_|"""""|_|"""""|_|"""""| 
// "`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-' 
//
// This is the user check file. This will get the user's permissions, check if they are logged-in and possibly have access to pages


// Check if the page requires login and if so, check if they are
if (isset($requireLogin)) {

    if ($requireLogin) {

        if (isset($_SESSION['u_loggedin'])) {

            if ($_SESSION['u_loggedin'] == "0") {
                header("Location: login.php");
            }

        } else {
            header("Location: login.php");
        }

    }

}

$timeout = 86400; // One day in seconds


if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout) {
        if (isset($_SESSION['u_loggedin']) && $_SESSION['u_loggedin'] == "1" && $_SESSION['STAY_LOGGEDIN'] == "0") {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['toast'] = array('info', "Session Expired", "Your session has expired! Please re-login");
            header("Location: login.php");
            die();
        }
    } 
}

$_SESSION['LAST_ACTIVITY'] = time();


// Get's the permissions of the user
if (isset($_SESSION['u_loggedin']) && $_SESSION['u_loggedin'] == 1) {

    require 'inc/db.inc.php';
    $TEMPVAR = $_SESSION['u_id'];
    $getGlobalUser = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$TEMPVAR'");
    $globalUser = mysqli_fetch_assoc($getGlobalUser);
    $globaluserPerm = $globalUser['u_permission'];
    $perm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissions WHERE perm_id='$globaluserPerm'"));

}



// Check if user is banned
if (isset($globalUser)) {
    $isBanned = $globalUser['u_banned'];
    if ($isBanned == 1) {
        $_SESSION['toast'] = array("error", "Account Banned", "You've been banned from the forums!");
        header("Location: inc/logout.inc.php");
        die();
    }
}

// Force logout user
if (isset($globalUser)) {
    if ($globalUser['u_forcelogout'] == "1") {
        header("Location: inc/logout.inc.php");
        die();
    }
}

?>