<?php
	ob_start();
	session_start();
	if (isset($_SESSION['valid'])) {
		header('Location: home.php');
		exit;
	}
	include 'constants.php';

	$auth_log = "/var/log/lighttpd/sentry-auth.log";
    $msg      = "Login";
	$userid   = "";
	$username = "";
	$hash     = "";

    if (isset($_POST['login']) && !empty($_POST['user']) && !empty($_POST['password'])) {

		sleep(2);
		try {
			$pdo = new PDO(USER_DB);
			} catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

	    try {
    	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	    $pdo->beginTransaction();
    	    $sql = "SELECT * FROM hash WHERE user=?";
    	    $statement = $pdo->prepare($sql);
			$statement->execute(array($_POST['user']));
			$statement->bindColumn('id', $userid);
			$statement->bindColumn('user', $username);
			$statement->bindColumn('value', $hash);
    	    $statement->fetch(PDO::FETCH_BOUND);
    	    $statement->closeCursor();
    	    $statement = null;
    	    $pdo = null;
    	    } catch(EXCEPTION $e) {}

        if (password_verify($_POST['password'], $hash)) {
            $_SESSION['valid']    = true;
            $_SESSION['timeout']  = time();
 			$_SESSION['userid']   = $userid;
			$_SESSION['username'] = $username;
			header('Location: home.php');
			exit;
    	} else {
			$msg = "Login failed";
			error_log("LOGIN ATTEMPT FAILED...user: ".$_POST['user']."\r\n", 3, $auth_log);
			}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Sentry | Login</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
   	</head>
   <body>
      <div>
         <form role = "form" action = " <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
            <div class="title"><?php echo $msg?></div><br>

			<div class="form-input"><input type = "text" name="user" placeholder="USER" required autofocus></div>
            <div class="form-input"><input type = "password" name = "password" placeholder = "PASSWORD" required></div>
            <div class="form-button"><button type = "submit" name = "login">Submit</button></div>
         </form>
      </div>
   </body>
</html>
