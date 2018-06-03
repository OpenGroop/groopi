
<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');

    $msg = '';

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

    <div><?php echo $msg; ?></div>

    <hr>

    <div class="map-title">CLOUD SERVICE STATUS</div>

    <?php 
        if cloudservice is disables {
            include('settings_mqtt_enable.php')
        } else {
            include('settings_mqtt_disable.php')
        }
    ?>



</div> <!--/#content-->

<?php
    // FOOTER
    printFooter();
?>
