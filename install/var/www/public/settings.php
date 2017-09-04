<?php 
    include 'session_check.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Settings | Sentry</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
    <?php 
        include 'nav_main.php';
        echo '<br><div class="title"> SETTINGS </div><br>';
        if ($_SESSION['userid'] < 3) {
            include 'nav_settings_admin.php';
        } else {
            include 'nav_settings_user.php';
        }
    ?>
    </body>
</html>
