<?php

session_start();
if (isset($_POST['sendMessage'])) {

    require 'db.inc.php';

    $sendMessageUser = $_SESSION['MESSAGE_USER'];
    $message = mysqli_real_escape_string($conn, $_POST['editor']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);

    date_default_timezone_set("America/Chicago");
    $date = date('Y-m-d H:i:s');

    if ($message == "" || $subject == "") {
        $_SESSION['toast'] = array("error", "Empty Field", "Please fill in all information");
        header("Location: ../message.php?user=" . $sendMessageUser);
        die();
    }

    $curUser = $_SESSION['u_id'];

    $sqlStatment = "INSERT INTO messages(ms_sendby, ms_user, ms_viewed, ms_senderview, ms_main, ms_parent, ms_title, ms_content, ms_date, ms_updated) 
    VALUES('$curUser', '$sendMessageUser', '0', '1', '1', '0', '$subject', '$message', '$date', '$date')";
    $insertResult = mysqli_query($conn, $sqlStatment);
    if ($insertResult) {
        $_SESSION['toast'] = array("success", "Message Sent", "Message has been sent successfully!");
        header("Location: ../index.php");
        die();
    } else {
        $_SESSION['toast'] = array("error", "Something happened", "Something wrong happened:<br>" . mysqli_error($conn));
        header("Location: ../message.php?user=" . $sendMessageUser);
        die();
    }

} else {

    echo "Something went wrong!";

}


?>