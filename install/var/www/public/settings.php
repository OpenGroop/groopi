<?php 
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'header.php';
    include 'nav_main.php';
?>
<div id="body">
<div>SETTINGS</div>
<div>
    <div><a href="settings_network.php" target="_self">NETWORK</a></div>
    <div><a href="settings_users.php"   target="_self">USERS</a></div>
    <div><a href="settings_backup.php"  target="_self">BACKUP/RESTORE DATA</a></div>
</div>
</div> <!--/body-->
<?php include 'footer.php'; ?>