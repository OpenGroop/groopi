
<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('mqtt.php')

    // FORM ACTION

    // HEADER
    printHeader();

    // BODY
    printBanner();
    printNavigation();

?>

<div id="content">
    <div class="breadcrumbs">
        <a href="settings.php" target="_self">SETTINGS</a>
    </div> <!--/#breadcrumbs-->

    <div><?php printTitle("CLOUD SERVICE"); ?></div>

    <hr>

    <div class="map-title">CLOUD SERVICE STATUS</div>

        <?php printMQTT() ?>

</div> <!--/#content-->

<?php
    // FOOTER
    printFooter();
?>
