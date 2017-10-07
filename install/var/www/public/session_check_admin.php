<?php
    if ($_SESSION['userid'] >= 3) {
        header('Location: home.php');
        exit;
    }
?>
