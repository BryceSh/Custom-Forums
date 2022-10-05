<?

session_start();

$requiredLogin = true;
$page = "users";

require '../inc/user_check.php';
require '../../inc/db.inc.php';

date_default_timezone_set("America/Chicago");
$date = date('Y-m-d H:i:s');

if (isset($_POST['updateAvatar'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $avatarLink = mysqli_real_escape_string($conn, $_POST['avatar']);
    $updateSQL = mysqli_query($conn, "UPDATE users SET u_avatar='$avatarLink' WHERE u_id='$userId'");
    if (!$updateSQL) {
        $_SESSION['toast'] = array("error", "Something went wrong", "Updating your avatar ran into an issue...<br/>" . mysqli_error($conn));
        header("Location: user_view.php?user=" .  $userId);
        die();

    } else {
        $_SESSION['toast'] = array("success", "Avatar Updated!", "Your avatar was successfully updated!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_POST['updatePassword'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if ($password == $cpassword) {

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $updateSQL = mysqli_query($conn, "UPDATE users SET u_password='$passwordHashed', u_forcelogout='1' WHERE u_id='$userId'");
        if (!$updateSQL) {
            $_SESSION['toast'] = array("error", "Something went wrong", "Updating password ran into an issue...<br/>" . mysqli_error($conn));
            header("Location: user_view.php?user=" .  $userId);
            die();
    
        } else {
            $_SESSION['toast'] = array("success", "Password Updated!", "User's password was successfully updated!");
            header("Location: user_view.php?user=" .  $userId);
            die();
        }

    } else {
        $_SESSION['toast'] = array("warn", "Password Error!", "Unable to update your password. Passwords do not match!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_POST['updateRank'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $permID = mysqli_real_escape_string($conn, $_POST['permRank']);
    $updateSQL = mysqli_query($conn, "UPDATE users SET u_permission='$permID' WHERE u_id='$userId'");
    if (!$updateSQL) {
        $_SESSION['toast'] = array("error", "Something went wrong", "Updating your avatar ran into an issue...<br/>" . mysqli_error($conn));
        header("Location: user_view.php?user=" .  $userId);
        die();

    } else {
        $_SESSION['toast'] = array("success", "Permissions Updated!", "Your permission was successfully updated!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_POST['updateBio'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $permID = mysqli_real_escape_string($conn, $_POST['bio']);
    $updateSQL = mysqli_query($conn, "UPDATE users SET u_bio='$permID' WHERE u_id='$userId'");
    if (!$updateSQL) {
        $_SESSION['toast'] = array("error", "Something went wrong", "Updating your bio ran into an issue...<br/>" . mysqli_error($conn));
        header("Location: user_view.php?user=" .  $userId);
        die();

    } else {
        $_SESSION['toast'] = array("success", "Bio Updated!", "Your bio was successfully updated!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_POST['updateEmail'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $emailAddress = mysqli_real_escape_string($conn, $_POST['email']);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE u_email='$emailAddress'")) != "0") {
        $_SESSION['toast'] = array("error", "Email Invalid", "A user with that email already exist!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }
    $updateSQL = mysqli_query($conn, "UPDATE users SET u_email='$emailAddress', u_emailverified='0' WHERE u_id='$userId'");
    if (!$updateSQL) {
        $_SESSION['toast'] = array("error", "Something went wrong", "Updating your email ran into an issue...<br/>" . mysqli_error($conn));
        header("Location: user_view.php?user=" .  $userId);
        die();

    } else {
        $_SESSION['toast'] = array("success", "Email Updated!", "Email was updated! User will need to re-verify email address");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_POST['warnUser'])) {

    $userId = $_SESSION['CURRENT_EDIT_USER'];
    $warnMessage = mysqli_real_escape_string($conn, $_POST['warnMessage']);
    $warnTitle = mysqli_real_escape_string($conn, $_POST['warnTitle']);
    $currentUser = $_SESSION['admin_id'];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userId'"));

    $warnMessageX = "Hello " . $user['u_username'] . "<br><br>You have been warned by an administrator!<br><br><b>Reason:</b> " . $warnMessage . "<br><br><b>Warned By:</b> " . $_SESSION['admin_username'] . "
    <br><b>Warn Date:</b> " . $date;

    $insertQuery = mysqli_query($conn, "INSERT INTO warnings (warn_by, warn_title, warn_message, warn_date, warn_user) VALUES('$currentUser', '$warnTitle', '$warnMessage', '$date', '$userId')");
    $sendMessage = mysqli_query($conn, "INSERT INTO messages (ms_sendby, ms_title, ms_content, ms_user, ms_date, ms_important) VALUES('0', 'You have been warned!', '$warnMessageX', '$userId', '$date', '1')");
    if (!$sendMessage || !$insertQuery) {
        $_SESSION['toast'] = array("error", "Something went wrong", "Something happened while warning user<br/>" . mysqli_error($conn));
        header("Location: user_view.php?user=" .  $userId);
        die();

    } else {
        $_SESSION['toast'] = array("success", "User warned!", "User has been warned successfully!");
        header("Location: user_view.php?user=" .  $userId);
        die();
    }

}

if (isset($_GET['user'])) {

    $userId = $_GET['user'];
    $getUser = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userId'");

    $_SESSION['CURRENT_EDIT_USER'] = $userId;

    if (mysqli_num_rows($getUser) == "1") {

        $user = mysqli_fetch_assoc($getUser);
        $userPermID = $user['u_permission'];
        $perm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissions WHERE perm_id='$userPermID'"));

        if (isset($_GET['email_option'])) {

            $emailOption = mysqli_real_escape_string($conn, $_GET['email_option']);
            mysqli_query($conn, "UPDATE users SET u_showemail='$emailOption' WHERE u_id='$userId'");
            header("Location: user_view.php?user=" .  $userId);
            die();

        }

        if (isset($_GET['force_email_verify'])) {

            mysqli_query($conn, "UPDATE users SET u_emailverified='1' WHERE u_id='$userId'");
            header("Location: user_view.php?user=" .  $userId);
            die();

        }

        if (isset($_GET['force_logout'])) {

            mysqli_query($conn, "UPDATE users SET u_forcelogout='1' WHERE u_id='$userId'");
            header("Location: user_view.php?user=" .  $userId);
            die();

        }

    } else {
        $_SESSION['toast'] = array("warn", "User not found", "The user with the ID " . $userId . " does not exsist!");
        header("Location: user_list.php");
        die();
    }

} else {

    $_SESSION['toast'] = array("warn", "Invalid Request", "The link you've provided is invalid!");
    header("Location: user_list.php");
    die();

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

    <!-- This is the modals  -->

    <div class="modal fade" id="updateAvatar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Avatar</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="user_view.php?user=<? echo $userId; ?>">
                        <div class="form-outline my-4">
                            <input type="text" id="avatar" name="avatar" class="form-control" required/>
                            <label class="form-label" for="avatar">Avatar Image Link</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="updateAvatar"><i class="fas fa-check-square me-2"></i>Update Avatar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Email</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="user_view.php?user=<? echo $userId; ?>">
                        <div class="form-outline my-4">
                            <input type="email" id="email" name="email" class="form-control" required/>
                            <label class="form-label" for="email">Email Address</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="updateEmail"><i class="fas fa-check-square me-2"></i>Update Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateBio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Bio</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;">
                        <div class="form-outline my-4">
                            <input type="text" data-mdb-showcounter="true" maxlength="30" id="bio" name="bio" class="form-control" value="<? echo $user['u_bio']; ?>" required/>
                            <label class="form-label" for="bio">Enter Bio</label>
                            <div class="form-helper"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="updateBio"><i class="fas fa-check-square me-2"></i>Update Bio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updatePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Password</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="user_view.php?user=<? echo $userId; ?>">
                        <div class="form-outline my-4">
                            <input type="password" id="password" name="password" class="form-control" required/>
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="form-outline my-4">
                            <input type="password" id="cpassword" name="cpassword" class="form-control" required/>
                            <label class="form-label" for="cpassword">Confirm Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="updatePassword"><i class="fas fa-check-square me-2"></i>Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warnUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Warn User</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="user_view.php?user=<? echo $userId; ?>">
                        <div class="form-outline my-4">
                            <input type="text" id="warnTitle" name="warnTitle" class="form-control" required/>
                            <label class="form-label" for="warnTitle">Warning Title</label>
                        </div>
                        <div class="form-outline mb-4">
                            <textarea class="form-control" id="warnMessage" name="warnMessage" rows="4"></textarea>
                            <label class="form-label" for="warnMessage">Warn Message</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="warnUser"><i class="fas fa-check-square me-2"></i>Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateRank" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Rank</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="user_view.php?user=<? echo $userId; ?>">
                        <select class="select" name="permRank">
                        <?
                            $permSelects = mysqli_query($conn, "SELECT * FROM permissions");
                            while ($permList = $permSelects -> fetch_assoc()) {
                                if ($permList['perm_id'] == $perm['perm_id']) {
                                    echo '<option value="'.$permList['perm_id'].'" selected>' . $permList['perm_name'] . '</option>';
                                } else {
                                    echo '<option value="'.$permList['perm_id'].'">' . $permList['perm_name'] . '</option>';
                                }
                                
                            }
                        ?>
                        </select>
                        <label class="form-label select-label">Select Rank</label>
                        <div class="text-center">
                            <a href="#" class="btn btn-primary my-2"><i class="fas fa-arrow-left me-2"></i>Go to permissions page</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3" name="updateRank"><i class="fas fa-check-square me-2"></i>Update Rank</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Beginning of the profile content -->
    <div class="container py-5">
        <a href="user_list.php" class="btn btn-primary mb-2"><i class="fas fa-arrow-circle-left me-2"></i>Go back to user list</a>
        <div class="row">
            <div class="col-md-4 my-2">
                <div class="container shadow-3 profile-left py-3 text-center">
                    <!-- This is the left side of the user page -->
                    <? 
                        if ($user['u_avatar'] == "img/default_user.png") {
                            echo '<img src="../../img/default_user.png"/>';
                        } else {
                            echo '<img src="'.$user['u_avatar'].'"/>';
                        }?><br/>
                    <a href="#" class="btn btn-rounded btn-primary" data-mdb-toggle="modal" data-mdb-target="#updateAvatar">Change Avatar</a>
                    <p class="my-2"><? echo $user['u_username']; ?></p>
                    <p class="text-uppercase" style="color: <? echo $perm['perm_label']; ?>; border: 1px solid <? echo $perm['perm_label']; ?>; border-radius: 1px;"><? echo $perm['perm_name']; ?></p>
                    <ul class="list-group list-group-light mt-2">
                        <?
                            $threadCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM threads WHERE th_postedby='$userId'"));
                            $replyCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comments WHERE cm_posted='$userId'"));
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Threads
                            <span class="badge badge-primary rounded-pill"><? echo $threadCount; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Replys
                            <span class="badge badge-primary rounded-pill"><? echo $replyCount; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Reputation
                            <span class="badge badge-primary rounded-pill"><? echo $user['u_rep']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Warnings
                            <?

                                $warnCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM warnings WHERE warn_user='$userId'"));

                            ?>
                            <span class="badge badge-primary rounded-pill"><? echo $warnCount; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8 my-2">
                <!-- Pills navs -->
                <ul class="nav nav-pills mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a
                        class="nav-link active"
                        id="account-tab"
                        data-mdb-toggle="pill"
                        href="#account"
                        role="tab"
                        aria-controls="account"
                        aria-selected="true"
                        >Account Information</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                        class="nav-link"
                        id="ex1-tab-3"
                        data-mdb-toggle="pill"
                        href="#threads-pill"
                        role="tab"
                        aria-controls="threads-pill"
                        aria-selected="false"
                        >Threads</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                        class="nav-link"
                        id="ex1-tab-2"
                        data-mdb-toggle="pill"
                        href="#ex1-pills-2"
                        role="tab"
                        aria-controls="ex1-pills-2"
                        aria-selected="false"
                        >Warns</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                        class="nav-link"
                        id="ex1-tab-3"
                        data-mdb-toggle="pill"
                        href="#ex1-pills-3"
                        role="tab"
                        aria-controls="ex1-pills-3"
                        aria-selected="false"
                        >Logs</a
                        >
                    </li>
                    </ul>
                    <!-- Pills navs -->

                    <!-- Pills content -->
                    <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account">
                        <div class="container shadow-3 py-3">
                            <p class="fs-5">Email Information:</p>
                            <p>Current Email: <span class="text-muted"><? echo $user['u_email']; ?><a data-mdb-toggle="modal" data-mdb-target="#updateEmail"><i class="ms-2 fas fa-pencil-alt"></i></a></span></p>
                            <p>Email verified:
                                <?
                                    if ($user['u_emailverified'] == "1") {
                                        echo '<span class="text-success">Verified</span>';
                                    } else {
                                        echo '<a class="btn btn-danger btn-sm mx-1" href="user_view.php?user='.$userId.'&force_email_verify">Force Verify</a>';
                                    }
                                ?>
                            </p>
                            <p>Email Hidden: 
                                <?
                                if ($user['u_showemail'] == "0") {
                                    echo '<span class="text-danger">Hidden</span> <a href="user_view.php?user='.$userId.'&email_option=1" class="btn btn-primary btn-sm">Show</a>';
                                } else {
                                    echo '<span class="text-success">Visible</span> <a href="user_view.php?user='.$userId.'&email_option=0" class="btn btn-primary btn-sm">Hide</a>';
                                }
                                ?>
                            </p>
                            <hr class="hr" />
                            <p class="fs-5">Account Information:</p>
                            <p>Account Verified: <?
                                if ($user['u_verified'] == "1") {
                                    echo '<span class="text-success">Verified Account<i class="fas fa-check ms-2"></i></span>';
                                } else {
                                    echo 'Not verified';
                                }
                            ?></p>
                            <p>Registration Date: <span class="text-muted"><? echo $user['u_regdate']; ?></span></p>
                            <p>Last Login: <span class="text-muted"><? echo $user['u_lastlogin']; ?></span></p>
                            <p>User's Bio: <span class="text-muted"><? echo $user['u_bio']; ?></span><a data-mdb-toggle="modal" data-mdb-target="#updateBio"><i class="ms-2 fas fa-pencil-alt"></i></a></p>
                            <hr class="hr" />
                            <p class="fs-5">Permissions</p>
                            <p>Current Rank: <span style="color: <? echo $perm['perm_label'];?>"><? echo $perm['perm_name']; ?></span><a data-mdb-toggle="modal" data-mdb-target="#updateRank""><i class="ms-2 fas fa-pencil-alt"></i></a></p>
                            <hr class="hr" />
                            <p class="fs-5">Options:</p>
                            <a href="#" class="btn btn-success m-1"><i class="fas fa-sign-in-alt me-2"></i>Force Password Change</a>
                            <a data-mdb-toggle="modal" data-mdb-target="#updatePassword" class="btn btn-primary m-1"><i class="fas fa-key me-2"></i>Change Password</a>
                            <a data-mdb-toggle="modal" data-mdb-target="#warnUser" class="btn btn-danger m-1"><i class="fas fa-exclamation-circle me-2"></i>Warn User</a>
                            <a href="#" class="btn btn-danger m-1"><i class="fas fa-ban me-2"></i>Ban User</a>
                            <?
                                if ($user['u_forcelogout'] == "1") {
                                    echo '<a class="btn btn-warning m-1" disabled><i class="far fa-times-circle me-2"></i>Force Logout</a>';
                                } else {
                                    echo '<a href="user_view.php?user='.$userId.'&force_logout" class="btn btn-warning m-1"><i class="far fa-times-circle me-2"></i>Force Logout</a>';
                                }
                            ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-pills-2">
                            <div class="container shadow-3 text-center py-2">
                                <p class="fs-5">Information:</p>
                                <p>Total Warnings: <span class="text-muted"><? echo $warnCount; ?></span></p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="threads-pill" role="tabpanel" aria-labelledby="threads-pill">
                            <div class="container shadow-3">
                            <div  class="datatable">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="th-sm">Thread</th>
                                        <th class="th-sm">Posted On</th>
                                        <th ckass="th-sm">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?
                                    
                                    $getThreads = mysqli_query($conn, "SELECT * FROM threads WHERE th_postedby='$userId' ORDER BY th_date DESC");
                                    while ($thread = $getThreads -> fetch_assoc()) {

                                        $viewLink = "thread.php?thread=" . $thread['th_id'];
                                        echo '<tr>';
                                        echo '<td>' . $thread['th_subject'] . '</td>';
                                        echo '<td>' . $thread['th_date'] . '</td>';
                                        echo '<td><a href="'.$viewLink.'" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>';
                                        echo '</tr>';

                                    }

                                    ?>
                                    
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ex1-pills-3" role="tabpanel" aria-labelledby="ex1-pills-3">
                            <div class="container shadow-3 text-center py-2">
                                <div  class="datatable">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Log ID</th>
                                            <th style="width: 60%;">Log Details</th>
                                            <th class="th-sm">Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?
                                                $getLog = mysqli_query($conn, "SELECT * FROM userlogs WHERE log_user='$userId' ORDER BY log_date DESC");
                                                while ($log = $getLog -> fetch_assoc()) {
                                                    
                                                    
                                                    echo '<tr>';
                                                    echo '<td>' . $log['log_id'] . '</td>';
                                                    echo '<td>' . $log['log_desc'] . '</td>';
                                                    echo '<td>' . $log['log_date'] . '</td>';
                                                    echo '</tr>';
                
                                                }
                                            ?>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
