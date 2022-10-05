<?

session_start();
require '../../inc/db.inc.php';

if (isset($_GET['id']) && isset($_GET['value'])) {

    $id = $_GET['id'];
    $value = $_GET['value'];
    $sql = mysqli_query($conn, "SELECT * FROM reports WHERE r_id='$id'");
    if (mysqli_num_rows($sql) == "1") {

        $update = mysqli_query($conn, "UPDATE reports SET r_status='$value' WHERE r_id='$id'");
        if ($update) {
            $_SESSION['toast'] = array("success", "Report Updated", "Report has been updated");
            header("Location: view_report.php?report=" . $_SESSION['REPORT_ID']);
            die();
        } else {
            header("Location: ../reports");
            die();
        }

    } else {
        header("Location: ../reports");
        die();
    }

} else {
    header("Location: ../reports");
        die();
}

?>