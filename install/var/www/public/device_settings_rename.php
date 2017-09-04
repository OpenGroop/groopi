<?php
	include 'session_check.php';
	include 'session_check_admin.php';
    include 'constants.php';

	$device_id    = $_GET['id'];
	$device_alias = $_GET['alias'];

	$actionPath = $_SERVER['PHP_SELF'] . "?id=" . $device_id . "&alias=" . $device_alias;

    if (isset($_POST['BTN_DEV_NAME']) && !empty($_POST['TXT_DEV_NAME']))  {
        try {
            $pdo = new PDO(REGISTER_DB);
            } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "UPDATE device_registers SET device_alias=? WHERE device_id=?";
            $stmnt = $pdo->prepare($sql);
            $stmnt->execute(array($_POST['TXT_DEV_NAME'], $device_id));
            $pdo->commit();
            $pdo = null;
            } catch(EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }
            header("Location: device_settings.php?id=".$device_id);
            exit;
    }

?>
<!DOCTYPE html>
<html>
	<head>
		<title> Rename Device | Device Settings  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	</head>
	<body>
		<?php include 'nav_main.php'; ?><br>

		<div class="title">DEVICE SETTINGS:</div>
		<div class="title-3">DEVICE: <?php echo $device_id; ?></div>
		<div class="title-3">ALIAS: <?php echo $device_alias; ?></div><br>
        <div>
			<div class="title-2">CHANGE ALIAS</div><br>
            <form role = "form" action = "<?php echo htmlspecialchars($actionPath); ?>" method = "post">
                <div class="form-input"><input type ="text" name="TXT_DEV_NAME" placeholder="<?php echo $device_alias?>" required autofocus></div>
                <div class="form-button"><button type ="submit" name ="BTN_DEV_NAME">Apply</button></div>
            </form>
        </div>

	</body>
</html>
