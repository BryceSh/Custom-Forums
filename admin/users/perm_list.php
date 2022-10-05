<?

session_start();

$requiredLogin = true;
$page = "users";

require '../inc/user_check.php';

if (isset($_GET['reload'])) {

    $_SESSION['toast'] = array("success", "Reloaded", "Data has been reloaded successfully!");

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
        <div class="container shadow-3">
            <a class="mt-2 mb-4 btn btn-primary" href="perm_add.php"><i class="fas fa-plus-square me-2"></i>Add Permission Group</a>

    <div  class="datatable">
        <table>
            <thead>
            <tr>
                <th class="th-sm">Permission Name</th>
                <th class="th-sm">Action</th>
            </tr>
            </thead>
            <tbody>

            <?
            
            require '../../inc/db.inc.php';

            $getPerms = mysqli_query($conn, "SELECT * FROM permissions");
            while ($perm = $getPerms -> fetch_assoc())  {

                echo '<tr>';
                echo '<td>' . $perm['perm_name'] . '</td>';
                echo '<td><a href="perm_view.php?id='.$perm['perm_id'].'" class="btn btn-primary"><i class="fas fa-pen-square"></i></a></td>';
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
