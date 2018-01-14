<?php

	require ('../session/session_check.php');

    if ($_SESSION['userid'] > 2) {
    	exitPage();
    }

?>
