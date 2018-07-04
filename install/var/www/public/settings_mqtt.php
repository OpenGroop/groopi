<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_system.php');

    // FORM ACTION

    if (isset($_POST['BTN_MQTT'])) {
        $settings = array($_POST['TXT_HOST'], (int) $_POST['TXT_PORT'], (int) $_POST['TXT_ACCT_ID'], $_POST['TXT_PASSWD'], (int) $_POST['RADIO_ENABLE'], -2);
        DBSystem::setMQTTSettings($settings);
        header('HTTP/1.1 303');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $mqtt = DBSystem::getMQTTSettings();

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

    <div class="map-title">SERVICE STATUS</div>
    <div class="settings-subheading">
        <?php if ($mqtt['enable'] == 0): ?>
            <p>Service is currently <b>disabled</b>.</p>
        <?php else: ?>
            <p>Service is currently <b>enabled</b>.</p>
            <p>Connection status is <b><?php echo $mqtt['conn_status']?></b></p>
        <?php endif ?>
    </div>

    <hr>

    <form class="form" role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

        <div class="map-title">ENABLE SERVICE</div>
        <div class="map">
            <div class="map-key"></div>
            <div class="map-value">
                <div><input type="radio" name="RADIO_ENABLE" value="1" <?php if ($mqtt['enable'] == 1) echo "checked" ?>>Enable</div>
                <div><input type="radio" name="RADIO_ENABLE" value="0" <?php if ($mqtt['enable'] == 0) echo "checked" ?>>Disable</div>
            </div>
        </div>

        <hr>

        <div class="map-title">SETTINGS</div>
        <div class="map">
            <div class="map-key">Host:</div>
            <div class="map-value spaced"><input type="text" name="TXT_HOST" value="<?php echo $mqtt['host']?>"></div>
        </div>

        <hr>

        <div class="map">
            <div class="map-key">Port:</div>
            <div class="map-value spaced"><input type="text" name="TXT_PORT" value="<?php echo $mqtt['port']?>"></div>
        </div>

        <hr>

        <div class="map">
            <div class="map-key">Account ID:</div>
            <div class="map-value spaced"><input type="text" name="TXT_ACCT_ID" value="<?php echo $mqtt['acct_id']?>"></div>
        </div>

        <hr>

        <div class="map">
            <div class="map-key">Password:</div>
            <div class="map-value spaced"><input type="text" name="TXT_PASSWD" value="<?php echo $mqtt['password']?>"></div>
        </div>

        <button type="submit" name="BTN_MQTT">Apply</button>

    </form>
</div> <!--/#content-->

<?php
    // FOOTER
    printFooter();
?>
