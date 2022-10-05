<?php


session_start();

$validRequest = FALSE;
require '../AppSettings.php';

if (isset($_POST['submitChange'])) {

    require 'db.inc.php';

    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if ($password == $cpassword) {

        $userID = $_SESSION['PASSWORD_RESET_ID'];
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
        $newUserToken = generateRandomHash();

        date_default_timezone_set("America/Chicago");
        $date = date('Y-m-d H:i:s');

        $updateSQL = mysqli_query($conn, "UPDATE users SET u_password='$passwordHashed', u_token='$newUserToken' WHERE u_id='$userID'");
        if ($updateSQL) {

            $logDetail = "User password was reset";
            $insertLog = mysqli_query($conn, "INSERT INTO userlogs('log_desc', 'log_date', 'log_user') VALUES('$logDetail', '$date', '$userID')");
            $passwordMessage = "Hello " . $_SESSION['u_username'] . ",<br>Your password was reset on <b>" . $date . "</b>";
            $sendMessage = mysqli_query($conn, "INSERT into messages(ms_sendby, ms_viewed, ms_title, ms_content, ms_user, ms_date) 
            VALUES('0', '0', 'Password has been reset!', '$passwordMessage', '$userID', '$date')");

            $_SESSION['toast'] = array("success", "Password Reset", "You're password has been successfully reset!");
            header("Location: ../login.php");
            die();
        } else {
            $_SESSION['toast'] = array("error", "Password Error", "Something went wrong!");
            header("Location: ../login.php");
            die();
        }

    } else {

        $_SESSION['toast'] = array('warn', "Password Error", "The passwords you've entered do not match!");
        header("Location: " . $_SESSION['PASSWORD_RESET_LINK']);
        die();

    }
}


if (isset($_GET['user']) && isset($_GET['email']) && isset($_GET['token'])) {

    require 'db.inc.php';
    $userID = mysqli_real_escape_string($conn, $_GET['user']);
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $token = mysqli_real_escape_string($conn, $_GET['token']);


    $requestSQL = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userID' AND u_email='$email'");
    if (mysqli_num_rows($requestSQL) == 1) {
        if ($user = mysqli_fetch_assoc($requestSQL)) {

            if ($user['u_token'] == $token) {
                $validRequest = true;
                $_SESSION['PASSWORD_RESET_ID'] = $userID;
                $_SESSION['PASSWORD_RESET_LINK'] = "passwordreset.php?user=" . $userID . "&email=".$email . "&token=" . $token;

            } else {
                $_SESSION['toast'] = array('warn', "Invalid Request", "The request you've submitted is invalid!");
                header("Location: ../login.php");
                die();
            }

        }

    } else {

        $_SESSION['toast'] = array('warn', "Invalid Request", "The request you've submitted is invalid!");
        header("Location: ../login.php");
        die();
    }

} else {

    $_SESSION['toast'] = array('warn', "Invalid Request", "The request you've submitted is invalid!");
    header("Location: ../login.php");
    die();

}

function generateRandomHash() {
    $lenghOfString = 16;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($lenghOfString);
    $randomString = '';
    for ($i = 0; $i < $lenghOfString; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return md5($randomString);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Password Reset - <? echo $_SITE_TITLE_ ?></title>
    <!-- MDB icon -->
    <link rel="icon" href="../img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB ESSENTIAL -->
    <link rel="stylesheet" href="../css/mdb.min.css" />
    <!-- MDB PLUGINS -->
    <link rel="stylesheet" href="../plugins/css/all.min.css" />
    <link rel="stylesheet" href="../css/style.css"/>
    <!-- Custom styles -->
    <style></style>
  </head>

  <body>
    <!-- Start your project here-->
    <div class="container" style="width: 100%;">

    </div>

    <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <form method="POST">
                            <h2 class="fw-bold mb-4 text-uppercase text-primary">Password Reset</h2>
                            <div class="form-outline my-2">
                                <input type="password" id="password" name="password" class="form-control form-control-lg" required/>
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="form-outline my-2">
                                <input type="password" id="cpassword" name="cpassword" class="form-control form-control-lg" required/>
                                <label class="form-label" for="cpassword">Confirm Password</label>
                            </div>
                            <button class="btn btn-primary btn-lg px-5" type="submit" name="submitChange">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- End your project here-->
  </body>

  <!-- MDB ESSENTIAL -->
  <script type="text/javascript" src="../js/mdb.min.js"></script>
  <!-- MDB PLUGINS -->
  <script type="text/javascript" src="../plugins/js/all.min.js"></script>
  <!-- Custom scripts -->
  <script type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="../js/toast.js"> </script>
</html>
