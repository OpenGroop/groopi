<?php
    require_once ('../lib/groop/src/groop_constants.php');
    require_once ('../lib/groop/src/groop_db_register.php');
    require_once ('../lib/groop/src/groop_device_sthp.php');

    function printDevicesList() {
        $device_ids = DBRegister::getIds();
        echo '<div class="devices-list">'.PHP_EOL;
        foreach ($device_ids as $device_id) {
            $sthp = new STHP($device_id);
            $sthpCurrent = $sthp->getCurrentReadings();
            echo '<div class="devices-alias"><a href="device.php?id='.$sthp->getId().'" target="_self" >'.$sthp->getAlias().'</a></div>'.PHP_EOL;
            echo '<div class="devices-reading">';
            if ($sthp->getValid()) {
                if ($sthp->getUnit() == Constants::TEMP_C) {
                    echo '<div class="devices-reading-block">'.round($sthpCurrent['temp'], 1).'&#x2103</div>'.PHP_EOL; 
                }
                if ($sthp->getUnit() == Constants::TEMP_F) {
                    echo '<div class="devices-reading-block">'.round($sthpCurrent['temp'], 1).'&#x2109</div>'.PHP_EOL;
                }
                echo '<div class="devices-reading-block">'.round($sthpCurrent['humidity'], 1).'%RH</div>'.PHP_EOL;
                echo '<div class="devices-reading-block">Lights: '.$sthpCurrent['ldr'].'</div>'.PHP_EOL;
                echo '<div class="text-minor devices-reading-ts">'.$sthpCurrent['timestamp'].'</div>'.PHP_EOL;
                }
            else { echo '<div class="devices-reading-nc">Device not connected</div>'.PHP_EOL;}
            echo '</div> <!--/.devices-reading-->'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
        }
        echo '</div> <!--/.devices-list-->'.PHP_EOL;
    }
?>
