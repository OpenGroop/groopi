<?php
	include 'session_check.php';


	$msg = "NETWORK CREDENTIALS";

    if (isset($_POST['button']) && !empty($_POST['essid']) && !empty($_POST['psk'])) {
		$cmd = 'sudo wpa_conf.py -s ' . $_POST['essid'] . ' -p ' . $_POST['psk'];
		exec($cmd);
		sleep(5);
		header('Location: settings_network.php');
		exit;
	}


?>
<!DOCTYPE html>
<html>
	<head>
		<title> Configure Wifi  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />	</head>
	<body>
		<?php include 'nav_main.php'; ?><br>
		<div class="title">SETTINGS</div><br>
		<div class="title-2">CONFIGURE WIRELESS</div><br>
		<div>
			<div class="setting-header"> <?php echo $msg; ?> <?div>
			<form role="form" action=" <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="form-input"> <input type="text"     name="essid" placeholder="ESSID" required/></div>
				<div class="form-input"> <input type="password" name="psk"   placeholder="PASSWORD"    required/></div>
				<div class="form-button"> <button type="submit"  name="button">Apply</button></div>
			</form>
		</div>
	</body>
</html>
