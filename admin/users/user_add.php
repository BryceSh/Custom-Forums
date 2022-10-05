<?

session_start();

$requiredLogin = true;
$page = "users";

require '../inc/user_check.php';
require '../../inc/db.inc.php';
require '../../inc/info.php';

if (isset($_POST['generateAccount'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $permission = mysqli_real_escape_string($conn, $_POST['permission']);
    $showEmail = 0;
    if (isset($_POST['showEmail'])) {
        $showEmail = 1;
    }

    // Username check
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE u_username='$username'")) == "0") {

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE u_email='$email'")) == "0") {

            if ($password == $cpassword) {

                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                date_default_timezone_set("America/Chicago");
                $date = date('Y-m-d H:i:s');
                $insert = mysqli_query($conn, "INSERT INTO users(u_username, u_password, u_email, u_regdate, u_showemail, u_permission) VALUES('$username', '$passwordHashed', '$email', '$date', '$showEmail', '$permission')");
                if ($insert) {
                    $_SESSION['toast'] = array("success", "User Registered", "User has been successfully registered!");

                    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE u_username='$username'"));

                    header("Location: user_view.php?user=" . $user['u_id']);
                    die();
                } else {
                    $_SESSION['toast'] = array("error", "Registration Error", "Something happened");
                }

            } else {
                $_SESSION['toast'] = array("warn", "Passwords Invalid", "Passwords do not match!");
            }

        } else {
            $_SESSION['toast'] = array("warn", "Email Taken", "A user with that email already exist!");
        }

    } else {
        $_SESSION['toast'] = array("warn", "Username Taken", "A user with that username already exist!");
    }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Dashboard - Admin Dashboard</title>
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
        require '../inc/navbar.php';
        require '../inc/toast.php';
    ?>
    <div class="container py-5">
        <div class="row d-flex justify-content-center gx-4">
            <div class="col-md-8">
                <div class="container shadow-3 p-4">
                    <p class="fs-4 text-primary fw-bold">ADD USER:</p>
                    <form method="POST">
                        <div class="row">
                            <div class="col">
                                <div class="form-outline my-3">
                                    <input type="text" id="username" name="username" class="form-control" required/>
                                    <label class="form-label" for="username">Username</label>
                                </div>
                                <div class="form-outline my-3">
                                    <input type="email" id="email" name="email" class="form-control" required/>
                                    <label class="form-label" for="email">Email</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline my-3">
                                    <input type="password" id="password" name="password" class="form-control" required/>
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="form-outline my-3">
                                    <input type="password" id="cpassword" name="cpassword" class="form-control" required/>
                                    <label class="form-label" for="cpassword">Confirm Password</label>
                                </div>
                            </div>
                            <select class="select" name="permission">
                                <?
                                
                                $options = mysqli_query($conn, "SELECT * FROM permissions");
                                while ($perm = $options -> fetch_assoc()) {
                                    echo '<option value="'. $perm['perm_id'] .'">' . $perm['perm_name'] . "</option>";
                                }
                                
                                ?>
                            </select>
                            <label class="form-label select-label">User Group</label>
                            <div class="form-outline my-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="showEmail" name="showEmail" />
                                    <label class="form-check-label" for="showEmail">Email Visible To Public</label>
                                </div>
                            </div>
                            <button type="submit" name="generateAccount" class="btn btn-primary btn-block mt-3">Generate Account</button>
                        </div>
                    </form>
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
