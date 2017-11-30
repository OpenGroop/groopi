<?php 
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';
    include 'form_network_wifi.php';

    $ethip = exec("ip addr show eth0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $wlnip = exec("ip addr show wlan0 | grep \"inet\b\" | awk '{print $2}' | cut -d/ -f1");
    $essid = exec("iwgetid -r");

    printHeader();
    printBanner();
?>
<div id="content">
<?php printTitle('SETTINGS:NETWORK') ?>
<div class="settings-header"><b>ETHERNET</b></div>
<div class="settings-subheading">
 <table>
  <tr>
   <td>IP ADDRESS:</td><td><?php echo $ethip; ?></td>
  <tr>
 </table>
</div>
<div class="settings-header"><b>WIFI</b></div>
<div class="settings-subheading">
 <table>
  <tr>
   <td class="key">SSID:</td><td><?php echo $essid; ?></td>
  </tr>
  <tr>
   <td>IP ADDRESS: </td><td><?php echo $wlnip; ?></td>
  </tr>
 </table>
</div>
<div class="settings-header"><b>CONFIGURE WIFI</b></div>
<div> <?php printWifiForm() ?></div>
</div> <!--/content-->

<?php printFooter() ?>