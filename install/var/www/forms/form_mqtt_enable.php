<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');

    function mqttEnanble() {}

?>


<div class="settings-subheading">
    <p>Cloud service is currently disabled.</p>
</div>

<div class="map">
    <div class="map-key">Host:</div>
    <div class="map-value spaced"><input type="text" name="TXT_HOST" required></></div>
</div> <!--/#map-->


<div class="map">
    <div class="map-key">Port:</div>
    <div class="map-value spaced"><input type="text" name="TXT_PORT" required></></div>
</div> <!--/#map-->

<div class="map">
    <div class="map-key">Account ID:</div>
    <div class="map-value spaced"><input type="text" name="TXT_ACCT_ID" required></></div>
</div> <!--/#map-->


<div class="map">
    <div class="map-key">Password:</div>
    <div class="map-value spaced"><input type="text" name="TXT_PASSWD" required></></div>
</div> <!--/#map-->
 
