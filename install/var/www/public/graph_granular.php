<?php // content="text/plain; charset=utf-8"
session_start();
if(!$_SESSION['valid'] == 1) {
    exit;
}

date_default_timezone_set('America/Toronto');

require_once ('../lib/groop/src/groop_constants.php');
require_once ('../lib/jpgraph/src/jpgraph.php');
require_once ('../lib/jpgraph/src/jpgraph_line.php');
require_once ('../lib/jpgraph/src/jpgraph_date.php');
require_once ('../lib/jpgraph/src/jpgraph_mgraph.php');

$device_table = $_GET['table'];
$timeframe    = $_GET['timeframe'];
$interval     = $_GET['interval'];
$uom          = $_GET['uom'];

try {
    $database = new PDO(Constants::SENSORDATA_DB);
} catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

try {
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->beginTransaction();
    $sql = "SELECT timestamp,".$uom.", humidity, ldr FROM ".$device_table.
        " WHERE timestamp BETWEEN strftime('%s', 'now', '-".$timeframe.
        " hours') AND strftime('%s','now') AND id % ".$interval." = 0";
    $statement = $database->query($sql);
    $result = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    $database = null;
} catch (EXCEPTION $e) { $database->rollback(); echo 'FAILED: ' . $e->getMessage(); }

$tsArray   = [];
$ldrArray  = [];
$tempArray = [];
$humArray  = [];

foreach($result as $array) {
    array_push($tsArray,$array['timestamp']);
    array_push($ldrArray,$array['ldr'] / 10);
    array_push($tempArray,$array[$uom]);
    array_push($humArray,$array['humidity']);
}

for($i = 0; $i < sizeof($tsArray); $i++) {
    if(!is_numeric($tempArray[$i])) {
        if($i == 0) { $tempArray[$i] = $tempArray[$i + 1]; }
        else        { $tempArray[$i] = $tempArray[$i - 1]; }
    }
    if(!is_numeric($humArray[$i])) {
        if($i == 0) { $humArray[$i] = $humArray[$i + 1]; }
        else        { $humArray[$i] = $humArray[$i - 1]; }
    }
    if(!is_numeric($ldrArray[$i])) {
        if($i == 0) { $ldrArray[$i] = $ldrArray[$i + 1]; }
        else        { $ldrArray[$i] = $ldrArray[$i - 1]; }
    }
}

$tsStart = date("F j, Y", $tsArray[0]);
$tsEnd   = date("F j, Y", $tsArray[sizeof($tsArray)-1]);

$graphTitle1 = $timeframe . ' Hours - ' . $tsStart . ' to ' . $tsEnd;
$graphTitle2 = "\n\rTEMPERATURE (RED) : HUMIDITY (BLUE) : LIGHT (BLACK)";

if ($uom == Constants::TEMP_F) {
    $temp_lo = 35;
    $temp_hi = 105;
} else {
    $temp_lo = 0;
    $temp_hi = 40;
}

$width = 925;
$height = 650;
// file_put_contents('filename.txt', print_r($tsArray, true));


// Create the graph. These two calls are always required
$tempGraph = new Graph($width,$height);
$tempGraph->SetScale('datint',$temp_lo, $temp_hi);

$tempGraph->SetYScale(0, 'lin', 0, 99);
$tempGraph->SetYScale(1, 'lin', 0, 100);

$tempGraph->graph_theme = null;
$tempGraph->SetTickDensity(TICKD_SPARSE, TICKD_SPARSE);

$tempGraph->SetFrame();
$tempGraph->SetMargin(50,50,80,110);
$tempGraph->SetMarginColor('white');
$tempGraph->title->Set($graphTitle1 . $graphTitle2);
$tempGraph->title->SetMargin(20);
$tempGraph->title->SetFont(FF_DV_SANSSERIF,FS_NORMAL,20);

// Grids
$tempGraph->xgrid->Show();
$tempGraph->ygrid->SetFill(true,'lightblue@0.5','white');

// x-Axis
$tempGraph->xaxis->HideFirstTickLabel();
$tempGraph->xaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,18);
$tempGraph->xaxis->SetLabelAngle(45);
$tempGraph->xaxis->scale->SetDateFormat('H:i');

// y-Axis
$tempGraph->yaxis->SetColor('red');
$tempGraph->yaxis->SetTextLabelInterval(2);
$tempGraph->yaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,20);

$tempGraph->ynaxis[0]->SetColor('blue');
$tempGraph->ynaxis[0]->SetFont(FF_DV_SANSSERIF,FS_NORMAL,24);
$tempGraph->ynaxis[0]->SetTextLabelInterval(3);
$tempGraph->ynaxis[0]->SetTickSize(20);

$tempGraph->ynaxis[1]->Hide();

// Plots
$tempPlot=new LinePlot($tempArray, $tsArray);
$ldrPlot=new LinePlot($ldrArray, $tsArray);
$humPlot = new LinePlot($humArray, $tsArray);

$tempPlot->SetColor('red');
$tempPlot->SetWeight(2);

$ldrPlot->SetColor('black');

$humPlot->SetColor('blue');
$humPlot->SetWeight(2);

// Add & Stroke
$tempGraph->Add($tempPlot);

$tempGraph->AddY(0, $humPlot);
$tempGraph->AddY(1, $ldrPlot);

$tempGraph->Stroke();

?>
