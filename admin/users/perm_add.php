<?

session_start();

$requiredLogin = true;
$page = "users";

require '../inc/user_check.php';
require '../../inc/db.inc.php';

if (isset($_POST['createPerm'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $display = mysqli_real_escape_string($conn, $_POST['display']);
    $perm_all = 0;
    $perm_postthread = 0;
    $perm_editthreads = 0;
    $perm_pin = 0;
    $perm_close = 0;
    $perm_deleteThread = 0;
    $perm_edit_thread_others = 0;
    $perm_admin = 0;
    //$perm_ = 0;
    if (isset($_POST['perm_all'])) {$perm_all = 1;}
    if (isset($_POST['perm_postthread'])) {$perm_postthread = 1;}
    if (isset($_POST['perm_editthreads'])) {$perm_editthreads = 1;}
    if (isset($_POST['perm_pin'])) {$perm_pin = 1;}
    if (isset($_POST['perm_close'])) {$perm_close = 1;}
    if (isset($_POST['perm_deleteThread'])) {$perm_deleteThread = 1;}
    if (isset($_POST['perm_edit_thread_others'])) {$perm_edit_thread_others = 1;}
    if (isset($_POST['perm_admin'])) {$perm_admin = 1;}

    $check = mysqli_query($conn, "SELECT * FROM permissions WHERE perm_name='$name'");
    if (mysqli_num_rows($check) == "0") {
        
        $insertSQL = "INSERT INTO permissions(
            perm_name,
            perm_display,
            perm_label,
            perm_all,
            perm_postthread,
            perm_editthreads,
            perm_pin,
            perm_close,
            perm_deleteThread,
            perm_edit_thread_others,
            perm_admin) VALUES(
                '$name', '$display', '$label',
                '$perm_all',
                '$perm_postthread',
                '$perm_editthreads',
                '$perm_pin',
                '$perm_close',
                '$perm_deleteThread',
                '$perm_edit_thread_others',
                '$perm_admin')";
        $check = mysqli_query($conn, $insertSQL);
        if ($check) {
            $_SESSION['toast'] = array("success", "Group Created!", "Permission group has been created successfully!");
        } else {
            $_SESSOION['toast'] = array("error", "Group Creation Error!", "Error: ".  mysqli_error($conn));
        }
        header("Location: perm_list.php");
        die();

    } else {
        $_SESSION['toast'] = array("warn", "Permissions Already Exist!", "A permission group with that name already exist!");
        header("Location: perm_add.php");
        die();
    }

}





?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Permissions - Admin Dashboard</title>
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
                    <a href="perm_list.php" class="btn btn-primary mb-3">Go Back</a>
                    <form method="post">
                        <p class="fs-4 text-primary fw-bold mb-2">Permission:</p>
                        <div class="form-outline mb-4">
                            <input type="text" id="name" name="name" class="form-control" />
                            <label class="form-label" for="name">Permission Name</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input type="text" id="label" name="label" class="form-control" />
                            <label class="form-label" for="label">Permission Color</label>
                        </div>
                        <div class="form-outline mb-4">
                            <input type="text" id="display" name="display" class="form-control" />
                            <label class="form-label" for="display">Permission Display</label>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_all" name="perm_all"/>
                                    <label class="form-check-label" for="perm_all">perm_all</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_editthreads" name="perm_editthreads" >
                                    <label class="form-check-label" for="perm_editthreads">perm_editthreads</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_close" name="perm_close" />
                                    <label class="form-check-label" for="perm_close">perm_close</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_edit_thread_others" name="perm_edit_thread_others" />
                                    <label class="form-check-label" for="perm_edit_thread_others">perm_edit_thread_others</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_postthread" name="perm_postthread" />
                                    <label class="form-check-label" for="perm_postthread">perm_postthread</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_pin" name="perm_pin"/>
                                    <label class="form-check-label" for="perm_pin">perm_pin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_deleteThread" name="perm_deleteThread" />
                                    <label class="form-check-label" for="perm_deleteThread">perm_deleteThread</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="perm_admin" name="perm_admin" />
                                    <label class="form-check-label" for="perm_admin">perm_admin</label>
                                </div>
                            </div>
                            <button type="submit" name="createPerm" class="btn btn-primary btn-block mt-3">Create Permission Group</button>
                            
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
