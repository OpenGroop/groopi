<?php
    require_once ('lib/groop/src/groop_device_register.php');

    include 'page_template.php';

    function printDSHeader(DeviceRegister $register) {
        echo '<div id="ds-header">'.PHP_EOL;
        echo '<div><h2>DEVICE SETTINGS</h2></div>'.PHP_EOL;
        echo '<div class="settings-header"><b>DEVICE:</b> '.$register->getId().'</div>'.PHP_EOL;
        echo '<div class="settings-header"><b>ALIAS:</b> <a href="device.php?id='.$register->getId().'" target="_self" >'.$register->getAlias().'</a></div>'.PHP_EOL;
        echo '</div> <!--/ds-header-->'.PHP_EOL;
    }


    function printDSNav(DeviceRegister $register) {
        echo '<div>'.PHP_EOL;
        echo '<div class="settings-header"><a href="device_settings_rename.php?id=' . $register->getId() . '" target="_self">DEVICE ALIAS</a></div>'.PHP_EOL;
        echo '<div class="settings-header"><a href="device_settings_unit.php?id=' . $register->getId() . '" target="_self">TEMPERATURE FORMAT</a></div>'.PHP_EOL;
        if ($register->getValid() == 0) {
            echo '<div class="settings-header"><a href="device_settings_remove.php?id=' . $register->getId() . '" target="_self">REMOVE DEVICE</a></div>'.PHP_EOL;
        }
        echo '</div>'.PHP_EOL;
    }

    function printDSBacklink(DeviceRegister $register) {
        echo '<div class="settings-header"><a href="device_settings.php?id=' . $register->getId() .'" target="_self">BACK TO DEVICE SETTINGS</a></div>'.PHP_EOL;
    }

?>
