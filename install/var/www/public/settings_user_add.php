<?php
	include 'session_check.php';
	include 'session_check_admin.php';
	include 'constants.php';
	$msg = "";

	if (isset($_POST['BTN_USER_ADD']) ) {
		if ($_POST['USER_PW_1'] == $_POST['USER_PW_2']) {
            $options = ['cost' => 9,];
            $password = password_hash($_POST['USER_PW_1'], PASSWORD_BCRYPT, $options);

			$values = array($password,$_POST['USER_NAME']);

			try {
			$pdo = new PDO(USER_DB);
			} catch(EXCEPTION $e) { die("Unable to connect" . $e->getMessage()); }

			try {
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->beginTransaction();
			$sql = "INSERT INTO hash (value, user) VALUES (?,?)";
			$statement = $pdo->prepare($sql);
			$statement->execute($values); // hash, user
			$pdo->commit();
			$statement->closeCursor();
			$pdo = null;
			} catch(EXCEPTION $e) {}

			header('Location: settings_users.php');
			exit;
		} else {
			$msg = "PASSWORDS DO NOT MATCH";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Add User | Settings | Sentry </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	</head>
	<body>
		<?php include 'nav_main.php'; ?><br>
		<div class="title"> SETTINGS</div><br>
		<div class="title-2"> ADD USER </div><br>
		<div>
			<form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="form-input"><input type="text" name="USER_NAME" placeholder="ENTER USERNAME" required autofocus /></div>
				<div class="form-input"><input type="password" name="USER_PW_1" placeholder="ENTER PASSWORD" required /></div>
				<div class="form-input"><input type="password" name="USER_PW_2" placeholder="CONFIRM PASSWORD" required /></div>
				<div class="form-button"><button type="submit" name="BTN_USER_ADD">SUBMIT</button></div>
			</form>
		</div>
		<div class="setting-header"><?php echo $msg ; ?></div>
	</body>
</html>
