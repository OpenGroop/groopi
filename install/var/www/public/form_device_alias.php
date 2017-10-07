<?php 
    require_once ('lib/groop/src/groop_db_register.php');

    if (isset($_POST['BTN_DEV_NAME']) && !empty($_POST['TXT_DEV_NAME'])) {
        DBRegister::updateAlias($_GET['id'],$_POST['TXT_DEV_NAME']);
        header("Location: device_settings.php?id=".$_GET['id']);
        exit;
    }

    function printAliasForm($device_id) {
        $action = $_SERVER['PHP_SELF'] . '?id=' . $device_id;
        echo '<div>'.PHP_EOL;
        echo '<div class="settings-header""><b>CHANGE ALIAS</b></div>'.PHP_EOL;
        echo '<form class="form" role="form" action="'. htmlspecialchars($action).'" method="post">'.PHP_EOL;
        echo '<div><input type="text" name="TXT_DEV_NAME" placeholder="ENTER NEW ALIAS" required></div>'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_DEV_NAME">Apply</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
    }

?>
