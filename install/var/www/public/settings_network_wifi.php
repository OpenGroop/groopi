<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'form_network_wifi.php';
    include 'header.php';
    include 'nav_main.php';

?>
<div id ="body">
<div>SETTINGS</div>
<div>CONFIGURE WIRELESS NETWORK</div>
<div><?php printForm(); ?></div>
</div> <!--/body-->

<?php include 'footer.php'; ?>