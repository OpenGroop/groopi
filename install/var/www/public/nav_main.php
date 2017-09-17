<div class="nav_header">
    <div class="nav_header_block"><a href="devices.php"  target="_self">Devices</a></div>
    <?php
        if ($_SESSION['userid'] <= 2) {
            echo '<div><a href="settings.php" target="_self">Settings</a></div>'.PHP_EOL;
        }
    ?>
    <div><a href="logout.php" target="_self">Logout</a></div>
    <div><?php echo $_SESSION['username']; ?></div>
</div>
