<?

session_start();

$requiredLogin = true;
$page = "reports";

require '../inc/user_check.php';
require '../../inc/db.inc.php';


if (isset($_GET['report'])) {

    $reportId = $_GET['report'];
    $getReport = mysqli_query($conn, "SELECT * FROM reports WHERE r_id='$reportId'");
    if (mysqli_num_rows($getReport) == "1") {

        $report = mysqli_fetch_assoc($getReport);
        $_SESSION["REPORT_ID"] = $report['r_id'];

        if (isset($_POST['submitClose'])) {

            $staffComment = mysqli_real_escape_string($conn, $_POST['notes']);

            $update = mysqli_query($conn, "UPDATE reports SET r_status='3', r_staffcomment='$staffComment' WHERE r_id='$reportId'");
            if ($update) {
                $_SESSION['toast'] = array("success", "Report Closed", "This report has been closed successfully!");
            } else {
                $_SESSION['toast'] = array("error", "Error", "There was an error tying to close this report. Please try again!");
            }
            header("Location: view_report.php?report=" . $reportId);
            die();
        }

    } else {

        header("Location: ../reports");
        die();

    }

} else {
    header("Location: ../reports");
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

    <div class="modal fade" id="closeReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hold on!</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Please enter a conclusion for closing this report!</p>
                    <form method="post" style="margin-top: 15px;">
                        <div class="form-outline my-4">
                            <input type="text" id="notes" name="notes" class="form-control" required/>
                            <label class="form-label" for="notes">Status Notes</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="submitClose"><i class="fas fa-check-square me-2"></i>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 shadow-3 p-4">
                <a href="../reports" class="btn btn-primary btn-rounded"><i class="fas fa-long-arrow-alt-left me-2"></i> Back to reports</a>
                <p class="fw-bold mb-4 text-uppercase text-center text-primary fs-3">View Report</p>
                <p><b>Report Status:</b>
                <?
                    if ($report['r_status'] == "1") {
                        echo '<span class="badge badge-success">Open</span>';
                    } else if ($report['r_status'] == "2") {
                        echo '<span class="badge badge-warning">Pending Review</span>';
                    } else if ($report['r_status'] == "3") {
                        echo '<span class="badge badge-danger">Closed</span>';
                    }
                ?>
                </p>
                <p><b>Report Type:</b> <?
                    if ($report['r_type'] == "1") {
                        echo '<span style="text-muted">Thread Post</span>';
                    } else if ($report['r_type'] == "2") {
                        echo '<span style="text-muted">Reply</span>';
                    } else if ($report['r_type'] == "3") {
                        echo '<span style="text-muted">User Account</span>';
                    }
                ?></p>
                <p><b>Report By:</b> <?
                $userID = $report['r_reportby'];
                $getUser = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userID'");
                if (mysqli_num_rows($getUser) == "1") {
                    $user = mysqli_fetch_assoc($getUser);
                    $usernameLink = '<a target="_BLANK" href="../users/user_view.php?user='. $userID .'">' . $user['u_username'] . '</a>';
                } else {
                    $usernameLink = '<a>N/A</a>';
                }
                echo $usernameLink;
                ?>
                <p><b>Report Reason:</b> <? echo $report['r_reason']; ?></p>
                <p><b>Report Comments:</b> <? echo $report['r_comment'] ?></p>
                <p class="fs-5 text-primary my-2">Report Details:</p>    
            </p>
                <?

                    $reportItem = $report['r_itemid'];

                    if ($report['r_type'] == "1") {

                        $generatedLink = "../../thread.php?thread=" . $reportItem;
                        $thread = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM threads WHERE th_id='$reportItem'"));
                        echo '<p><b>Thread Title:</b> '. $thread['th_subject'] ."</p>";
                        echo '<p><b>Thread Link:</b> <a target="_BLANK" href="'. $generatedLink .'">Click me!</a></p>';

                    } else if ($report['r_type'] == "2") {

                        $reportedReply = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM comments WHERE cm_id='$reportItem'"));
                        $postedUser = $reportedReply['cm_posted'];
                        $replyUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE u_id='$postedUser'"));
                        echo '<div class="border border-1">'. $reportedReply['cm_content'] .'</div>';
                        echo '<p><b>Posted By:</b> <a target="_BLANK" href="../users/user_view.php?user='.$replyUser['u_id'].'">'. $replyUser['u_username'] .'</a></p>';
                        echo '<p class="fs-5 text-primary my-2">Report Options:</p>';
                        echo '<a href="clear_content.php?type=2&id='.$reportItem.'" class="btn btn-danger m-1">Clear Content</a>';
                        echo '<a href="#" class="btn btn-danger m-1">Warn User</a>';
                        echo '<a href="#" class="btn btn-danger m-1">Ban User</a>';

                    } else if ($report['r_type'] == "3") {
                        $reportedAccount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE u_id='$reportItem'"));
                        echo '<p><b>Reported Account:</b> <a target="_BLANK" href="../users/user_view.php?user='. $reportItem .'">'.$reportedAccount['u_username'].'</a></p>';    
                        echo '<p><b>Report Reason:</b> '. $report['r_reason'] .'</p>';
                        echo '<p><b>Report Comment:</b> '. $report['r_comment'] .'</p>';    
                    }
                ?>
        
                <p class="fs-5 text-primary my-2">Report Status:</p>
                <?
                    if ($report['r_status'] == "3") {
                        echo '<p><b>Staff Notes:</b><br/>' . $report['r_staffcomment'] . "</p>";
                    }
                ?>
                <a href="update_status.php?id=<? echo $reportId; ?>&value=1" class="btn btn-success btn-sm m-1">Open</a>
                <a href="update_status.php?id=<? echo $reportId; ?>&value=2" class="btn btn-warning btn-sm m-1">Pending</a>
                <a data-mdb-toggle="modal" data-mdb-target="#closeReport" class="btn btn-danger btn-sm m-1">Close</a>

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
