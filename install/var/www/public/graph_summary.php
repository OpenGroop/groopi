<?php // content="text/plain; charset=utf-8"

require_once ('lib/groop/src/groop_constants.php');
require_once ('lib/jpgraph/src/jpgraph.php');
require_once ('lib/jpgraph/src/jpgraph_line.php');
require_once ('lib/jpgraph/src/jpgraph_mgraph.php');



$device_table = $_GET['table'];
$timeframe    = $_GET['timeframe'];
$uom          = $_GET['uom'];

// CONNECT TO SENSORDATA.DB
try {
    $db_conn = new PDO(Constants::SENSORDATA_DB);
} catch (EXCEPTION $e) {die('Unable to connect: ' . $e->getMessage());}


try {
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_conn->beginTransaction();
    $sql = "SELECT timestamp, tappv, tadpv, happv, hadpv FROM ".$device_table.
        " WHERE timestamp BETWEEN strftime('%s', 'now', '-".$timeframe.
        " days') AND strftime('%s','now')";
    $statement = $db_conn->query($sql);
    $result = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    $db_conn = null;
} catch (EXCEPTION $e) { $database->rollback(); echo 'FAILED: ' . $e->getMessage(); }


$timestamps = [];
$tappvList  = [];
$tadpvList  = [];
$happvList  = [];
$hadpvList  = [];

$tsArray = [];

foreach ($result as $row) {
    $tsArray[]   = $row['timestamp'];
    $timestamps[] = date("D M j", $row['timestamp']);
    $tappvList[] = $row['tappv'];
    $tadpvList[] = $row['tadpv'];
    $happvList[] = $row['happv'];
    $hadpvList[] = $row['hadpv'];
}

$tsStart = date("F j, Y", $tsArray[0]);
$tsEnd   = date("F j, Y", $tsArray[sizeof($tsArray)-1]);

$graphTitle1 = $timeframe . ' Days - ' . $tsStart . ' to ' . $tsEnd;
$graphTitle2 = "\n\rAVERAGE DAY TEMP (RED) : AVERAGE NIGHT TEMP (BLUE)";
$graphTitle3 = "\n\rAVERAGE DAY HUMIDITY (RED) : AVERAGE NIGHT HUMIDITY (BLUE)";



// file_put_contents('filename.txt', print_r($timestamps, true));

if ($uom == "temp_f") {
    foreach ($tappvList as &$pValue) {
        $pValue = ($pValue * 1.8) + 32;
    }
    foreach ($tadpvList as &$dValue) {
        $dValue = ($dValue * 1.8) + 32;
    }
}

if ($uom == "temp_f") {
    $temp_lo = 35;
    $temp_hi = 105;
} else {
    $temp_lo = 0;
    $temp_hi = 40;
}
// Graph
$tempGraph = new Graph(960,650);
$tempGraph->graph_theme = null;
$tempGraph->SetScale('textint', $temp_lo, $temp_hi);
$tempGraph->SetMargin(55,20,60,125);
$tempGraph->title->SetMargin(20);
$tempGraph->title->Set($graphTitle1 . $graphTitle2);
$tempGraph->title->SetFont(FF_DV_SANSSERIF,FS_NORMAL,20);
$tempGraph->SetTickDensity(TICKD_SPARSE, TICKD_VERYSPARSE);

// Grids
$tempGraph->xgrid->Show();
$tempGraph->ygrid->SetFill(true,'lightblue@0.5','white');

// x-Axis
$tempGraph->xaxis->SetTickLabels($timestamps);
$tempGraph->xaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,14);
$tempGraph->xaxis->SetLabelAngle(90);

// y-Axis
$tempGraph->yaxis->SetTextLabelInterval(2);
$tempGraph->yaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,18);

// Plots
$tappvPlot = new LinePlot($tappvList);
$tadpvPlot = new LinePlot($tadpvList);

// pp-Plot
$tappvPlot->SetColor('red');
$tappvPlot->SetWeight(3);
$tappvPlot->mark->SetType(MARK_FILLEDCIRCLE);
$tappvPlot->mark->SetFillColor('red');

// dp-Plot
$tadpvPlot->SetColor('blue');
$tadpvPlot->SetWeight(3);
$tadpvPlot->mark->SetType(MARK_FILLEDCIRCLE);
$tadpvPlot->mark->SetFillColor('blue');

// Add & Stroke
$tempGraph->Add($tappvPlot);
$tempGraph->Add($tadpvPlot);
//$tempGraph->Stroke();

//////////////////////
/// HUMIDITY GRAPH ///
//////////////////////

// Graph
$humGraph = new Graph(960,650);
$humGraph->graph_theme = null;
$humGraph->SetScale('textint', 0, 99);
$humGraph->SetMargin(55,20,60,125);
$humGraph->title->SetMargin(20);
$humGraph->title->Set($graphTitle1 . $graphTitle3);
$humGraph->title->SetFont(FF_DV_SANSSERIF,FS_NORMAL,20);
$humGraph->SetTickDensity(TICKD_NORMAL, TICKD_VERYSPARSE);

// Grids
$humGraph->xgrid->Show();
$humGraph->ygrid->SetFill(true,'lightblue@0.5','white');

// x-Axis
$humGraph->xaxis->SetTickLabels($timestamps);
$humGraph->xaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,14);
$humGraph->xaxis->SetLabelAngle(90);

// y-Axis
$humGraph->yaxis->SetTextLabelInterval(2);
$humGraph->yaxis->SetFont(FF_DV_SANSSERIF,FS_NORMAL,18);

// Plots
$happvPlot = new LinePlot($happvList);
$hadpvPlot = new LinePlot($hadpvList);

// pp-Plot
$happvPlot->SetColor('red');
$happvPlot->SetWeight(3);
$happvPlot->mark->SetType(MARK_FILLEDCIRCLE);
$happvPlot->mark->SetFillColor('red');

// dp-Plot
$hadpvPlot->SetColor('blue');
$hadpvPlot->SetWeight(3);
$hadpvPlot->mark->SetType(MARK_FILLEDCIRCLE);
$hadpvPlot->mark->SetFillColor('blue');

// Add & Stroke
$humGraph->Add($happvPlot);
$humGraph->Add($hadpvPlot);
//$humGraph->Stroke();

///////////////////////
/// MGraph Settings ///
///////////////////////

$mgraph = new MGraph();
$mgraph->SetImgFormat('jpeg',100);
//$mgraph->SetFrame(true,'darkgray',2);
$mgraph->AddMix($tempGraph,0,0);
$mgraph->AddMix($humGraph,0,666);
$mgraph->Stroke();


//foreach ($result as $row) {
//  $timestamps[] = date("D M j", $row['timestamp']);
//  $datayy[] = $row['tappv'];
//  $datayy[] = $row['tadpv'];
//  $datayy[] = $row['tminv'];
//  $datayy[] = $row['tmaxv'];
//  $datayy[] = $row['taov'];
//  }

// Data must be in the format : open,close,min,max,median
//$datay = array(
//    34,42,27,45,36,
//    55,25,14,59,40,
//    15,40,12,47,23,
//    62,38,25,65,57,
//    38,49,32,64,45);


// // Create a ne   w stock plot
// $tempPlot = new BoxPlot($datayy);
// $tempPlot->SetCenter(false);

// // Setup URL target for image map
// $tempPlot->SetCSIMTargets(array('#1','#2','#3','#4','#5'));

// // Width of the bars (in pixels)
// $tempPlot->SetWidth(96);


// Add the plot to the graph and send it back to the browser
// $graph->Add($tempPlot);
// $graph->StrokeCSIM('graph_summary.php')
?>
