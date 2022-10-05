<?

session_start();

$requiredLogin = true;
$page = "forums";

require '../inc/user_check.php';
require '../../inc/db.inc.php';

if (isset($_GET['reload'])) {

    $_SESSION['toast'] = array("success", "Reloaded", "Data has been reloaded successfully!");

}

if (isset($_POST['submitOrder'])) {

    $isValue = true;
    foreach($_POST as $key => $value){
        if ($value != "Submit") {
            $catID = substr($key, 3);
            if (is_numeric($catID)) {
                $updateSql = mysqli_query($conn, "UPDATE categories SET c_order='$value' WHERE c_id='$catID'");
            } else {
                $isValue = false;
                break;
            }

        }
    }

    if (!$isValue) {
        $_SESSION['toast'] = array('warn', "Invalid Number", "Please enter only valid numbers!");
    } else {
        $_SESSION['toast'] = array('success', "Order Updated", "The order has been updated successfully!");
    }

    header("Location: categories.php");
    die();

}

if (isset($_POST['addCategory'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $order = mysqli_real_escape_string($conn, $_POST['order']);
    if (!is_numeric($order)) {
        $_SESSION['toast'] = array("warn", "Invalid Order", "Please enter a valid number for the order!");
        header("Location: categories.php");
        die();
    }

    $insertSql = mysqli_query($conn, "INSERT INTO categories(c_title, c_description, c_order) VALUES('$title', '$description', '$order')");
    if ($insertSql) {
        $_SESSION['toast'] = array("success", "Category Added", "Category has been successfully added!");
    } else {
        $_SESSION['toast'] = array("error", "An error has occured", "Error: " . mysqli_error($conn));
    }

    header("Location: categories.php");
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

  <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" style="margin-top: 15px;" action="categories.php">
                        <div class="form-outline my-4">
                            <input type="text" id="title" name="title" class="form-control" required/>
                            <label class="form-label" for="title">Category Title</label>
                        </div>
                        <div class="form-outline mb-4">
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            <label class="form-label" for="description">Category Description</label>
                        </div>
                        <div class="form-outline my-4">
                            <input type="text" id="order" name="order" class="form-control" required/>
                            <label class="form-label" for="order">Category Order</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="addCategory"><i class="fas fa-check-square me-2"></i>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Start your project here-->
    <?
        require '../inc/navbar.php';
        require '../inc/toast.php';
    ?>
    
    <div class="container mt-3">
        <div class="container shadow-3">
            <div class="text-end my-3">
                <a class="btn btn-success btn-rounded m-1" data-mdb-toggle="modal" data-mdb-target="#addCategory">Add Category</a>
                <a href="categories.php?reload" class="btn btn-primary btn-rounded m-1">Reload Data</a>
            </div>
            <table class="table table-borderless">
                <thead>
                    <tr>
                    <th scope="col" style="width: 70%;">Category</th>
                    <th scope="col"><p>
                        Order
                        <a href="#" data-mdb-toggle="tooltip" title="Order goes from 0 being the top to higher numbers being the bottom"><i class="fas fa-question-circle"></i></a>
                        </p>
                    </th>
                    <th scope="col">Options</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="categories.php" method="POST">
                    <?
                    
                    $getCategories = mysqli_query($conn, "SELECT * FROM categories ORDER by c_order");
                    while ($category = $getCategories -> fetch_assoc()) {

                        echo '<tr class="admin-category">
                        <td><p class="fs-5">'. $category['c_title'] .'</p><p>'.$category['c_description'].'</p></td>
                        <td>  
                            <input type="submit" style="display: none" name="submitOrder" />
                            <input type="text" id="order" name="id:'.$category['c_id'].'" value="' . $category['c_order'] . '"/>
                        </td>
                        <td><a href="edit_category.php?id='. $category['c_id'] .'" class="btn btn-sm btn-primary">Edit</a></td>
                    </tr><tr><td></td><td></td><td></td></tr>';

                    }
                    
                    ?>
                    </form>
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
