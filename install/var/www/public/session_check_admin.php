<?php
    if ($_SESSION['userid'] >= 2) {
        header('Location: home.php');
        exit;
    }
?>
