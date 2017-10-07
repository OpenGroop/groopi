<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';

    // HEADER
    printHeader();

    // BODY
    printBanner();

    // CONTENT
    echo '<div id="content">'.PHP_EOL;
    printTitle('SETTINGS');
?>
<!-- HTML START -->
<div class="content">
    <div class="settings-header"><a href="settings_network.php" target="_self">NETWORK</a></div>
    <div class="settings-header"><a href="settings_users.php"   target="_self">USERS</a></div>
    <div class="settings-header"><a href="settings_backup.php"  target="_self">BACKUP/RESTORE DATA</a></div>
</div> <!--/.content-->
</div> <!--/#content-->

<?php
    printFooter(); 
?>
