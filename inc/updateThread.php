<?

session_start();
if (isset($_POST['updateThread'])) {

    require 'db.inc.php';

    $threadID = $_SESSION['editThread'];

    $content = mysqli_real_escape_string($conn, $_POST['editor']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    date_default_timezone_set("America/Chicago");
    $date = date('Y-m-d H:i:s');

    $updateSQL = "UPDATE threads SET th_subject='$subject', th_content='$content', th_lastupdate='$date' WHERE th_id='$threadID'";
    $updateResult = mysqli_query($conn, $updateSQL);
    if (!$updateResult) {
        echo "An error has occured!<br/>Error: " . mysqli_error($conn) . "<br/>Please reload the page";
        die();
    } else {

        $_SESSION['toast'] = array("success", "Thread Updated", "Thread has been successfully updated!");

        header("Location: ../thread.php?thread=" . $threadID);

        $_SESSION['editThread'] = "";

        die();

    }

}

?>