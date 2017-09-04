<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'constants.php';

    $id = $_GET['id'];
    $_SESSION['varid']  = $id;
    $user  = "";

    try {
        $pdo = new PDO(USER_DB);
    } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }
    
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $sql = "SELECT user FROM hash WHERE id=?";
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));
        $statement->bindColumn('user', $user);
        $statement->fetch(PDO::FETCH_BOUND);
        $statement->closeCursor();
        $pdo = null;
    } catch (EXCEPTION $e) {}
    
    $_SESSION['varuser'] = $user;
    $msg = "";
?>
<!DOCTYPE html>
<html>
    <head>
        <title> User | Settings | Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title">SETTINGS</div><br>
        <div class="title-2">USER: <?php echo $user; ?></div><br>
        <div><a href="settings_admin_password.php" target="_self">CHANGE PASSWORD</a></div>
        <?php
            if ($id > 2 ) {
                echo '<div><a href="settings_user_remove.php" target="_self">REMOVE USER</a></div>';
            }
        ?>
    </body>
</html>
