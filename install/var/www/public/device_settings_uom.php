<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'constants.php';
    $device_id    = $_GET['id'];
    $device_alias = $_GET['alias'];

    $actionPath = $_SERVER['PHP_SELF'] . "?id=" . $device_id . "&alias=" . $device_alias;

    if (isset($_POST['BTN_TEMP']) && !empty($_POST['RADIO_TEMP']))  {
        try {
            $pdo = new PDO(REGISTER_DB);
        } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "UPDATE device_registers SET uom=? WHERE device_id=?";
            $stmnt = $pdo->prepare($sql);
            $stmnt->execute(array($_POST['RADIO_TEMP'], $device_id));
            $pdo->commit();
            $pdo = null;
        } catch (EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }
        header("Location: device_settings.php?id=".$device_id);
        exit;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Sentry </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>

        <div class="title">DEVICE SETTINGS:</div>
        <div class="title-3">DEVICE: <?php echo $device_id; ?></div>
        <div class="title-3">ALIAS: <?php echo $device_alias; ?></div><br>
        <div class="nav-block">
            <div class="title-2">TEMPERATURE FORMAT</div><br>
            <form action = "<?php echo htmlspecialchars($actionPath); ?>" method = "post">
                <div class="nav-list-block"><input type="radio" name="RADIO_TEMP" value="temp_c">Celsius</div>
                <div class="nav-list-block"><input type="radio" name="RADIO_TEMP" value="temp_f">Fahrenheit</div>
                <div><button type="submit" name="BTN_TEMP">SUBMIT</button></div>
            </form>
        </div>

    </body>
</html>
