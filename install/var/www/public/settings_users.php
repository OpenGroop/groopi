<?php
    include 'session_check.php';
    include 'session_check_admin.php';

    unset($_SESSION['varid']);
    unset($_SESSION['varuser']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Users | Settings | Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title"> SETTINGS</div><br>
        <div class="title-2">USERS</div><br>
        <?php include 'nav_users.php';?>
        <br>
        <div><a href="settings_user_add.php" target="_self">ADD USERS</a></div>
    </body>
</html>
