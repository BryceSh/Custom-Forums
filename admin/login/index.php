<?php

session_start();

$requiredLogin = false;

require '../inc/user_check.php';

if (isset($_POST['loginSubmit'])) {

    require '../../inc/db.inc.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE u_username='$username'");
    if (mysqli_num_rows($check) > 0) {

        if ($user = mysqli_fetch_assoc($check)) {

            if (password_verify($password, $user['u_password'])) {

                $userPermission = $user['u_permission'];
                $perm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissions WHERE perm_id='$userPermission'"));
                if ($perm['perm_all'] == "1" || $perm['perm_admin']== "1") {

                    $_SESSION['admin_id'] = $user['u_id'];
                    $_SESSION['admin_username'] = $user['u_username'];
                    $_SESSION['admin_loggedin'] = "1";
                    header("Location: ../dash");
                    die();

                } else {
                    $_SESSION['toast'] = array("error", "Invalid Credentials", "You do not have permission to access this page!");
                }

            } else {

                $_SESSION['toast'] = array("error", "Invalid Credentials", "You do not have permission to access this page!");

            }

        }

    } else {

        $_SESSION['toast'] = array("error", "Invalid Credentials", "You do not have permission to access this page!");

    }

}


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Login - Admin Dashboard</title>
    <!-- MDB icon -->
    <link rel="icon" href="../../img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB ESSENTIAL -->
    <link rel="stylesheet" href="../../css/mdb.min.css" />
    <!-- MDB PLUGINS -->
    <link rel="stylesheet" href="../../plugins/css/all.min.css" />
    <link rel="stylesheet" href="../../css/style.css"/>
    <!-- Custom styles -->
    <style></style>
  </head>

  <body>
    <!-- Start your project here-->
    <?
        require '../inc/toast.php';
    ?>
    <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <form method="POST">
                            <h2 class="fw-bold mb-4 text-uppercase text-primary">Login</h2>
                            <h4 class="text-danger">Admin Dashboard</h4>
                            <div class="form-outline my-2">
                                <input type="text" id="typeUsername" name ="username" class="form-control form-control-lg" required/>
                                <label class="form-label" for="typeUsername">Username</label>
                            </div>
                            <div class="form-outline my-2">
                                <input type="password" id="typePassword" name ="password" class="form-control form-control-lg" required/>
                                <label class="form-label" for="typePassword">Password</label>
                            </div>
                            <button class="btn btn-primary btn-lg px-5" type="submit" name="loginSubmit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- End your project here-->
  </body>

  <!-- MDB ESSENTIAL -->
  <script type="text/javascript" src="../../js/mdb.min.js"></script>
  <!-- MDB PLUGINS -->
  <script type="text/javascript" src="../../plugins/js/all.min.js"></script>
  <!-- Custom scripts -->
  <script type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="../../js/toast.js"> </script>
</html>
