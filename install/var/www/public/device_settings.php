
<?php 
    require      ('../session/session_check_admin.php');
    require_once ('../lib/groop/src/groop_constants.php');
    require_once ('../lib/groop/src/groop_device_register.php');
    require_once ('../lib/groop/src/groop_db_register.php');
    require_once ('../lib/groop/src/groop_db_sensordata.php');
    require_once ('../page/page_template.php');

    $msg = '';

    // FORM ACTION
    if ( isset($_POST['BTN_SAVE']) && !empty($_POST['RADIO_TEMP']) ) {
        $statusAlias = DBRegister::updateAlias($_GET['id'], $_POST['TXT_ALIAS']);
        $statusUnit  = DBRegister::updateUnit($_GET['id'], $_POST['RADIO_TEMP']);
        if ($statusAlias == 0 && $statusUnit == 0) {
            $msg = "Changes have been saved..";
        } else {
            $msg = 'There seems to be a problem..(' . $statusAlias . '/' . $statusUnit .  ')' ;
        }
    }


    //REMOVE FORM ACTION
    if (isset($_POST['BTN_REMOVE']) ) {
        $register = new DeviceRegister($_GET['id']);
        if ($_POST['TXT_REMOVE'] == $register->getId() || $_POST['TXT_REMOVE'] == $register->getAlias()) {
            $statusSD = DBSensordata::delete($register->getTables());
            $statusRG = DBRegister::delete($_GET['id']);
            if ($statusSD == 0 && $statusRG == 0 ) {
                header('HTTP/1.1 303');
                header('Location: https://' . $_SERVER['HTTP_HOST'] . '/devices.php');
            exit;
            } else {
                $msg = 'There seems to be a problem..('.$status['sensordata'].$status['register'].')';
            }
        }
    }


    $register = new DeviceRegister($_GET['id']);
    $action   = $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'];
    $action2  = 'device.php?id=' . $_GET['id'];

    function isC($register) {
        if ($register->getUnit() == Constants::TEMP_C) {
            echo "checked";
        }
    }

    function isF($register) {
        if ($register->getUnit() == Constants::TEMP_F) {
            echo "checked";
        }
    }

    function printRemoveDevice($register, $action) {
        if ($register->getValid() == 0) {
            echo '<div class="map-title">DELETE SENSOR DATA</div>'.PHP_EOL;
            echo '<div class="settings-subheading">'.PHP_EOL;
            echo "<p>Deleting a sensor will delete all sensor data associated with this sensor's alias. If you would like to save this sensor's data, either backup the data through SETTINGS or save chart images.</p>".PHP_EOL;
            echo "<p>To delete this sensor, enter this sensor's id or alias into the input field and click DELETE.</p>".PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '<form role="form" action="' . $action . '" method="post">'.PHP_EOL;
            echo '<div class="map">'.PHP_EOL;
            echo '    <div class="map-key">ID or ALIAS:</div>'.PHP_EOL;
            echo '    <div class="map-value"><input name="TXT_REMOVE" required></div>'.PHP_EOL;
            echo '    <button name="BTN_REMOVE" type="submit">DELETE</button>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '</form>'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
        }
    }

    // HEADER
    printHeader();

    // BODY
    printBanner();
    printNavigation();

?>

<div id="content">
<div class="breadcrumbs spaced">
<a href="devices.php" target="_self">SENSORS</a>
/ <a href="<?php echo $action2 ?>"><?php echo $register->getAlias() ?></a>
</div>
<div><?php printTitle("SENSOR SETTINGS"); ?></div>
<div><?php echo $msg; ?></div>
<hr>
<div class="map-title">SENSOR SETTINGS</div>
<div class="map"> <!-- device id-->
    <div class="map-key" id="device-settings">ID:</div>
    <div class="map-value"><?php echo $register->getId() ?></div>
</div>

<form action="<?php htmlspecialchars($action) ?>" method="post">
    <div class="map">
        <div class="map-key" id="device-settings" >Alias:</div>
        <div class="map-value"><input type="text "name="TXT_ALIAS" value="<?php echo $register->getAlias() ?>" required></div>
    </div>
    <div class="map">
        <div class="map-key" id="device-settings">Format:</div>
        <div class="map-value">
            <div><input type="radio" name="RADIO_TEMP" value="<?php echo Constants::TEMP_C ?>" <?php isC($register) ?>>&#x2103</div>
            <div><input type="radio" name="RADIO_TEMP" value="<?php echo Constants::TEMP_F ?>" <?php isF($register) ?>>&#x2109</div>
        </div>
    </div>
    <button name="BTN_SAVE" type="submit">SAVE</button>
<hr>
</form>
<?php printRemoveDevice($register, $action) ?>
</div> <!--/#content-->

<?php
    // FOOTER
    printFooter();
?>
