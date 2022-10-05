<?
    // This is the groups index page!

    session_start();
    $pageTitle = "Verify Account";
    $page = "verify";
    $requireLogin = true;

    require_once('../inc/user.php');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <? require_once('../inc/page_top.php'); ?>
    </head>

    <body>
    <!-- This is all of the modals -->

    <!-- The navbar file -->
    <? require_once('../inc/navbar.php'); ?>

    <div class="container">
        <div class="container shadow-3 my-4">
            
        </div>
    </div>

    <!-- End your project here-->
    </body>

    <!-- MDB ESSENTIAL -->
    <? require_once('../inc/page_bottom.php'); ?>
</html>
