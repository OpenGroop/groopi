<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'constants.php';

    $valid = '';
    $msg   = '';

    // CONNECT TO SYSTEM DATABASE
    try {
        $system_db = new PDO(SYSTEM_DB);
        } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    try {
        $system_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $system_db->beginTransaction();
        $statement = $system_db->prepare('SELECT valid FROM usb WHERE ROWID=1');
        $statement->execute();
        $statement->bindColumn('valid', $valid);
        $statement->fetch(PDO::FETCH_BOUND);
        $statement = null;
        $system_db = null;
        } catch(EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }    

    if     ($valid == '') {$msg = "Error: USB validity not assigned.";}
    elseif ($valid == 0 ) {$msg = "No USB drive detected.<br>Plug in a USB drive and refresh this page.";}
    elseif ($valid >= 1 ) {$msg = "USB drive detected.";}
    
    $cmd = '';

    if (isset($_POST['btn_BACKUP'])) {
        $cmd = 'sudo /usr/local/bin/backupdbs.sh';
        exec($cmd);
        $cmd = '';
    }

    if (isset($_POST['btn_RESTORE'])) {
        $cmd = 'sudo /usr/local/bin/restoredbs.sh';
        exec($cmd);
        $cmd = '';
    }    

?>

<!DOCTYPE html>
<html>
    <head>
        <title> Backup-Restore | Settings | Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title"> SETTINGS</div><br>
        <div class="title-2">BACKUP/RESTORE DATA</div><br>
        <div class="title-2"><?php echo $msg; ?></div><br>
        <div>
        <?php
            if ($valid >=1) {
            echo '<form role = "form" action = "'.$_SERVER['PHP_SELF'].'" method = "post">';
            echo '<div class="form-button"><button type = "submit" name = "btn_BACKUP">BACKUP DATA</button></div><br>';
            echo '<div class="form-button"><button type = "submit" name = "btn_RESTORE">RESTORE DATA</button></div><br>';
            echo '</form>';
            }
        ?>
        </div>
    </body>
</html>
