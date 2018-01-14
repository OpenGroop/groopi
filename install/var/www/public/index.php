<?php
    session_start();
    function redirect($page) {
        header('HTTP/1.1 303');
        header('Location: https://' . $_SERVER['HTTP_HOST'] . '/' . $page);
        exit;
    }

    if (isset($_SESSION['valid'])) { redirect('devices.php'); }
    else                           { redirect('login.php');   }

?>
