<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../lib/groop/src/groop_ip_tools.php');
    require_once ('../page/page_template.php');

    $ethip  = get_if_ip('eth0');
    $ethurl = get_if_url($ethip);

    $wlnip  = get_if_ip('wlan0');
    $wlnurl = get_if_url($wlnip);
    //$essid = get_local_ssid();
    $essid = exec("iwgetid -r");

    printHeader();
    printBanner();
    printNavigation();
?>
<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/ <a href="settings_network.php" target="_self">NETWORK</a>
</div>
<div><?php printTitle('SETTINGS : LOCAL NETWORK'); ?></div>

<hr>
<div class="map-title">ETHERNET INTERFACE</div>
<div class="map">
    <div class="map-key">IP address:</div>
    <div class="map-value spaced"><?php echo $ethip; ?></div>
</div>

<div class="map">
    <div class="map-key">Web URL:</div>
    <div class="map-value spaced"><?php echo $ethurl; ?></div>
</div>

<hr>

<div class="map-title">WIFI INTERFACE</div>
<div class="map">
    <div class="map-key">IP address:</div>
    <div class="map-value spaced"><?php echo $wlnip; ?></div>
</div>

<div class="map">
    <div class="map-key">Web URL:</div>
    <div class="map-value spaced"><?php echo $wlnurl; ?></div>
</div>


<div class="map">
    <div class="map-key">Network:</div>
    <div class="map-value spaced"><?php echo $essid; ?></div>
</div>

<div class="map">
    <div class="map-key spaced"><a href="settings_wifi.php" target="_self">configure</a></div>
    <div class="map-value"></div>
</div>

<hr>

<div class="settings-subheading">
<h3>Accessing the OpenGroop console via a local network</h3>
<p>The RPI can connect to a local network by either Ethernet cable, WIFI, or both.</p>
<h3>Ethernet connection</h3>
<p>Connecting to a local network by Ethernet cable does not require any configuring. Just use an Ethernet cable to connect the RPI to a router, and after a few seconds or so, refresh this page. There should now be an IP address assigned to the Ethernet interface. If not, just keep refreshing. It'll pop up.</p>
<p>Once the Ethernet interface has been assigned an IP address, the OpenGroop console will be accessible on the local network via the Ethernet's web URL.</p>
<h3>WIFI connection</h3>
<p>Connecting to a local WIFI network requires configuring.</p>
<p>During the configuration process, the RPI's access point needs to be reset. If your browsing device is connected to the RPI's access point, it will be disconnected while the RPI resets it's access point.</p>
<p>Once the RPI's access point has become available again, join it, return to this page (you might have to log back in to the OpenGroop console), and confirm an IP address has been assigned to the WIFI interface. Once confirmed, the OpenGroop console will be accessible on the local network via the WIFI's web URL.</p>

</div>

</div> <!--/content-->

<?php printFooter() ?>
