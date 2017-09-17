<?php 
	include 'session_check.php';
	include 'session_check_admin.php';
    include 'device_settings_header.php';
    require_once ('lib/groop/src/groop_device_register.php');

    $register = new DeviceRegister($_GET['id']);

	$remove = '';

    if ($register->getValid() == 0) {
    	$remove = '<a href="device_settings_remove.php?id=' . $register->getId() . '" target="_self">REMOVE DEVICE</a>';
    }

    include 'header.php';
    include 'nav_main.php';
?>

<div id="body">
<div><?php printHeader($register)?></div>
<div>
<div>
<a href="device_settings_rename.php?id=<?php echo $register->getId(); ?>" target="_self">DEVICE ALIAS</a>
</div>
<div>
<a href="device_settings_unit.php?id=<?php echo $register->getId(); ?>" target="_self">TEMPERATURE FORMAT</a>
</div>
<div>
<?php echo $remove ?>
</div>
</div> <!--/nav-list-->
</div> <!--/body-->

<?php include 'footer.php'; ?>