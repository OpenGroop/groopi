<?php
    require      ('../session/session_check.php');
    require_once ('../page/page_template.php');

    // HEADER
    printHeader();

    // BODY
    printBanner();
    // printNavigation();

    // CONTENT
    echo '<div id="content">'.PHP_EOL;
    printTitle('SUPPORT');
?>
<!-- HTML START -->

<hr>

<div class="settings-subheading">

<h3>Contact</h3>

<div class="map text-minor">
    <div class="map-key">Email:</div>
    <div class="map-value spaced">support@opengroop.org</div>
</div>

<div class="map text-minor">
    <div class="map-key">Twitter:</div>
    <div class="map-value spaced">@OpenGroop</div>
</div>

</div>

</div> <!--/#content-->

<?php
    printFooter(); 
?>
