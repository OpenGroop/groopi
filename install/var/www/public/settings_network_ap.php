<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../lib/groop/src/groop_ip_tools.php');
    require_once ('../page/page_template.php');

    $apip   = get_if_ip('uap0');
    $apname = get_ap_ssid();
    $appw   = get_ap_pw();

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
<div><?php printTitle('SETTINGS : ACCESS POINT'); ?></div>
<hr>
    <div class="map">
        <div class="map-key">Network:</div>
        <div class="map-value spaced"><?php echo $apname ?></div>
    </div>

    <div class="map">
        <div class="map-key">Password:</div>
        <div class="map-value spaced"><?php echo $appw ?></div>
    </div>

    <div class="map">
        <div class="map-key">Web URL:</div>
        <div class="map-value spaced">https://10.10.10.1</div>
    </div>

<hr>
<div class="settings-subheading">
    <h3>Accessing the OpenGroop console via the access point</h3>

    <p>On a WIFI capable device:</p>

    <ol>
        <li>Scan for available WIFI networks.</li>
        <li>Join this RPI's network.</li>
        <li>Browse to this RPI's web URL.</li>
        <li>Log in to the OpenGroop console.</li>
    </ol>
</div>
</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>
