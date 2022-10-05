<?

session_start();

if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] == "1") {

    header("Location: dash");

} else {

    header("Location: login");

}


?>