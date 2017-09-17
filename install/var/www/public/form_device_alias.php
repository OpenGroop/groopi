<?php 
    require_once ('lib/groop/src/groop_db_register.php');

    if (isset($_POST['BTN_DEV_NAME']) && !empty($_POST['TXT_DEV_NAME'])) {
        DBRegister::updateAlias($_GET['id'],$_POST['TXT_DEV_NAME']);
        header("Location: device_settings.php?id=".$_GET['id']);
        exit;
    }

    function printForm($device_id) {
        $action = $_SERVER['PHP_SELF'] . '?id=' . $device_id;
        echo '<form role = "form" action = "'. htmlspecialchars($action).'" method = "post">'.PHP_EOL;
        echo '<div><input type ="text" name="TXT_DEV_NAME" placeholder="ENTER NEW ALIAS" required autofocus></div>'.PHP_EOL;
        echo '<div><button type ="submit" name ="BTN_DEV_NAME">Apply</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }

?>
