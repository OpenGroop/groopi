<?php
	include 'session_check.php';

 	$msg = "";

    if (isset($_POST['SUBMIT']) && !empty($_POST['PW_CURRENT']) && !empty($_POST['PW_NEW_1']) && !empty($_POST['PW_NEW_2'])) {

        sleep(2);
		$hash = '';
		$PDO_DB = 'sqlite:/var/local/sqlite/db/user.db';
        try {
            $pdo = new PDO($PDO_DB);
            } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "SELECT * FROM hash WHERE id=?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($_SESSION['userid']));
            $statement->bindColumn('value', $hash);
            $statement->fetch(PDO::FETCH_BOUND);
            $statement->closeCursor();
            $statement = null;
            $pdo = null;
            } catch(EXCEPTION $e) {}
		if (password_verify($_POST['PW_CURRENT'], $hash)) {
			if ($_POST['PW_NEW_1'] == $_POST['PW_NEW_2']) {
				$options = ['cost' => 9,];
				$newPassword = password_hash($_POST['PW_NEW_1'], PASSWORD_BCRYPT, $options);

				try {
					$pdo = new PDO($PDO_DB);
					} catch (EXCEPTION $e) {
					die("Unable to connect: " . $e->getMessage());
					}
				try {
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pdo->beginTransaction();
					$sql = "UPDATE hash SET value=? WHERE id=?";
					$statement = $pdo->prepare($sql);
					$statement->execute(array($newPassword,$_SESSION['userid']));
					$pdo->commit();
					$pdo = null;
					} catch (EXCEPTION $e) {}
				$msg = "Password succesfully changed.";
			} else {
				$msg = "Failed. New passwords do not match.";
			}
        } else {
            $msg = "Failed. Current password is incorrect.";
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title> User Settings | Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />	</head>
	<body>
		<?php include 'nav_main.php'; ?>
		<br>
		<div class="title">SETTINGS</div>
		<br>
		<div class="title-2">CHANGE PASSWORD</div>
		<br>
		<div>
			<form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
				<div class="form-input"><input type="password" name="PW_CURRENT" placeholder="CURRENT PASSWORD" required/></div>
				<div class="form-input"><input type="password" name="PW_NEW_1" placeholder="NEW PASSWORD" required/></div>
				<div class="form-input"><input type="password" name="PW_NEW_2" placeholder="CONFIRM PASSWORD" required/></div>
				<div class="form-button"><button type="submit" name="SUBMIT">Submit</button>
				<div class="setting-header"><?php echo $msg; ?></div>
			</form>
		</div>
	</body>
</html>
