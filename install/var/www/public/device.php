<?php
    include 'session_check.php';
    include 'constants.php';

    // INITIALIZED DEVICE PARAMETERS
    $device_id             = $_GET['id'];
    $device_alias          = "";
    $device_valid          = "";
    $device_granular_table = "";
    $device_summary_table  = "";
    $temp_uom              = "";

    $timestamp= "";
    $temp     = "";
    $humidity = "";
    $ldr      = "";


    // CONNECT TO REGISTER DATABASE
    try {
        $register_db = new PDO(REGISTER_DB);
    } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    // SELECT ALL REGISTER DATA FOR DEVICE 
    try {
        $register_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $register_db->beginTransaction();
        $statement = $register_db->prepare("SELECT * FROM device_registers WHERE device_id=?");
        $statement->execute(array($device_id));
        $statement->bindColumn('device_alias', $device_alias);
        $statement->bindColumn('granular_table', $device_granular_table);
        $statement->bindColumn('summary_table', $device_summary_table);
        $statement->bindColumn('valid', $device_valid);
        $statement->bindColumn('uom', $temp_uom);
        $statement->fetch(PDO::FETCH_BOUND);
        $statement    = null;
        $register_db  = null;
    } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }



    // CONNECT TO SENSORDATA DATABASE
    try {
        $sensordata_db = new PDO(SENSORDATA_DB);
    } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

    // SELECT CURRENT TEMPERATURE (CELSIUS), HUMIDITY, AND LIGHT OUTPUT FOR DEVICE 
    try {
        $sensordata_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sensordata_db->beginTransaction();
        $sql = "SELECT timestamp,".$temp_uom.", humidity, ldr FROM ".$device_granular_table.
        " WHERE id = (SELECT MAX(id) FROM ".$device_granular_table.") ";
        $statement = $sensordata_db->query($sql);
        $statement->bindColumn($temp_uom, $temp);
        $statement->bindColumn('humidity', $humidity);
        $statement->bindColumn('ldr', $ldr);
        $statement->bindColumn('timestamp', $timestamp);
        $statement->fetch();
        $statement = null;
        $sensordata_db = null;
    } catch (EXCEPTION $e) { echo 'FAILED: ' . $e->getMessage(); }

    if ($ldr == 0) { $ldr = 'OFF';}
    else           { $ldr = 'ON' ;}

    $timestamp = date("l, F j, g:i:s a", $timestamp);

    // EQUATING VPD
    $a = 17.27 * $temp;
    $b = 237.3 + $temp;
    $c = $a / $b;
    $es = 0.6108 * exp($c);
    $vpd = (100.0 - $humidity) / 100.0 * $es;
    $vpd = round($vpd, 2);

    // INITIALIZING CHART PARAMETERS

    if (empty($_GET['table'])) {
        $chart_table     = $device_granular_table;
        $chart_timeframe = 24;
        $chart_interval  = 30;
    } else {
        $chart_table     = $_GET['table'];
        $chart_timeframe = $_GET['timeframe'];
        $chart_interval  = $_GET['interval'];
    }



    // DISPLAY LINK TO DEVICE SETTINGS FOR ADMIN ACCOUNT
    function displayEdit($did) {
        if ($_SESSION['userid'] < 3) {
            echo '<div><a class="setting-header" href="device_settings.php?id=' . $did .'" target="_self">device settings</a></div>';
        }
    }

    // DISPLAY LINKS TO CHARTS
    function displayGraph($table, $tf, $intv, $uom) {
        if (strpos($table, "granular") > 0) {
            echo '<div class="title-1">' . $tf . ' Hours</div>';
            echo '<img src="graph_granular.php?table='.$table.'&timeframe='.$tf.'&interval='.$intv.'&uom='.$uom.'" alt="graph.php">';
        }
        if (strpos($table, "summary") > 0) {
            echo '<div class="title-1" >' . $tf . ' Days</div>';
            echo '<img src="graph_summary.php?table='.$table.'&timeframe='.$tf.'&interval='.$intv.'&uom='.$uom.'" alt="graph_summary.php">';
    //      include 'graph_summary.php';
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo "Sentry | " . $device_alias ?></title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />      </style>
    </head>
    <body>
        <?php include 'nav_main.php'; ?>
        <br>

        <div class="title">DEVICES</div><br>

        <div class="title"><?php echo $device_alias; ?></div>
        <div class="setting-header">DEVICE: <?php echo $device_id;?></div>
        <?php displayEdit($device_id); ?><br>
        <?php
            echo '<div class="reading">';
            if ($device_valid) {
                if ($temp_uom == TEMP_C) { echo '<div class="reading-block">' . round($temp, 1).'&#x2103</div>'; }
                if ($temp_uom == TEMP_F) { echo '<div class="reading-block">' . round($temp, 1).'&#x2109</div>'; }
                echo '<div class="reading-block">' .round($humidity, 1).'%RH</div>';
                echo '<div class="reading-block">Lights: ' .$ldr.'</div>';
                echo '<div class="reading-block reading-time">'.$timestamp.'</div>';
                displayGraph($chart_table, $chart_timeframe, $chart_interval, $temp_uom);
                echo '<div class="nav-charts">';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_granular_table.'&timeframe=24&interval=30" target="_self"> 24 Hours </a></div>';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_granular_table.'&timeframe=48&interval=60" target="_self"> 48 Hours </a></div>';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_granular_table.'&timeframe=72&interval=90" target="_self"> 72 Hours </a></div>';
                echo '</div>';
                echo '<div class="nav-charts">';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_summary_table.'&timeframe=7&interval=1" target="_self"> 7 Days </a></div>';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_summary_table.'&timeframe=14&interval=1" target="_self"> 14 Days </a></div>';
                echo '<div class="nav-charts-block"><a href="device.php?id='.$device_id.'&table='.$device_summary_table.'&timeframe=30&interval=1" target="_self"> 30 Days </a></div>';
                echo '</div>';
            }
            else {
                echo '<div class="reading-block-nc">Device not connected</div>';
                echo '<div class="reading-block-nc">Last reading was recorded on:</div>';
                echo '<div class="reading-block-nc">' . $timestamp .'<div><br>';
            }
            echo '</div>';
        ?>
<!--        <div class="nav-charts">
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id?>&table=<?php echo $device_granular_table ?>&timeframe=24&interval=30" target="_self"> 24 Hours </a></div>
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id?>&table=<?php echo $device_granular_table ?>&timeframe=48&interval=60" target="_self"> 48 Hours </a></div>
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id?>&table=<?php echo $device_granular_table ?>&timeframe=72&interval=90" target="_self"> 72 Hours </a></div>
        </div>
        <div class="nav-charts">
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id ?>&table=<?php echo $device_summary_table ?>&timeframe=7&interval=1" target="_self"> 7 Days </a></div>
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id ?>&table=<?php echo $device_summary_table ?>&timeframe=14&interval=1" target="_self"> 14 Days </a></div>
            <div class="nav-charts-block"><a href="device.php?id=<?php echo $device_id ?>&table=<?php echo $device_summary_table ?>&timeframe=30&interval=1" target="_self"> 30 Days </a></div>
        </div>
 -->        <br>
    </body>
</html>
