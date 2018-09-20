<?php
    session_start();

    function exitPage() {
        header('HTTP/1.1 303');
        header('Location: login.php');
        exit;
    }

    if (!$_SESSION['valid'] == true) {
    	exitPage();
    }

?>
