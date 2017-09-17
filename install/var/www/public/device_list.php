<?php
	require_once ('lib/groop/src/groop_constants.php');
    require_once ('lib/groop/src/groop_db_register.php');
    require_once ('lib/groop/src/groop_device_sthp.php');

    $device_ids = DBRegister::getIds();

    // ECHO DEVICE INFO
    foreach ($device_ids as $device_id) {
        $sthp = new STHP($device_id);
        $sthpCurrent = $sthp->getCurrentReadings();
        echo '<div><a href="device.php?id='.$sthp->getId().'" target="_self" >'.$sthp->getAlias().'</a></div>'.PHP_EOL;
        echo '<div>';
        if ($sthp->getValid()) {
            if ($sthp->getUnit() == Constants::TEMP_C) {
                echo '<div>'.round($sthpCurrent['temp'], 1).'&#x2103</div>'.PHP_EOL; 
            }
            if ($sthp->getUnit() == Constants::TEMP_F) {
                echo '<div>'.round($sthpCurrent['temp'], 1).'&#x2109</div>'.PHP_EOL;
            }
            echo '<div>'.round($sthpCurrent['humidity'], 1).'%RH</div>'.PHP_EOL;
            echo '<div>Lights: '.$sthpCurrent['ldr'].'</div>'.PHP_EOL;
            echo '<div>'.$sthpCurrent['timestamp'].'</div>'.PHP_EOL;
            }
        else { echo '<div>Device not connected</div>'.PHP_EOL;}
        echo '</div>'.PHP_EOL;
    }
?>