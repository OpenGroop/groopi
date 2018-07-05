<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../lib/groop/src/groop_ip_tools.php');
    require_once ('../page/page_template.php');

    $ethip = get_if_ip('eth0');
    $wlnip = get_if_ip('wlan0');
    $remip  = get_remote_ip();

    // HEADER
    printHeader();

    // BODY
    printBanner();
    printNavigation();
?>
<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/ <a href="settings_network.php" target="_self">NETWORK</a>
</div>
<?php printTitle('SETTINGS : REMOTE NETWORK'); ?>

<hr>

<div class="map-title">REMOTE IP ADDRESS</div>
<div class="map">
    <div class="map-key">Remote:</div>
    <div class="map-value spaced"><?php echo $remip ?></div>
</div>

<hr>

<div class="map-title">LOCAL IP ADDRESSES</div>
<div class="map">
    <div class="map-key">WIFI:</div>
    <div class="map-value spaced"><?php echo $wlnip ?></div>
</div>

<div class="map">
    <div class="map-key">Ethernet:</div>
    <div class="map-value spaced"><?php echo $ethip ?></div>
</div>

<hr>

<div class="settings-subheading">
<h2>Requirements</h2>
<ul>
<li>A local IP address. Either WIFI or Ethernet.</li>
<li>Port forwarding enabled on every router between the RPI and the Internet.</li>
</ul>

<h3>Port forwarding</h3>
<p>Port forwarding is a way of making a computer on your local network accessible from the Internet. After you have forwarded a port you are said to have an open port.</p>
<p>Port forwarding is handled differently by different routers. That being said, I suggest doing an Internet search for  your router model with the term 'port forwarding' to get the proper instructions for opening ports on your router.</p>
<p>In a nutshell, your router will ask for:</p>
<ul>
<li>a source port, and</li>
<li>a destination IP address and port.</li>
</ul>

<p>The source port is:</p>
<ul>
<li class="spaced">80 (http:// - insecure)</li>
<li class="spaced">443 (https:// - secure - highly suggested - but comes with warnings)</li>
</ul>
<p>The destination IP address can be any local IP address listed above (if available). The destination port will be the same as the source port (80 or 443).</p>
<p>Note that if there are multiple routers between the Internet and the RPI, then the destination IP address will be the IP address of the next router in line, and so on until the RPI's IP address is reached.</p>

<h3>Remote access</h3>
<p>Once your router(s) are configured to forward ports to the RPI, the OpenGroop console will be accessible remotely by pointing a browser to the remote IP address.</p>
</div>
</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>
