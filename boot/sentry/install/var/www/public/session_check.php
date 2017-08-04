<?php 
session_start();
if (!$_SESSION['valid'] == true) {
	header('Location: index.php');
	exit;
	}

?>
