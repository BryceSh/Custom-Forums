<?

session_start();

$requiredLogin = true;

require '../inc/user_check.php';
require '../../inc/db.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Settings - Admin Dashboard</title>
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
    <!-- This is all of the modals -->
    <div class="modal fade" id="addOption" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Report Option</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;">
                        <div class="form-outline my-4">
                            <input type="password" id="password" name="password" class="form-control" required/>
                            <label class="form-label" for="password">Report Option Name</label>
                        </div>
                        <div class="form-outline my-4">
                            <input type="password" id="cpassword" name="cpassword" class="form-control" required/>
                            <label class="form-label" for="cpassword">Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="updatePassword"><i class="fas fa-check-square me-2"></i>Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>'
    <!-- Start your project here-->
    <?
        require '../inc/navbar.php';
        require '../inc/toast.php';
    ?>
    <div class="container py-5">
        <div class="container shadow-3">
        <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="report-settings-tab" data-mdb-toggle="tab" href="#report-settings" role="tab" aria-controls="report-settings" aria-selected="false">Reports</a>
            </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">

                <!-- Report TAB -->
                <div class="tab-pane fade p-4 show active" id="report-settings" role="tabpanel" aria-labelledby="ex1-tab-2">
                    <p class="fs-4 text-primary">Report Settings</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="container shadow-3 p-4">
                                <p>Enable / Disable Reporting System</p>
                                <?
                                    $reportStatus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings WHERE settings_name='reports_enabled'"));
                                    echo '<p><b>Current Status:</b> ';
                                    if ($reportStatus['value'] == "1") {
                                        echo '<span style="color: green;">Enabled</span></p>';
                                        echo '<a href="action.php?status=0" class="btn btn-danger">Disable System</a>';
                                    } else {
                                        echo '<span style="color: red;">Disabled</span></p>';
                                        echo '<a href="action.php?status=1" class="btn btn-success">Enable System</a>';
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="container shadow-3 p-4">
                                <p class="fs-5 text-primary">Report Options</p>
                                <a href="#" class="btn btn-success btn-sm"><i class="fas fa-plus-circle me-2"></i>Add Option</a>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th scope="col" style="width: 50%;">Name</th>
                                        <th scope="col">Thread</th>
                                        <th scope="col">Reply</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Comments</th>
                                        <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                            $optionFalse = '<td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>';
                                            $optionTrue = '<td><span class="text-success"><i class="fas fa-check-circle"></i></span></td>';
                                            $getOptions = mysqli_query($conn, "SELECT * FROM report_options ORDER by ro_order ASC");
                                            while ($option = $getOptions -> fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>'.$option['ro_content'].'</td>';
                                                if ($option['ro_threads'] == "1") {
                                                    echo $optionTrue;
                                                } else {
                                                    echo $optionFalse;
                                                }
                                                if ($option['ro_replys'] == "1") {
                                                    echo $optionTrue;
                                                } else {
                                                    echo $optionFalse;
                                                }
                                                if ($option['ro_users'] == "1") {
                                                    echo $optionTrue;
                                                } else {
                                                    echo $optionFalse;
                                                }
                                                if ($option['ro_require_comment'] == "1") {
                                                    echo $optionTrue;
                                                } else {
                                                    echo $optionFalse;
                                                }
                                                echo '<td><a href="#" class="text-secondary"><i class="fas fa-edit"></i></a></td>';                                                
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
