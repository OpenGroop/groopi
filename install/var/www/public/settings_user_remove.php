<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'constants.php';
    $msg = "(check all 5 boxes)";

    function verifyRemove() {
        $b = false;
        if (isset($_POST['REMOVE_1']))   {
            if (isset($_POST['REMOVE_2'])) {
                if (isset($_POST['REMOVE_3'])) {
                    if (isset($_POST['REMOVE_4'])) {
                        if (isset($_POST['REMOVE_5'])) {
                            $b = true;
                        }
                    }
                }
            }
        }
        return $b;
    }


    if ( isset($_POST['BTN_REMOVE']) ) {
        if (verifyRemove()) {
            try {
                $pdo = new PDO(USER_DB);
            } catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "DELETE FROM hash WHERE id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($_SESSION['varid']));
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
            } catch (EXCEPTION $e) {}
            header('Location: settings_users.php');
            exit;
        } else {
            $msg = "(ALL 5 BOXES MUST BE CHECKED)";
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Remove User | Settings | Sentry   </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title"> SETTINGS</div><br>
        <div class="title-2">USER: <?php echo $_SESSION['varuser']; ?></div><br>
        <div class="title-3"> REMOVE USER</div><br>
        <div>
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div>
                    <input type="checkbox" name="REMOVE_1"/>
                    <input type="checkbox" name="REMOVE_2"/>
                    <input type="checkbox" name="REMOVE_3"/>
                    <input type="checkbox" name="REMOVE_4"/>
                    <input type="checkbox" name="REMOVE_5"/>
                </div>
                <div class="setting-header"><?php echo $msg; ?></div>
                <div><button type="submit" name="BTN_REMOVE">REMOVE</button></div>
            </form>
        </div>
 
    </body>
</html>
