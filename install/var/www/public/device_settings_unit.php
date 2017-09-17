<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'device_settings_header.php';
    include 'form_device_unit.php';
    require_once ('lib/groop/src/groop_device_register.php');

    $register = new DeviceRegister($_GET['id']);

    include 'header.php';
    include 'nav_main.php';

?>

<div id="body">
<div><?php printHeaderBacklink($register); ?></div>
<div>
<div>TEMPERATURE FORMAT</div><br>
<div><?php printForm($register->getId());?></div>
</div> <!--/nav-block-->
</div> <!--/body-->
<?php include 'footer.php'; ?>
