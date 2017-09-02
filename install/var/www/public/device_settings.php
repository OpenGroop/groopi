<?php 
	include 'session_check.php';
	include 'session_check_admin.php';
	include 'constants.php';

	$device_id = $_GET['deviceid'];
	$device_alias = "";
	$device_valid = 1;

	$remove = '';

    try {
        $pdo = new PDO(REGISTER_DB);
        } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $statement = $pdo->prepare("SELECT device_alias, valid FROM device_registers WHERE device_id=?");
        $statement->execute(array($device_id));
		$statement->bindColumn('device_alias', $device_alias);
		$statement->bindColumn('valid', $device_valid);
        $statement->fetch(PDO::FETCH_BOUND);
        $statement = null;
        $pdo = null;
        } catch(EXCEPTION $e) { $pdo->rollback(); echo 'FAILED: ' . $e->getMessage(); }

    if ($device_valid == 0) {
    	$remove = '<a href="device_settings_remove.php?id='.$device_id.'&alias='.$device_alias.'" target="_self">REMOVE DEVICE</a>';
    }
?>

<html>
	<head>
		<title> Sentry | Edit Device</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	</head>
	<body>
		<?php include 'nav_main.php'; ?><br>

		<div class="title"> DEVICE SETTINGS </div>
		<div class="title-3">DEVICE: <?php echo $device_id; ?></div>
		<div class="title-3">ALIAS: <?php echo $device_alias; ?></div><br>
		<div class="nav-list">
			<div class="nav-list-block">
				<a href="device_settings_rename.php?id=<?php echo $device_id ?>&alias=<?php echo $device_alias ?>" target="_self">DEVICE ALIAS</a>
			</div>
			<div class="nav-list-block">
				<a href="device_settings_uom.php?id=<?php echo $device_id ?>&alias=<?php echo $device_alias ?>" target="_self">TEMPERATURE FORMAT</a>
			</div>
			<div class="nav-list-block">
				<?php echo $remove ?>
			</div>
		</div>
	</body>
</html>
