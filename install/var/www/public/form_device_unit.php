<?php 
    require_once ('lib/groop/src/groop_db_register.php');
    require_once ('lib/groop/src/groop_constants.php');

    if (isset($_POST['BTN_TEMP']) && !empty($_POST['RADIO_TEMP']))  {
        DBRegister::updateUnit($_GET['id'], $_POST['RADIO_TEMP']);
        header("Location: device_settings.php?id=".$_GET['id']);
        exit;
    }

    function printForm($device_id) {
        $action = $_SERVER['PHP_SELF'] . '?id=' . $device_id;
        echo '<form action = "'.htmlspecialchars($action).'" method = "post">'.PHP_EOL;
        echo '<div class="nav-list-block"><input type="radio" name="RADIO_TEMP" value="'.Constants::TEMP_C.'">Celsius</div>'.PHP_EOL;
        echo '<div class="nav-list-block"><input type="radio" name="RADIO_TEMP" value="'.Constants::TEMP_F.'">Fahrenheit</div>'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_TEMP">SUBMIT</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }

?>