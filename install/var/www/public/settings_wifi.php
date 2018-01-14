<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../forms/form_network_wifi.php');

    if ( isset($_POST['BTN_DISCONNECT']) ) {
        exec('sudo wpa_disconnect.sh');
    }

    $wlnip = exec("ip addr show wlan0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $essid = exec("iwgetid -r");

    function showCurrentNetwork($essid, $wlnip) {
        if ( strlen($essid) > 0 ) {
            echo '<div class="map-title">CURRENT WIFI NETWORK</div>'.PHP_EOL;
            echo '<div class="map">'.PHP_EOL;
            echo '<div class="map-key">IP address:</div>'.PHP_EOL;
            echo '<div class="map-value spaced">'. $wlnip .'</div>'.PHP_EOL;
            echo '</div>'.PHP_EOL;

            echo '<div class="map">'.PHP_EOL;
            echo '<div class="map-key">Network:</div>'.PHP_EOL;
            echo '<div class="map-value spaced">'. $essid .'</div>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post"><button name="BTN_DISCONNECT">Disconnect</button></form></div>'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
        }
    }


    if ( isset($_POST['BTN_WIFI']) ) {
        connectWifi($_POST['TXT_ESSID'], $_POST['TXT_PSK']);
        exit;
//        $a = 0;
//        foreach ($status as $b) {
//          if ($b > 0) {
//              $a = $b;
//          }
//        }
//        if ($a == 0) {
//            header('HTTP/1.1 303');
//            header('Location: http://'.$_SERVER['HTTP_HOST'].'/logout.php');
//            exit;
//        } else {
//          $status = implode(',', $status);
//          $msg = 'There seems to be a problem..(' . $status . ')';
//        }
    }


    printHeader();
    printBanner();
    printNavigation();
?>
<script language="Javascript">
    function showAlert() {
        alert("During the configuration process, the RPI's access point will be reset. If your browsing device is connected to the RPI's access point, it will be disconnected while the RPI resets it's access point.");
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

<?php showCurrentNetwork($essid, $wlnip) ?>
<form class="form" role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
    <div class="map-title">CONNECT TO LOCAL WIFI NETWORK</div>
    <div class="map">
        <div class="map-key">Network:</div>
        <div class="map-value"><input type="text" name="TXT_ESSID" required/></div>
    </div>

    <div class="map">
        <div class="map-key">Password:</div>
        <div class="map-value"><input type="password" name="TXT_PSK" required/></div>
    </div>
    <button type="submit"  name="BTN_WIFI" onclick="showAlert()">Connect</button>
</form>

<hr>

<div class="settings-subheading">
<h3>Read before connecting/disconnecting..</h3>
<p>During the configuration process, the RPI's access point will be reset. If your browsing device is connected to the RPI's access point, it will be disconnected while the RPI resets it's access point.</p>
<p>When the RPI's access point becomes available, rejoin it, and then go to Settings/Network/Local Network to confirm connection status.</p>

</div>




</div> <!--/content-->

<?php printFooter() ?>
