<?php 
    include 'session_check.php';
    include 'session_check_admin.php';

    $ethip = exec("ip addr show eth0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $wlnip = exec("ip addr show wlan0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $essid = exec("iwgetid -r");

    include 'header.php';
    include 'nav_main.php';

?>
<div id="body">
<div>SETTINGS</div>
<div>NETWORK</div>
<div> ETHERNET IP   : <?php echo $ethip; ?></div>
<div> WIRELESS SSID : <?php echo $essid; ?></div>
<div> WIRELESS IP   : <?php echo $wlnip; ?></div>
<div> <a href="settings_network_wifi.php" target="_self">CONFIGURE WIRELESS NETWORK</a></div>
</div> <!--/body-->

<?php include 'footer.php'; ?>
