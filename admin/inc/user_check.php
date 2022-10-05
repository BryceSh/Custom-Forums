<?

if (isset($requiredLogin)) {
    if ($requiredLogin) {

        if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] == "0") {
            header("Location: ../login");
            die();
        }

// DISABLED BECAUSE IT AIN'T WORKING!!!!

        // $time = 30;
        // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $time)) {
        //     $_SESSION['admin_loggedin'] == "0";
        //     $_SESSION['toast'] = array('info', "Re-authenticate", "Due to inactivity, please re-authenticate!");
        //     header("Location: ../login");
        //     die();
        // }

        $_SESSION['LAST_ACTIVITY'] = time();

    }

}


?>