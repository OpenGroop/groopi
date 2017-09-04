<?php 
    include 'session_check.php';
    include 'session_check_admin.php';

    $ethip = exec("ip addr show eth0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $wlnip = exec("ip addr show wlan0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $essid = exec("iwgetid -r");

?>
<!DOCTYPE html>
<html>
    <head>
        <title> Network | Settings | Sentry</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />  </head>
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title">SETTINGS</div><br>
        <div class="title-2">NETWORK</div><br>
        <div> ETHERNET IP   : <?php echo $ethip; ?></div>
        <div> WIRELESS SSID : <?php echo $essid; ?></div>
        <div> WIRELESS IP   : <?php echo $wlnip; ?></div>
        <div> <a href="settings_network_wifi.php" target="_self">CONFIGURE WIRELESS</a></div>
    </body>
</html>

