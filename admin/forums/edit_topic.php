<?php

session_start();

$requiredLogin = true;
$page = "forums";

require '../inc/user_check.php';
require '../../inc/db.inc.php';

if (isset($_POST['updateTopic'])) {

    $topicID = $_SESSION['CURRENT_EDIT_ID'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $order = mysqli_real_escape_string($conn, $_POST['order']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    if (!is_numeric($order)) {
        $_SESSION['toast'] = array("warn", "Invalid Order", "Please enter a valid number for the order!");
        header("Location: edit_topic.php?id=" . $categoryID);
        die();
    }

    $updateSQL = mysqli_query($conn, "UPDATE topics SET t_title='$title', t_description='$description', t_order='$order', t_category='$category' WHERE t_id='$topicID'");
    if ($updateSQL) {
        $_SESSION['toast'] = array("success", "Topic Updated", "Topic has been successfully updated!");
        header("Location: topics.php");
        die();
    } else {
        $_SESSION['toast'] = array("error", "An error has occured", "Error: " . mysqli_error($conn));
        header("Location: edit_topic.php?id=" . $topicID);
        die();
    }


}

if (isset($_GET['id'])) {

    $topicID = $_GET['id'];
    $_SESSION['CURRENT_EDIT_ID'] = $topicID;

    $checkSql = mysqli_query($conn, "SELECT * FROM topics WHERE t_id='$topicID'");
    if (mysqli_num_rows($checkSql) == "1") {

        $topic = mysqli_fetch_assoc($checkSql);

    } else {

        $_SESSION['toast'] = array("error", "Invalid Link", "You've submitted an invalid link...");
        header("Location: topics.php");
        die();

    }

} else {
    $_SESSION['toast'] = array("error", "Invalid Link", "You've submitted an invalid link...");
    header("Location: topics.php");
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

    <div class="modal fade" id="deleteConfirmation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"">
        <div class="modal-dialog modal-sm">
          <div class="modal-content text-center">
            <div class="modal-header bg-danger text-white d-flex justify-content-center">
              <h5 class="modal-title" id="exampleModalLabel">Hold on!</h5>
            </div>
            <div class="modal-body">
              <i class="fas fa-times fa-3x text-danger"></i>
              <p class="tex-center">You are about to delete <b><? echo $topic['t_title'] ?>!</b> This will delete all topics and threads within this category!</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <a href="delete_topic.php?topicID=<? echo $topic['t_id']; ?>&confirmation=1" type="button" class="btn btn-danger">Delete</a>
              <a type="button" class="btn btn-outline-danger" data-mdb-dismiss="modal">
                Cancel
                </a>
            </div>
          </div>
        </div>
      </div>

    <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <form method="POST">
                            <h2 class="fw-bold mb-4 text-uppercase text-primary">Edit Topic</h2>
                            <div class="form-outline my-4">
                                <input type="text" id="title" name="title" class="form-control" value="<? echo $topic['t_title']; ?>" required/>
                                <label class="form-label" for="title">Topic Title</label>
                            </div>
                            <div class="form-outline mb-4">
                                <textarea class="form-control" id="description" name="description" rows="4"><? echo $topic['t_description']; ?></textarea>
                                <label class="form-label" for="description">Topic Description</label>
                            </div>
                            <div class="form-outline my-4">
                                <input type="text" id="order" name="order" class="form-control" value="<? echo $topic['t_order']; ?>" required/>
                                <label class="form-label" for="order">Topic Order</label>
                            </div>
                            <select class="select" name="category">
                            <? 
                                $getCategories = mysqli_query($conn, "SELECT * FROM categories ORDER by c_order");
                                while ($c = $getCategories -> fetch_assoc()) {
                                    if ($topic['t_category'] == $c['c_id']) {
                                        echo '<option value="'. $c['c_id'] .'" selected>' . $c['c_title'] . '</option>';
                                    } else {
                                        echo '<option value="'. $c['c_id'] .'">' . $c['c_title'] . '</option>';
                                    }
                                }
                            ?>
                            </select>
                            <button class="btn btn-primary btn-lg px-5 m-1" type="submit" name="updateTopic">Submit Changes</button>
                            <a class="btn btn-primary btn-lg m-1" href="topics.php">Go Back</a>
                            <a class="btn btn-danger btn-lg m-1" data-mdb-toggle="modal" data-mdb-target="#deleteConfirmation">Delete Category</a>
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
