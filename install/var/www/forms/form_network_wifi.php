<?php

    function connectWifi($essid, $ps) {

        $w = [];
        $x = [];
        $y = [];
        $z = [];

        $status = [
            'w' => '',
            'x' => '',
            'y' => '',
            'z' => '',
        ];

        exec('sudo hostapd-stop.sh', $w, $status['w'] );
        exec('sudo hostapd-reconf.sh ' . $essid, $x, $status['x'] );
        exec('sudo wpa_conf.py -s ' . $essid . ' -p ' . $ps, $y, $status['y']);
        sleep(10);
        exec('sudo hostapd-start.sh', $z, $status['z']);

        return $status;
    }
?>
