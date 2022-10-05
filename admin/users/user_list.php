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

    <div class="modal fade" id="searchUsers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search Users</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;">
                        <div class="form-outline my-4">
                            <input type="text" id="searchInput" name="searchInput" class="form-control" placeholder="Username, ID, Email" required/>
                            <label class="form-label" for="searchInput">Search Users</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="searchUsers"><i class="fas fa-search me-2"></i>Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="container shadow-3">
            <a href="#" class="btn btn-rounded btn-primary my-3" data-mdb-toggle="modal" data-mdb-target="#searchUsers"><i class="fas fa-search me-2"></i>Search Users</a>
            <a href="user_list.php?reload" class="btn btn-rounded btn-primary my-3"><i class="fas fa-sync me-2"></i>Reload Data</a>
    <div  class="datatable">
        <table>
            <thead>
            <tr>
                <th class="th-sm">Username</th>
                <th class="th-sm">Permission</th>
                <th class="th-sm">Last Login</th>
                <th class="th-sm">Action</th>
            </tr>
            </thead>
            <tbody>

            <?
            
            require '../../inc/db.inc.php';

            $sql = "SELECT * FROM users ORDER by u_regdate";
            if (isset($_POST['searchUsers']) && isset($_POST['searchInput'])) {
                $searchInput = $_POST['searchInput'];
                $sql = "SELECT * FROM users WHERE u_username LIKE '%$searchInput%' OR u_email LIKE '%$searchInput%' OR u_id LIKE '%$searchInput%' ORDER by u_regdate";
            }
            if (isset($_GET['options'])) {
                if ($_GET['options'] == "banned") {
                    $sql = "SELECT * FROM users WHERE u_banned='1' ORDER by u_regdate";
                }
            }

            $getUsers = mysqli_query($conn, $sql);
            while ($user = $getUsers -> fetch_assoc()) {

                $userPerm = $user['u_permission'];
                $perm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permissions WHERE perm_id='$userPerm'"));

                echo '<tr>';
                echo '<td>' . $user['u_username'] . '</td>';
                echo '<td>' . $perm['perm_name'] . '</td>';
                echo '<td>'. $user['u_lastlogin'] .'</td>';
                echo '<td><a href="user_view.php?user='.$user['u_id'].'" class="btn btn-rounded btn-primary"><i class="fas fa-eye"></i></a></td>';
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
