<?php 
	include 'session_check_admin.php';
    try {
        $pdo = new PDO('sqlite:/var/local/sqlite/db/user.db');
        } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $sql = "SELECT id, user FROM hash WHERE id > 1";
        $statement = $pdo->query($sql);
        $result = $statement->fetchAll();
        $statement->closeCursor();
        $statement = null;
        $pdo = null;
        } catch(EXCEPTION $e) {}

	foreach ($result as $row) {
		$userid   = $row['id'];
		$username = $row['user'];
		echo '<div class="nav-list-block"><a href="settings_user.php?id='.$userid.'" target="_self" >'.$username.'</a></div>';
		}
?>
