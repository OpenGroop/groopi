<?php
    include 'session_check.php';
    require_once ('lib/groop/src/groop_constants.php');    
    require_once ('lib/groop/src/groop_device_sthp.php');

    // EQUATING VPD
    // $a = 17.27 * $temp;
    // $b = 237.3 + $temp;
    // $c = $a / $b;
    // $es = 0.6108 * exp($c);
    // $vpd = (100.0 - $humidity) / 100.0 * $es;
    // $vpd = round($vpd, 2);

    // INITIALIZING CHART PARAMETERS

    $sthp = new STHP($_GET['id']);
    $readings = $sthp->getCurrentReadings();

    if (empty($_GET['table'])) {
        $chart_table     = $sthp->getGranularTable();
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
            echo '<div class="device-settings"><a href="device_settings.php?id=' . $did .'" target="_self">DEVICE SETTINGS</a></div>'.PHP_EOL;
        }
    }

    // DISPLAY SUMMARY/GRANULAR GRAPHS
    function displayGraph($table, $tf, $intv, $uom) {
        if (strpos($table, "granular") > 0) {
            echo '<div class="graph"><img src="graph_granular.php?table='.$table.'&timeframe='.$tf.'&interval='.$intv.'&uom='.$uom.'" alt="graph.php"></div>'.PHP_EOL;
        }

        if (strpos($table, "summary") > 0) {
            echo '<div class="graph"><img src="graph_summary.php?table='.$table.'&timeframe='.$tf.'&interval='.$intv.'&uom='.$uom.'" alt="graph_summary.php"></div>'.PHP_EOL;
        }
    }

    include 'page_template.php';
    printHeader();
    printBanner();
?>
<div id="content">
<div><?php printTitle($sthp->getAlias()); ?></div>

<div>
<?php
    if ($sthp->getValid()) {

        echo '<div class="device-reading">'.PHP_EOL;
        echo '<div class="text-minor device-reading-ts">'.$readings['timestamp'].'</div>'.PHP_EOL;
        if ($sthp->getUnit() == Constants::TEMP_C) { echo '<div class="device-reading-block">' . round($readings['temp'], 1).'&#x2103</div>'.PHP_EOL; }
        if ($sthp->getUnit() == Constants::TEMP_F) { echo '<div class="device-reading-block">' . round($readings['temp'], 1).'&#x2109</div>'.PHP_EOL; }
        echo '<div class="device-reading-block">' .round($readings['humidity'], 1).'%RH</div>'.PHP_EOL;
        echo '<div class="device-reading-block">Lights: ' .$readings['ldr'].'</div>'.PHP_EOL;
        echo '</div> <!--/device-reading-->'.PHP_EOL;



        displayGraph($chart_table, $chart_timeframe, $chart_interval, $sthp->getUnit());

        echo '<div class="charts">'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getGranularTable().'&timeframe=24&interval=30" target="_self"> 24 HOURS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getGranularTable().'&timeframe=48&interval=60" target="_self"> 48 HOURS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getGranularTable().'&timeframe=72&interval=90" target="_self"> 72 HOURS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getGranularTable().'&timeframe=144&interval=180" target="_self"> 144 HOURS </a></div>'.PHP_EOL;
        echo '</div> <!--/charts-->'.PHP_EOL;

        echo '<div class="charts">'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getSummaryTable().'&timeframe=7&interval=1" target="_self"> 7 DAYS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getSummaryTable().'&timeframe=14&interval=1" target="_self"> 14 DAYS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getSummaryTable().'&timeframe=30&interval=1" target="_self"> 30 DAYS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getSummaryTable().'&timeframe=60&interval=1" target="_self"> 60 DAYS </a></div>'.PHP_EOL;
        echo '<div class="chart-block"><a href="device.php?id='.$sthp->getId().'&table='.$sthp->getSummaryTable().'&timeframe=90&interval=1" target="_self"> 90 DAYS </a></div>'.PHP_EOL;
        echo '</div> <!--/charts-->';
    }
    else {
        echo '<div>Device not connected</div>'.PHP_EOL;
        echo '<div>Last reading was recorded on:</div>'.PHP_EOL;
        echo '<div>' . $readings['timestamp'] .'<div><br>'.PHP_EOL;
    }
?>
</div>
<div><?php displayEdit($sthp->getId()); ?></div>
</div> <!--/content-->
<?php printFooter(); ?>
