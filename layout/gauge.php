<?php
/**
 * Generate a double gauge
 *
 * @package Gauge
 * @author Knut Kohl <software@knutkohl.de>
 * @version 1.0.1
 */

# -----------------------------------------------------------------------------
# Configuration
# -----------------------------------------------------------------------------

/**
 * Directoy for cached buttons, must be writable by script,
 * absolute path or relative to this script
 *
 * Use upload_tmp_dir, it is writable by default and in user scope
 *
 * Set to FALSE to disable caching
 */
define( 'CACHE_DIR', ini_get('upload_tmp_dir').'/.cache' );
#define( 'CACHE_DIR', FALSE );

/**
 * Length for cache file name, max. 32
 *
 * Build using hash over all button parameters
 */
define( 'NAME_LENGTH', 8 );

/**
 * 1. default parameters
 */
$cfg = array(
  'd' => 20,             # circle diameter
  'l' => 0,              # left gauge, 0..100
  'r' => 0,              # right gauge, 0..100
  'b' => '00CC00',       # gauge color, hex value
  'g' => 'CC0000',       # background color, hex value
  'm' => 'png',          # resulting mime type, default same as image file
  'n' => FALSE,          # no caching
);

# ****************************************************************************
# DON'T CHANGE FROM HERE
# ****************************************************************************
#error_reporting(0);
error_reporting(-1);

$dbg = isset($_GET['debug']);
unset($_GET['debug']);

if (isset($_GET['clear'])) {
  # clear cached images
  if (CACHE_DIR) {
    foreach (glob(CACHE_DIR.'/*') as $gauge) unlink($gauge);
  }
  _die('Gauge cache cleared.');
}

$CACHE_DIR = isset($_GET['n']) ? FALSE : CACHE_DIR;

if ($CACHE_DIR) {
  is_dir($CACHE_DIR) OR @mkdir($CACHE_DIR);
  is_writeable($CACHE_DIR) OR $CACHE_DIR = FALSE;
}

# 2. check params
foreach($_GET as $key => $val) {
  if (isset($cfg[strtolower($key)])) {
    $cfg[strtolower($key)] = $val;
  } else {
    _die('Unknown parameter: <tt>'.$key.'='.htmlspecialchars($val).'</tt>, allowed are <tt>d,'
       .implode(',',array_keys($cfg)).'</tt> and <tt>clear</tt> to clean gauge dir.');
  }
}

if (get_magic_quotes_gpc()) {
  $cfg = array_map('stripslashes', $cfg);
}

extract($cfg, EXTR_PREFIX_ALL, 'cfg');

if ($cfg_l < 0 OR $cfg_l > 100) _die('l: 0..100!');
if ($cfg_r < 0 OR $cfg_r > 100) _die('r: 0..100!');

# hash over all variable data for unique thumb file name
$hash = substr(md5(serialize($cfg)), 0, NAME_LENGTH);

unset($cfg);

if (!in_array(strtolower($cfg_m), array('gif','jpeg','jpg','png'))) {
  $cfg_m = 'png';
}
if ($cfg_m == 'jpg') {
  $cfg_m = 'jpeg';
}

if (!$dbg AND $CACHE_DIR) {
  $cdir = $CACHE_DIR;
  if (substr($CACHE_DIR, 0, 1) != '/') {
    $cdir = dirname(__FILE__).'/'.$cdir;
  }

  # ---------------------------------------------------------------------------
  # try to get cached version
  # ---------------------------------------------------------------------------
  $cfile = $cdir.'/'.$hash.'.'.$cfg_m;
  if (file_exists($cfile)) {
    Header('Content-type: image/'.$cfg_m);
    readfile($cfile);
    exit;
  }
} else {
  $cdir = $cfile = FALSE;
}

# -----------------------------------------------------------------------------
# create image
# -----------------------------------------------------------------------------
if ($cdir AND !is_writable($cdir)) _die('Can not write to '.$cdir);

$gauge = ImageCreate($cfg_d, $cfg_d);
$r = $cfg_d--/2 - 1;

$ccolor = ImageColorAllocateFromHex($gauge, 'FFFFFF');
$lcolor = ImageColorAllocateFromHex($gauge, 'FFFFFE');
  
if ($cfg_m != 'jpeg') {
  # gif or png
#  ImageFill($gauge, 0, 0, $ccolor);
  ImageColorTransparent($gauge, $ccolor);
}

$bcolor = ImageColorAllocateFromHex($gauge, $cfg_b);
$gcolor = ImageColorAllocateFromHex($gauge, $cfg_g);

# circle
ImageFilledEllipse($gauge, $r, $r, $cfg_d, $cfg_d, $bcolor);

# left pie
ImageFilledArc($gauge, $r, $r, $cfg_d, $cfg_d, 90, 90+$cfg_l*180/100, $gcolor, IMG_ARC_PIE);

# right pie
ImageFilledArc($gauge, $r, $r, $cfg_d, $cfg_d, 90-$cfg_r*180/100, 90, $gcolor, IMG_ARC_PIE);

imageline($gauge, $r, 0, $r, $cfg_d-1, $lcolor);

if (!$dbg) {
  if ($cfile) {
    call_user_func('Image'.$cfg_m, $gauge, $cfile);
  }
  Header('Content-type: image/'.$cfg_m);
  call_user_func('Image'.$cfg_m, $gauge);
}

imagedestroy($gauge);

# ****************************************************************************
# functions
# ----------------------------------------------------------------------------

/**
 * Allocate a color using a hex string
 *
 * @param resource &$img Image
 * @param string $hexstr Hex color code
 * @return integer Color index in image
 */
function ImageColorAllocateFromHex ( &$img, $hexstr ) {
  if (strlen($hexstr) == 3) {
    $hexstr = $hexstr{0}.$hexstr{0}.$hexstr{1}.$hexstr{1}.$hexstr{2}.$hexstr{2};
  }
  $int = hexdec($hexstr);
  return ImageColorAllocate($img, 0xFF & ($int >> 0x10), 0xFF & ($int >> 0x8), 0xFF & $int);
}

/**
 * Die with error message on image
 *
 * @param string $msg Error message
 */
function _die ( $msg ) {
  global $dbg;
  if ($dbg) {
    die($msg);
  } else {
    $f = 2;
    $fw = ImageFontWidth($f);
    $fh = ImageFontHeight($f);

    if (!is_array($msg)) {
      $msg = preg_split('~\s+~',strip_tags($msg));
    }

    $wm = 0;
    foreach ($msg as $hmm) {
      $l = strlen($hmm);
      if ($l > $wm) $wm = $l;
    }
    $wm *= $fw;

    do {
      $id = 0;
      $tm = array('');
      foreach ($msg as $hmm) {
        if ((strlen($tm[$id])+strlen($hmm)) * $fw > $wm) $tm[++$id] = '';
        $tm[$id] .= $hmm.' ';
      }
      $w = $wm + 5;
      $h = count($tm) * ($fh+1) + 4;
      $wm *= 2;
    } while ($w < 2*$h AND count($tm) > 1);

    $i = ImageCreate($w,$h);
    ImageColorAllocate ($i, 255, 255, 255);
    $c = ImageColorAllocate ($i, 255, 0, 0);
    $y = 2;
    foreach ($tm as $t) {
      ImageString($i, $f, 2, $y, trim($t), $c);
      $y += $fh+1;
    }
    Header('Content-type: image/png');
    ImagePNG($i);
    ImageDestroy($i);
    exit;
  }
}

/*
# ----------------------------------------------------------------------------
function d ( $var, $name='', $force=FALSE ) {
  global $dbg;
  if ($force OR $dbg) {
    echo '<pre>';
    if ($name) echo '<b><u>'.$name.'</u></b><br />';
    echo print_r($var,TRUE).'</pre>';
  }
}
*/

?>