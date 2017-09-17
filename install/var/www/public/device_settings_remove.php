<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'device_settings_header.php';
    include 'form_device_remove.php';
    require_once ('lib/groop/src/groop_device_register.php');

    $register = new DeviceRegister($_GET['id']);

    include 'header.php';
    include 'nav_main.php';
?>

<div id="body">
<div><?php printHeaderBacklink($register); ?></div>
<div>REMOVE DEVICE</div>
<div>
<p>Removing this device will remove all data associated with this device/alias.<br>
This cannot be undone. Once removed, ALL DATA WILL BE LOST.<br>
All charts are images that can be saved. If you would like to save data, save a chart image.</p>
</div>
<div><?php printForm($register->getId())?></div>
</div>
<?php include 'footer.php' ?>