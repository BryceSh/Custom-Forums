<?

session_start();

if (isset($_GET['topicID']) && isset($_GET['confirmation'])) {

    require '../../inc/db.inc.php';

    $topicID = $_GET['topicID'];
    $confirmation = $_GET['confirmation'];

    if ($confirmation == "1") {

        $deleteSql = mysqli_query($conn, "DELETE FROM `topics` WHERE `t_id` = '$topicID'");
        if ($deleteSql) {
            $_SESSION['toast'] = array("success", "Deleted Successfully!", "Category has been deleted successfully!");
        } else {
            $_SESSION['toast'] = array('error', "Unable to delete", "An error has occured!<br>Error:" . mysqli_error($conn));
        }
        header("Location: topics.php");
        die();

    } else {
        $_SESSION['toast'] = array("error", "Invalid Request", "The request you've submitted is invalid");
        header("Location: topics.php");
        die();
    }

} else {
    $_SESSION['toast'] = array("error", "Invalid Request", "The request you've submitted is invalid");
    header("Location: topics.php");
    die();
}


?>