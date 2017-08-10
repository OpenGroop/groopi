<?php 
	try {
		$database = new PDO(REGISTER_DB);
		} catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

	try {
		$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 		$database->beginTransaction();
//		$sql = "SELECT  device_id, device_alias, valid FROM device_registers ORDER BY device_alias";
		$sql = "SELECT  * FROM device_registers ORDER BY device_alias";
		$statement = $database->query($sql);
		$result = $statement->fetchAlL(PDO::FETCH_ASSOC);
		$statement->closeCursor();
		$statement = null;
		$database = null;
		} catch(EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

	foreach ($result as $array) {
		$deviceAlias    = $array['device_alias'];
		$deviceId       = $array['device_id'];
		$granular_table = $array['granular_table'];
		$summary_table  = $array['summary_table'];
		$valid          = $array['valid'];
		echo '<div class="nav-list-block"><a href="device.php?id='.$deviceId.'" target="_self" >'.$deviceAlias.'</a></div>';
		}
?>
