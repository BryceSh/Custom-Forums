<?php

session_start();

if (isset($_GET['userID']) && isset($_GET['token'])) {

    $subUserID = $_GET['userID'];
    $subToken = $_GET['token'];

    require 'db.inc.php';

    $getUser = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$subUserID'");
    if (mysqli_num_rows($getUser) == "1") {

        $user = mysqli_fetch_assoc($getUser);
        if($subToken == $user['u_token']) {

            $_SESSION['u_emailverified'] = "1";
            $updateSQL = mysqli_query($conn, "UPDATE users SET u_emailverified='1' WHERE u_id='$subUserID'");

        } else {

            header("Location: ../index.php");
            die();

        }

    } else {

        header("Location: ../index.php");
        die();
    }


} else {

    header("Location: ../index.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verified!</title>
</head>
<body style="text-align: center;">
    <h2>Email verified!</h2>
    <p>Your email address has been verified successfully!</p>
    <p>You'll be redirected in 5 seconds...</p>
    <script>
         setTimeout(function(){
            window.location.href = '../profile.php';
         }, 5000);
      </script>
</body>
</html>