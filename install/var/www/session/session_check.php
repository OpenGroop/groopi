<?php
    session_start();

    function exitPage() {
        header('HTTP/1.1 303');
        header('Location: https://' . $_SERVER['HTTP_HOST'] . '/index.php');
        exit;
    }

    if (!$_SESSION['valid'] == true) {
    	exitPage();
    }

?>
