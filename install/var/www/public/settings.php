<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');

    // HEADER
    printHeader();

    // BODY
    printBanner();
    printNavigation();

0?>
<!-- HTML START -->

<div id="content">
<?php echo printTitle('SETTINGS') ?>
<hr>
<div class="settings-header"><a href="settings_network.php" target="_self">NETWORK</a></div>
<div class="settings-header"><a href="settings_users.php"   target="_self">USERS</a></div>
<div class="settings-header"><a href="settings_backup.php"  target="_self">BACKUP/RESTORE DATA</a></div>
</div> <!--/.content-->

<?php
    printFooter(); 
?>
