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
    <div class="settings-header"><a href="settings_network_ap.php" target="_self">AP NETWORK</a></div>
    <div class="settings-header"><a href="settings_network_local.php" target="_self">LOCAL NETWORK</a></div>
    <div class="settings-header"><a href="settings_network_remote.php" target="_self">REMOTE NETWORK</a></div>
</div> <!--/.content-->
</div> <!--/#content-->

<?php
    printFooter(); 
?>