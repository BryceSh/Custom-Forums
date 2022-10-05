<?

session_start();
require '../../inc/db.inc.php';

if (isset($_GET['status'])) {

    $status = $_GET['status'];
    $updateStatus = mysqli_query($conn, "UPDATE settings SET value='$status' WHERE settings_name='reports_enabled'");
    if ($updateStatus) {
        $_SESSION['toast'] = array("success", "Status Updated", "Status has been updated");
    } else {
        $_SESSION['toast'] = array("error", "Status Error", "An error has occured!");
    }
    header("Location: ../settings");
    die();

}

?>