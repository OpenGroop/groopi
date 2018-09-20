<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');

    // HEADER
    printHeader();

    // BODY
    printBanner();
    // printNavigation();

    // CONTENT
?>
<!-- HTML START -->
<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a> /
</div>
<?php echo printTitle('SETTINGS : NETWORK') ?>
<hr>
<div class="settings-header"><a href="settings_network_ap.php" target="_self">ACCESS POINT</a></div>
<div class="settings-header"><a href="settings_network_local.php" target="_self">LOCAL NETWORK</a></div>
<div class="settings-header"><a href="settings_network_remote.php" target="_self">REMOTE NETWORK</a></div>
</div> <!--/#content-->

<?php
    printFooter(); 
?>
