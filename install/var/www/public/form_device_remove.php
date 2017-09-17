<?php 
    require_once ('lib/groop/src/groop_device_register.php');
    require_once ('lib/groop/src/groop_db_register.php');
    require_once ('lib/groop/src/groop_db_sensordata.php');

    if (isset($_POST['BTN_REMOVE']) ) {
        $register = new DeviceRegister($_GET['id']);
        if ($_POST['TXT_DEVICE'] == $register->getId() || $_POST['TXT_DEVICE'] == $register->getAlias()) {
            DBSensordata::delete($register);
            DBRegister::delete($register->getId());
            header('Location: devices.php');
            exit;
        }
    }

    function printForm($device_id) {
        $action = $_SERVER['PHP_SELF'] . "?id=" . $device_id;
        echo '<form role="form" action="'.$action.'" method="post">'.PHP_EOL;
        echo '<div><input type ="text" name="TXT_DEVICE" placeholder="ENTER DEVICE ID OR ALIAS" required></div>'.PHP_EOL;
        echo '<div><button type="SUBMIT" name="BTN_REMOVE">submit</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }
?>