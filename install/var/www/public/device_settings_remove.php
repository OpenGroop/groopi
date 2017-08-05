<?php
	include 'session_check.php';
	include 'session_check_admin.php';
	include 'constants.php';

	$device_id = $_GET['id'];
	$device_alias = $_GET['alias'];
	$device_table_granular = "";
	$device_table_summary  = "";

	$actionPath = $_SERVER['PHP_SELF'] . "?id=" . $device_id . "&alias=" . $device_alias;

	$SENSORDATA_DB = "sqlite:/var/local/sqlite/db/sensordata.db";
	$REGISTER_DB   = "sqlite:/var/local/sqlite/db/register.db";

	if (isset($_POST['BTN_REMOVE']) ) {
		// BEGIN REMOVING DEVICE FROM REGISTER.
		try {
			$pdo = new PDO(REGISTER_DB);
		} catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }

		// FIRST, WE MUST GET GRANULAR AND SUMMARY TABLES FOR DEVICE
		try {
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->beginTransaction();
			$sql = "SELECT granular_table, summary_table FROM device_registers WHERE device_id=?";
			$statement = $pdo->prepare($sql);
			$statement->execute(array($device_id));
			$statement->bindColumn('granular_table', $device_table_granular);
			$statement->bindColumn('summary_table', $device_table_summary);
			$statement->fetch(PDO::FETCH_BOUND);
			$statement->closeCursor();
			// THEN WE DELETE DEVICE FROM REGISTER
			$sql = "DELETE FROM device_registers WHERE device_id=?";
			$statement = $pdo->prepare($sql);
			$statement->execute(array($device_id));
			$pdo->commit();
			$statement->closeCursor();
			$pdo = null;
		} catch (EXCEPTION $e) {}


		// BEGIN REMOVING DEVICE GRANULAR AND SUMMARY TABLES
		$tables = array($device_table_granular, $device_table_summary);
		try {
			$pdo = new PDO(SENSORDATA_DB);
		} catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }

		try {
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->beginTransaction();
			foreach ($tables as $table) {
				$sql = "DROP TABLE " . $table;
				$statement = $pdo->prepare($sql);
				$statement->execute();
			}
			$pdo->commit();
			$statement->closeCursor();
			$pdo = null;
		} catch (EXCEPTION $e) {
			echo $e->getMessage();
			$pdo->rollBack();
			$pdo = null;
		}
		header('Location: devices.php');
		exit;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title> Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	</head>
	<body>
		<?php include 'nav_main.php'; ?>
		<br>
		<div class="title"> DEVICE SETTINGS </div>
		<div class="title-3">DEVICE: <?php echo $device_id; ?></div>
		<div class="title-3">ALIAS: <?php echo $device_alias; ?></div>
		<br>
		<div class="title-2">REMOVE DEVICE</div>
		<br>
		<div>
			Removing this device will remove all data associated with this device/alias.<br>
			This cannot be undone. Once removed, ALL DATA WILL BE LOST.<br>
			All charts are images that can be saved. If you would like to save data, save a chart image.
		</div>
		<br>
		<div>
			<form role="form" action="<?php echo $actionPath ?>" method="post">
				<div class="setting-header">CHECK ALL 5 BOXES TO REMOVE</div>
				<div>
					<input type="checkbox" name="CHK_REMOVE_1" required/>
					<input type="checkbox" name="CHK_REMOVE_2" required/>
					<input type="checkbox" name="CHK_REMOVE_3" required/>
					<input type="checkbox" name="CHK_REMOVE_4" required/>
					<input type="checkbox" name="CHK_REMOVE_5" required/>
				</div>
				<div><button type="SUBMIT" name="BTN_REMOVE">submit</button></div>
			</form>
		</div>

	</body>
</html>
