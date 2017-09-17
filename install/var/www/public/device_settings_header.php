<?php
    require_once ('lib/groop/src/groop_device_register.php');

    function printHeader(DeviceRegister $register) {
        echo '<div>DEVICE SETTINGS:</div>'.PHP_EOL;
        echo '<div>DEVICE: '.$register->getId().'</div>'.PHP_EOL;
        echo '<div>ALIAS: <a href="device.php?id='.$register->getId().'" target="_self" >'.$register->getAlias().'</a></div>'.PHP_EOL;
    }

    function printHeaderBacklink(DeviceRegister $register) {
        printHeader($register);
        echo '<div><a href="device_settings.php?id=' . $register->getId() .'" target="_self">device settings</a></div>'.PHP_EOL;
    }
?>