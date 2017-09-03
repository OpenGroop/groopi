<?php 
    include 'session_check.php';
    include 'constants.php';

    try {
        $database = new PDO(REGISTER_DB);
    } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    try {
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->beginTransaction();
        $sql = "SELECT  * FROM device_registers ORDER BY device_alias";
        $statement = $database->query($sql);
        $result = $statement->fetchAlL(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        $statement = null;
        $database = null;
    } catch(EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

    

    function getDeviceData($array) {
        $data = array('temp'=>'', 'humidity'=>'', 'ldr'=>'','timestamp'=>'');
        // CONNECT TO SENSORDATA DATABASE
        try {
            $sensordata_db = new PDO(SENSORDATA_DB);
        } catch(EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

        // SELECT CURRENT TEMPERATURE (CELSIUS), HUMIDITY, AND LIGHT OUTPUT FOR DEVICE 
        try {
            $sensordata_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sensordata_db->beginTransaction();
            $sql = "SELECT timestamp,".$array['uom'].", humidity, ldr FROM ".$array['granular_table'].
            " WHERE id = (SELECT MAX(id) FROM ".$array['granular_table'].") ";
            $statement = $sensordata_db->query($sql);
            $statement->bindColumn($array['uom'], $data['temp']);
            $statement->bindColumn('humidity', $data['humidity']);
            $statement->bindColumn('ldr', $data['ldr']);
            $statement->bindColumn('timestamp', $data['timestamp']);
            $statement->fetch();
            $statement = null;
            $sensordata_db = null;
        } catch(EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

        if ($data['ldr'] == 0) { $data['ldr'] = 'OFF'; } 
        else                   { $data['ldr'] = 'ON';  }

        $data['timestamp'] = date("g:ia", $data['timestamp']);  
        
        return $data;   
    }

    function listDevices($result) {
        // ECHO DEVICE INFO
        foreach ($result as $array) {
            $data = getDeviceData($array);
            echo '<div class="nav-list-block"><a href="device.php?id='.$array['device_id'].'" target="_self" >'.$array['device_alias'].'</a></div>';
            echo '<div>';
            if ($array['valid']) {
                if ($array['uom'] == TEMP_C) { echo '<div class="nav-list-block reading-block">'.round($data['temp'], 1).'&#x2103</div>'; }
                if ($array['uom'] == TEMP_F) { echo '<div class="nav-list-block reading-block">'.round($data['temp'], 1).'&#x2109</div>'; }
                echo '<div class="nav-list-block reading-block">'.round($data['humidity'], 1).'%RH</div>';
                echo '<div class="nav-list-block reading-block">Lights: '.$data['ldr'].'</div>';
                echo '<div class="nav-list-block reading-block">'.$data['timestamp'].'</div>';
                }
            else { echo '<div class="nav-list-block">Device not connected</div>';}
            echo '</div><br>';
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Devices | Sentry</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    </head>
    <body>
        <?php include 'nav_main.php'; ?><br>
        <div class="title"> DEVICES </div><br>
        <div><?php listDevices($result); ?></div>
    </body>
</html>
