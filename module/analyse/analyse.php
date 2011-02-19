<?php
/**
 * Wrapper for JpGraph
 *
 * http://jpgraph.net/
 *
 * @ingroup    Module-Analyse
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

// -----------------------------------------------------------------------------
// Some configuration

$MinMaxWidth = array( 4, 8 );

$XYGrace     = array( 3, 8 ); # x/y grace in percent

// -----------------------------------------------------------------------------
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

$time = time();

$jppath = 'jpgraph';

/**
 * Needs to patch JpGraph to de-TTF
 *
 * - gd_image.inc.php
 *   comment from line 91 ALL defs EXCEPT those for FF_FONT1
 *
 * - jpgraph_ttf.inc.php from line 147
 *   // Default font family
 *   //define('FF_DEFAULT', FF_DV_SANSSERIF);
 *   define('FF_DEFAULT', FF_FONT1);
 */
require_once $jppath.'/jpgraph.php';

$data = FALSE;

if (!empty($_GET['data'])) {
  // 1. via ?data=...
  $data = $_GET['data'];
} elseif (!empty($_SERVER['QUERY_STRING'])) {
  // 2. via query ?...
  $data = $_SERVER['QUERY_STRING'];
}

if (!$data) {
  // error handling via jpgraph ...
  $e = new JpGraphErrObjectImg();
  $e->Raise('No data!'."\n\n".'There are 2 ways to provide data:'."\n\n"
           .'1. as ...?<data>'."\n"
           .'2. or as ...?data=<data>');
}

$data = base64_decode($data);

if (function_exists('gzuncompress')) {
  // parameter CAN but not must be compressed
  ob_start();
  // pick warnings of de-compression errors
  eval('$_data = gzuncompress($data);');
  if (!ob_get_clean()) $data = $_data;
}

list (
  $xsize, $ysize,
  $bid,  $legend,
  $mid,  $mlegend,
  $hmid, $hlegend,
  $data
) = unserialize($data);

$ends = $data['endts'];
$datax = array_map('fTS', $data['endts']);
$datay = $data['bid'];
$databids = $data['bids'];

$MinMaxBids = $x = $y = array(1E+10,0);

$XY = $linebids = $linemid = $linehmid = array();

foreach ($databids as $id => $bids) {
  $XY[md5($datax[$id].$datay[$id])] = array($bids, $ends[$id]);
  if ($bids < $MinMaxBids[0]) $MinMaxBids[0] = $bids;
  if ($bids > $MinMaxBids[1]) $MinMaxBids[1] = $bids;
  // self calculation of x grace
  if ($datax[$id] < $x[0]) $x[0] = $datax[$id];
  if ($datax[$id] > $x[1]) $x[1] = $datax[$id];
  // self calculation of y grace
  if ($datay[$id] < $y[0]) $y[0] = $datay[$id];
  if ($datay[$id] > $y[1]) $y[1] = $datay[$id];
  $linebids[] = $bid;
  $linemid[]  = $mid;
  $linehmid[] = $hmid;
}

$MinMaxScale = (count($datax) == 1 OR ($MinMaxBids[1]-$MinMaxBids[0]) == 0)
             ? 1
             : ($MinMaxWidth[1]-$MinMaxWidth[0]) / ($MinMaxBids[1]-$MinMaxBids[0]);

// move at least bid into graph...
if ($y[0] > $bid) $y[0] = $bid;
if ($y[1] < $bid) $y[1] = $bid;

// self calculation of graces
$Grace[0] = $y[0] - ($y[1]-$y[0])*$XYGrace[1]/100;
$Grace[0] = floor($Grace[0]);
// no negative prices ;-)
if ($Grace[0] < 0) $Grace[0] = '0';
$Grace[1] = $y[1] + ($y[1]-$y[0])*$XYGrace[1]/100;

$Grace[2] = $x[0] - ($x[1]-$x[0])*$XYGrace[0]/100;
if ($Grace[2] < 0) $Grace[2] = '1';
$Grace[3] = $x[1] + ($x[1]-$x[0])*$XYGrace[0]/100;

include $jppath.'/jpgraph_date.php';

// Setup a basic graph
$graph = new Graph($xsize, $ysize, 'auto');

$graph->SetScale('datlin', $Grace[0], $Grace[1], $Grace[2], $Grace[3]);

if ($xsize < 500) $graph->SetMargin(55, 15, 25, 40);
else              $graph->SetMargin(65, 15, 25, 50);

// Setup graph colors
$graph->SetFrame(FALSE);
$graph->SetMarginColor('white');

// X
$graph->xaxis->SetPos('min');
$graph->xaxis->scale->SetDateFormat('H:i');
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->HideTicks(TRUE, FALSE);
$graph->xgrid->Show();
$graph->xgrid->SetColor('gray@0.8');

// Y
$graph->yaxis->SetLabelFormat('%0.2f');
$graph->yaxis->HideTicks(TRUE, FALSE);
$graph->ygrid->SetColor('gray@0.8');

// Legend
$graph->legend->SetColor('black', 'white');
#$graph->legend->SetFillColor('#E0E0E0');
#$graph->legend->SetShadow(TRUE);
$graph->legend->SetPos(0.015, 0.01);

$flegend = '%1$.2f : %2$s';

include $jppath.'/jpgraph_line.php';

// Lines
if ($bid > 0) {
  // Create bid line as lin. plot
  $lp1 = new LinePlot($linebids, $datax);
#  $lp1->SetColor('FF0000');
  $lp1->SetLegend(sprintf($flegend, $bid, $legend));
  $graph->Add($lp1);
}

if ($mid > 0) {
  // Arithmetical average
  $lp2 = new LinePlot($linemid, $datax);
#  $lp2->SetColor('green');
  $lp2->SetLegend(sprintf($flegend, $mid, $mlegend));
  $graph->Add($lp2);
}

if ($hmid > 0) {
  // Harmonic average
  $lp3 = new LinePlot($linehmid, $datax);
#  $lp3->SetColor('blue');
  $lp3->SetLegend(sprintf($flegend, $hmid, $hlegend));
  $graph->Add($lp3);
}

include $jppath.'/jpgraph_scatter.php';

/*
if (count($ends) >= 3) {
  // Moving average, 3 or more auctions required
  $e = array();
  foreach ($ends as $id => $end) {
    // Prepare for sort by end time only, IGNORE date
    $e[] = array(fTS($end), $end, 0, $datay[$id], $databids[$id]);
  }
  usort($e, function($a,$b){return $a[0]-$b[0];});

  $max = count($e);
  for ($i=0; $i<$max; $i++) {
    if ($i == 1) {
      // ignore 1st...
      $e[$i-1][2] = ($e[$i-1][3] + $e[$i][3]) / 3;
    }
    if ($i >= 1 AND $i <= $max-2) {
      $e[$i][2]   = ($e[$i-1][3] + $e[$i][3] + $e[$i+1][3]) / 3;
    }
    if ($i == $max-2) {
      // ... and last value
      $e[$i+1][2] = ($e[$i][3] + $e[$i+1][3]) / 2;
    }
  }
  usort($e, function($a,$b){return $a[1]-$b[1];});

  $lineavg = array();
  foreach ($e as $d) $lineavg[] = $d[2];

  $lp4 = new ScatterPlot($lineavg, $datax);
#  $lp4->SetColor('yellow');
#  $lp4->SetLegend(sprintf($flegend, $hmid, $hlegend));
  $lp4->SetLegend('Moving average');
  $graph->Add($lp4);
}
*/

// Create the scatter plot
$sp1 = new ScatterPlot($datay, $datax);
$sp1->mark->SetType(MARK_FILLEDCIRCLE);

// Specify the callback
$sp1->mark->SetCallbackYX('fCallbackYX');

// Plot to the graph
$graph->Add($sp1);

// Send to browser
$graph->Stroke();

/**
 * Reformat time stamp
 *
 * @param integer $ts Time stamp
 * @return integer
 */
function fTS ( $ts ) {
  $h = explode(':',date('H:i:s', $ts));
  $h = ($h[0]-1)*60*60 + $h[1]*60 + $h[2];
  // move to the end of display
  if ($h < 0) $h += 86400;
  return $h;
}

/**
 * Callback function for graph point formating
 *
 * @param integer $Y
 * @param integer $X
 * @return array
 */
function fCallbackYX ( $Y, $X ) {
  global $XY, $MinMaxScale, $MinMaxWidth, $MinMaxBids, $time;
  $point = md5($X.$Y);
  $bids = $XY[$point][0];
  $size = ($MinMaxBids[1]-$bids) * $MinMaxScale;
  $color = $XY[$point][1] < $time
         ? getGradientColor('AAFFAA','FF6666',$MinMaxBids[1]-$MinMaxBids[0],$bids-$MinMaxBids[0])
         : 'yellow';
  $width = $MinMaxWidth[0] + $size;
  return array( $width, NULL, $color, NULL, NULL );
}

/**
 * Calculate graph point color
 *
 * @param string $s Start color
 * @param string $e End color
 * @param integer $max Point count
 * @param integer $id Point ID
 * @return array
 */
function getGradientColor ( $s, $e, $max, $id ) {
  if ($id < 0    ) $id = 0;
  if ($id > $max ) $id = $max;

  if (!is_array($s)) {
    $s = str_replace('#','',$s);
    $s = array( hexdec(substr($s,0,2)), hexdec(substr($s,2,2)), hexdec(substr($s,4,2)) );
  }
  if (!is_array($e)) {
    $e = str_replace('#','',$e);
    $e = array( hexdec(substr($e,0,2)), hexdec(substr($e,2,2)), hexdec(substr($e,4,2)) );
  }
  return (!$max)
       ? array( $s[0], $s[1], $s[2] )
       : array(
           round( max(0, $s[0] - ( (($e[0]-$s[0])/-$max) * $id )) ),
           round( max(0, $s[1] - ( (($e[1]-$s[1])/-$max) * $id )) ),
           round( max(0, $s[2] - ( (($e[2]-$s[2])/-$max) * $id )) )
         );
}