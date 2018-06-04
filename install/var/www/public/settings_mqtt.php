
<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_system.php')
    require_once ('mqtt.php')

    $mqtt = getMQTTSettings();

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

    <?php if ($mqtt['enabled'] == 0): ?>
        <form>
            
        </form>   
    <?php else: ?>

    <?php endif ?>

</div> <!--/#content-->

<?php
    // FOOTER
    printFooter();
?>
