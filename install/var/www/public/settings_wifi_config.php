<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../forms/form_network_wifi.php');

    $network = $_GET['network'];
    $essid = exec("iwgetid -r");

    if (strlen($essid) > 0) {
        header('HTTP/1.1 303');
        header('Location: settings_network_local.php');
        exit;
    }

    if ( isset($_POST['BTN_WIFI']) ) {
        connectWifi($_POST['TXT_ESSID'], '"' . $_POST['TXT_PSK'] . '"');
        exit;
    }


    printHeader();
    printBanner();
    // printNavigation();
?>
<script language="Javascript">
    function showAlert() {
        alert("During the configuration process, the RPI's access point will be reset. If you are connected to the RPI via it's access point, you will be disconnected while the RPI configures the WIFI client.");
    }
</script>

<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/ <a href="settings_network.php" target="_self">NETWORK</a>
/ <a href="settings_network_local.php" target="_self">LOCAL NETWORK</a>
</div>


<?php printTitle('SETTINGS : WIFI'); ?>

<hr>

<form class="form" role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
    <div class="map-title">ENTER PASSWORD FOR '<?php echo $network; ?>'</div>
    <div class="map">
        <div class="map-key">Password:</div>
        <div class="map-value"><input type="password" name="TXT_PSK" required/></div>
        <div><input type="hidden" name="TXT_ESSID" value="<?php echo $network?>"></input></div>
    </div>
    <button type="submit"  name="BTN_WIFI" onclick="showAlert()">Connect</button>
</form>

<hr>

<div class="settings-subheading">
<h3>Read before connecting/disconnecting..</h3>
<p>During the configuration process, the RPI's access point will be reset. If you are connected to the RPI via it's access point, you will be disconnected while the RPI configures the WIFI client</p>
<p>When the RPI's access point becomes available, rejoin it, and then go to Settings/Network/Local Network to confirm connection status.</p>

</div>

</div> <!--/content-->

<?php printFooter() ?>
