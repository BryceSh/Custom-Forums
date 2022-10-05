<?

session_start();

if (isset($_GET['categoryID']) && isset($_GET['confirmation'])) {

    require '../../inc/db.inc.php';

    $categoryID = $_GET['categoryID'];
    $confirmation = $_GET['confirmation'];

    if ($confirmation == "1") {

        $deleteSql = mysqli_query($conn, "DELETE FROM `categories` WHERE `c_id` = '$categoryID'");
        if ($deleteSql) {
            $_SESSION['toast'] = array("success", "Deleted Successfully!", "Category has been deleted successfully!");
        } else {
            $_SESSION['toast'] = array('error', "Unable to delete", "An error has occured!<br>Error:" . mysqli_error($conn));
        }
        header("Location: categories.php");
        die();

    } else {
        $_SESSION['toast'] = array("error", "Invalid Request", "The request you've submitted is invalid");
        header("Location: categories.php");
        die();
    }

} else {
    $_SESSION['toast'] = array("error", "Invalid Request", "The request you've submitted is invalid");
    header("Location: categories.php");
    die();
}


?>