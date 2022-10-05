<?

session_start();

$requiredLogin = true;
$page = "dash";

require '../inc/user_check.php';
require '../../inc/db.inc.php';
require '../../inc/info.php';

$upToDate = "";
$currentVersion = $_SOFTWARE_VERSION;
$lastestVersion = file_get_contents("https://auth.ubiniti.com/ubinbb/version/curversion.txt");
if ($currentVersion == $lastestVersion) {
    $upToDate = '<span class="text-success fw-light ms-3">Up to date! <i class="fas fa-check-square"></i></span></span>';
} else {
    $upToDate = '<span class="text-danger fw-bold ms-3">Out of date! <i class="fas fa-window-close"></i></span>';
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
        <div class="container shadow-3 p-3">
            <div class="row d-flex justify-content-center gx-4">
                <div class="col-md-3">
                    <div class="container shadow-3 p-4 bg-primary rounded-7 text-white">
                        <p class="fs-5">User Count:</p>
                        <p><?

						echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));

                        ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="container shadow-3 p-4 bg-primary rounded-7 text-white">
                        <p class="fs-5">Unread Reports:</p>
                        <p><?
							echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reports WHERE r_status='1'"));
                        ?></p>
                    </div>
                </div>
				<div class="col-md-3">
                    <div class="container shadow-3 p-4 bg-primary rounded-7 text-white">
                        <p class="fs-5">Global Thread Count:</p>
                        <p><?
							echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM threads"));
                        ?></p>
                    </div>
                </div>
            </div>
			<p class="fs-4 text-primary fw-bold mt-4 mb-2">Quick Actions</p>
			<a class="btn btn-lg btn-secondary my-1">Lock Forums</a>
			<a class="btn btn-lg btn-secondary my-1">Force Logout Users</a>
			<table class="table table-striped border-primary">
				<thead>
					<tr>
					<th scope="col" style="width: 50%;">Server / Forums Information</th>
					<th scope="col">Values</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Software Version</th>
						<td><? echo $_SOFTWARE_VERSION . " " . $upToDate; ?> </td>
					</tr>
					<tr>
						<th>PHP Version</th>
						<td><? echo phpversion(); ?></td>
					</th>
					<tr>
						<th>SQL Version</th>
						<td><? echo $conn->server_info; ?></td>
					</th>
				</tbody>
			</table>
            <p class="fs-4 text-primary fw-bold mt-4 mb-2">Plugins</p>
            <table class="table table-striped border-primary">
				<thead>
					<tr>
					<th scope="col" style="width: 70%;">Plugin Details</th>
					<th scope="col">Active</th>
					<th scope="col">Options</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
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
