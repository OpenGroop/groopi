<?php
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

    $url = 'https://' . $_SERVER['HTTP_HOST'] . '/login.php';
    header('HTTP/1.1 303');
    header("Location: $url");
    exit;
?>
