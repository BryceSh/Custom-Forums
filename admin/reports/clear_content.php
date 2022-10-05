<?

session_start();
require '../../inc/db.inc.php';

if (isset($_GET['type']) && isset($_GET['id'])) {

    $type = $_GET['type'];
    $id = $_GET['id'];
    if ($type == "1") {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM threads WHERE th_id='$id'")) == "1") {
            $updateSQL = mysqli_query($conn, "UPDATE threads SET th_content='** content deleted **' WHERE th_id='$id'");
            if ($updateSQL) {
                $_SESSION['toast'] = array("success", "Content Cleared", "Content has been cleared successfully!");
                header("Location: view_report.php?report=" . $_SESSION['REPORT_ID']);
                die();
            }
        } else {
            header("Location: ../reports");
            die();
        }
    } else if ($type == "2") {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comments WHERE cm_id='$id'")) == "1") {
            $updateSQL = mysqli_query($conn, "UPDATE comments SET cm_content='** content deleted **' WHERE cm_id='$id'");
            if ($updateSQL) {
                $_SESSION['toast'] = array("success", "Content Cleared", "Content has been cleared successfully!");
                header("Location: view_report.php?report=" . $_SESSION['REPORT_ID']);
                die();
            }
        } else {
            header("Location: ../reports");
            die();
        }
    }

}

?>