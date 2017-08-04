<?php
	include 'session_check.php';
	include 'session_check_admin.php';

	$msg ="";

   	if (isset($_POST['BTN_PW']) && !empty($_POST['PW_NEW_1']) && !empty($_POST['PW_NEW_2'])) {

        sleep(2);

        if ($_POST['PW_NEW_1'] == $_POST['PW_NEW_2']) {
            $options = ['cost' => 9,];
            $newPassword = password_hash($_POST['PW_NEW_1'], PASSWORD_BCRYPT, $options);

            try {
                $pdo = new PDO('sqlite:/var/local/sqlite/db/user.db');
                } catch (EXCEPTION $e) {
                    die("Unable to connect: " . $e->getMessage());
                }
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "UPDATE hash SET value=? WHERE id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($newPassword,$_SESSION['varid']));
                $pdo->commit();
                $pdo = null;
	            $msg = "Password  succesfully changed.";
                } catch (EXCEPTION $e) {}
        } else {
            $msg = "Failed. New passwords do not match.";
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Password | Settings | Sentry  </title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	</head>
	<body>
		<?php include 'nav_main.php'; ?><br>
		<div class="title"> SETTINGS</div><br>
        <div class="title-2">USER: <?php echo $_SESSION['varuser']; ?></div><br>
		<div class="title-3"> CHANGE PASSWORD</div><br>
		<div>
    		<form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
		        <div><input type="password" name="PW_NEW_1" placeholder="NEW PASSWORD" required/></div><br>
        		<div><input type="password" name="PW_NEW_2" placeholder="CONFIRM PASSWORD" required/></div><br>
        		<div><button type="submit" name="BTN_PW">Submit</button>
        		<div class="setting-header"><?php echo $msg; ?><br><br></div>
    		</form>
		</div>

	</body>
</html>
