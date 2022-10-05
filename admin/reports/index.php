<?

session_start();

$requiredLogin = true;
$page = "reports";

require '../inc/user_check.php';
require '../../inc/db.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Reports - Admin Dashboard</title>
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
        
        <div class="container shadow-3 p-3">
            <div class="row d-flex justify-content-center gx-4">
                <div class="col-md-4">
                    <div class="container shadow-3 p-4 bg-primary rounded-7 text-white">
                        <p class="fs-5">Open Reports:</p>
                        <p><?
                        
                        echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reports WHERE r_status='1'"));

                        ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="container shadow-3 p-4 bg-primary rounded-7 text-white">
                        <p class="fs-5">Total Reports:</p>
                        <p><?
                        echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reports"));
                        ?></p>
                    </div>
                </div>
            </div>

        <div  class="datatable mt-4">
        <table>
            <thead>
            <tr>
                <th class="th-sm">Report By</th>
                <th class="th-sm">Status</th>
                <th class="th-sm">Type</th>
                <th class="th-sm">Date</th>
                <th class="th-sm">Action</th>
            </tr>
            </thead>
            <tbody>
            <?
            
            $getReports = mysqli_query($conn, "SELECT * FROM reports ORDER by r_date DESC");
            while ($report = $getReports -> fetch_assoc()) {

                $userID = $report['r_reportby'];
                $getUser = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userID'");
                if (mysqli_num_rows($getUser) == "1") {
                    $user = mysqli_fetch_assoc($getUser);
                    $username = $user['u_username'];
                } else {
                    $username = "N/A";
                }

                if ($report['r_status'] == "1") {
                    $statusText = '<span class="badge badge-success rounded-pill d-inline">Open</span>';
                } else if ($report['r_status'] == "2") {
                    $statusText = '<span class="badge badge-warning rounded-pill d-inline">Pending Review</span>';
                } else if ($report['r_status'] == "3") {
                    $statusText = '<span class="badge badge-danger rounded-pill d-inline">Closed</span>';
                }

                if ($report['r_type'] == "1") {
                    $reportType = "Thread Post";
                } else if ($report['r_type'] == "2") {
                    $reportType = "Reply";
                } else if ($report['r_type'] == "3") {
                    $reportType = "User Account";
                }

                echo '<tr>';
                echo '<td>' . $username . '</td>';
                echo '<td>' . $statusText . '</td>';
                echo '<td>' . $reportType . '</td>';
                echo '<td>' . $report['r_date'] . '</td>';
                echo '<td><a href="view_report.php?report='. $report['r_id'] .'" class="btn btn-primary btn-rounded">View Report</a></td>';
                echo '</tr>';

            }
            
            ?>
            </tbody>
        </table>
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
