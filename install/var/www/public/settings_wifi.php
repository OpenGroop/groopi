<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../forms/form_network_wifi.php');

    if ( isset($_POST['BTN_DISCONNECT']) ) {
        exec('sudo wpa_disconnect.sh');
    }

    $essid = exec("iwgetid -r");

    function showNetwork() {
        global $essid;

        if ( strlen($essid) > 0 ) {
            $wlnip = exec("ip addr show wlan0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
            echo '<div class="map-title">CURRENT WIFI NETWORK</div>'.PHP_EOL;
            echo '<div class="map">'.PHP_EOL;
            echo '<div class="map-key">IP address:</div>'.PHP_EOL;
            echo '<div class="map-value spaced">'. $wlnip .'</div>'.PHP_EOL;
            echo '</div>'.PHP_EOL;

            echo '<div class="map">'.PHP_EOL;
            echo '<div class="map-key">Network:</div>'.PHP_EOL;
            echo '<div class="map-value spaced">'. $essid .'</div>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post"><button name="BTN_DISCONNECT">Disconnect</button></form>'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
        } else {
            $scan = [];
            $rc   = -1;
            exec('sudo essid-scan.sh', $scan, $rc);

            echo '<div class="map-title">AVAILABLE WIFI NETWORKS</div>'.PHP_EOL;

            foreach ($scan as $network) {
                if (strlen($network) != null && $network != $essid) {
                    echo '<div class="spaced"><a href="settings_wifi_config.php?network=' . $network . '">'.$network.'</a></div>'.PHP_EOL;
                }
            }
            echo '<form class="form" role="form" action="' . $_SERVER['PHP_SELF'] . '" method="post">'.PHP_EOL;
            echo '<button type="submit"  name="BTN_SCAN">Scan</button>'.PHP_EOL;
            echo '</form>'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
        }
    }


    printHeader();
    printBanner();
    printNavigation();
?>

<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/ <a href="settings_network.php" target="_self">NETWORK</a>
/ <a href="settings_network_local.php" target="_self">LOCAL NETWORK</a>
</div>

<?php printTitle('SETTINGS : WIFI'); ?>

<hr>

<?php showNetwork() ?>

<div class="settings-subheading">
<h3>Read before connecting/disconnecting..</h3>
<p>During the configuration process, the RPI's access point will be reset. If you are connected to the RPI via it's access point, you will be disconnected while the RPI configures the WIFI client</p>
<p>When the RPI's access point becomes available, rejoin it, and then go to Settings/Network/Local Network to confirm connection status.</p>

</div>

</div> <!--/content-->

<?php printFooter() ?>
