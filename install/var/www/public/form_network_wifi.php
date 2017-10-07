<?php

    if (isset($_POST['BTN_WIFI']) && !empty($_POST['TXT_ESSID']) && !empty($_POST['TXT_PSK'])) {
        $cmd = 'sudo wpa_conf.py -s ' . $_POST['TXT_ESSID'] . ' -p ' . $_POST['TXT_PSK'];
        exec($cmd);
        sleep(10);
        header('Location: settings_network.php');
        exit;
    }

    function printWifiForm() {
        echo '<form class="form" role="form" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">'.PHP_EOL;
        echo '<div> <input type="text"     name="TXT_ESSID" placeholder="ESSID"    required/></div>'.PHP_EOL;
        echo '<div> <input type="password" name="TXT_PSK"   placeholder="PASSWORD" required/></div>'.PHP_EOL;
        echo '<div> <button type="submit"  name="BTN_WIFI">APPLY</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }
?>
