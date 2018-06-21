<?php

    function connectWifi($essid, $ps) {

        exec('sudo wpa_connect.sh ' . $essid . ' ' . $ps);

    }
?>
