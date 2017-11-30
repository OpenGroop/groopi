<?php

    if (isset($_POST['BTN_WIFI']) && !empty($_POST['TXT_ESSID']) && !empty($_POST['TXT_PSK'])) {
        exec('sudo hostapd-stop.sh');
        exec('sudo hostapd-reconf.sh ' . $_POST['TXT_ESSID']);
        exec('sudo wpa_conf.py -s ' . $_POST['TXT_ESSID'] . ' -p ' . $_POST['TXT_PSK']);
        sleep(10);
        exec('sudo hostapd-start.sh');
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
