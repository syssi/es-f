<?php
/**
 * button.php
 *
 * Generate variable buttons with text from a "button template"
 *
 * @package    button-php
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2008 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.1.0
 * @since      File available since Release 0.1.0
 */

// ----------------------------------------------------------------------------
// Configuration
// ----------------------------------------------------------------------------

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
 * Length for cache file name
 *
 * Max. 32 chars
 *
 * Build using md5 hash over all serialized button parameters
 */
define( 'NAME_LENGTH', 8 );

/**
 * Location of TTF font files, RELATIVE to this script
 */
define( 'TTF_FONT_DIR', './fonts' );

/**
 * File name with default settings
 *
 * If you use this, every call of button.php will open this file, the settings will here not be cached!
 *
 * To disable this, set this feature to FALSE and customize instead the $CFG-array.
 */
define( 'DEFAULT_FILE', 'button.cfg.php' );
#define( 'DEFAULT_FILE', FALSE );

/**
 * Default parameters
 */
$CFG = array(
  'i' => 'button.gif',   // image file name
  't' => '',             // text to display
  'a' => 'c',            // text-align: l|c|r
  'o' => 0,              // text offset in pixel, extra space before/after text
  'l' => 0,              // left text offset in pixel
  'r' => 0,              // right text offset in pixel
  'w' => 0,              // fix with of resulting button
  'f' => 0,              // system font 1..5 or TTF-font[,height] ; e.g. "arial" or "verdana,10"
  'c' => '000000',       // text color, hex value
  's' => '',             // shadow color: hexvalue[,offset] , default offset=1
  'x' => 0,              // delta x from center
  'y' => 0,              // delta y from center, shadow will recognized automatic
  'm' => '',             // resulting mime type, default same as image file
  'n' => FALSE,          // no caching
  'd' => DEFAULT_FILE,   // file with button settings, overwriting this settings
);

// ****************************************************************************
// DON'T CHANGE FROM HERE
// ****************************************************************************
error_reporting(0);
#error_reporting(-1);

if ($dbg = isset($_GET['debug'])) error_reporting(-1);
unset($_GET['debug']);

if (isset($_GET['clear'])) {
  // clear cached images
  if (CACHE_DIR) foreach (glob(CACHE_DIR.'/*') as $thumb) unlink($thumb);
  _die('Button cache cleared.');
}

$CACHE_DIR = isset($_GET['n']) ? FALSE : CACHE_DIR;

if ($CACHE_DIR) {
  is_dir($CACHE_DIR) OR @mkdir($CACHE_DIR);
  is_writeable($CACHE_DIR) OR ($CACHE_DIR = FALSE);
}

// Overwrite 
if (!empty($_GET['d'])) {
  $CFG['d'] = $_GET['d'];
  unset($_GET['d']);
}
// 1. use $CFG from above
// 2. read defaults from file if defined
/**
 * Include configured user config file
 */
if (file_exists($CFG['d'])) include $CFG['d'];
 
// no more used
unset($CFG['d']);

// 3. check $_GET params
foreach($_GET as $key => $val) {
  $lkey = strtolower($key);
  if (isset($CFG[$lkey])) $CFG[$lkey] = $val;
  elseif($dbg)           _die(sprintf('Unknown parameter: %s = %s', $key, $val));
}
unset($key, $lkey, $val);

// remove slashes
if (get_magic_quotes_gpc()) $CFG = array_map('stripslashes', $CFG);

// let's go
if (function_exists('iconv') AND
    $t = @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $CFG['t'])) {
  $CFG['t'] = $t;
}

$mimes = array ( 1=>'gif', 2=>'jpg', 3=>'png' );

if (substr($CFG['i'], 0, 1) != '/') $CFG['i'] = dirname(__FILE__).'/'.$CFG['i'];

$CFG['i'] = realpath($CFG['i']);

$size = @GetImageSize($CFG['i']);
if (!$size) _die('Missing image: '.$CFG['i']);

if (!isset($mimes[$size[2]])) _die('Unknown image type: '.$size[2]);

// find output mime type
if (!in_array(strtolower($CFG['m']), array('gif','jpeg','jpg','png'))) $CFG['m'] = '';
if ($CFG['m'] == 'jpg') $CFG['m'] = 'jpeg';
if (!$CFG['m']) $CFG['m'] = $mimes[$size[2]];

/**#@+
 * @ignore
 */
define ( 'L',  0 );  # left
define ( 'M',  1 );  # middle
define ( 'R',  2 );  # right
define ( 'W', 10 );  # width
define ( 'H', 11 );  # height
/**#@-*/

// ----------------------------------------------------------------------------
// create image
// ----------------------------------------------------------------------------
$i[H] = $size[1];

if (!$CFG['f']) $CFG['f'] = floor($i[H]/5);

if (abs($CFG['x']) > floor($size[0]/2))
  _die('Can\'t move "column to stretch" outside button template.');

// width left/right ends
$wi[L] = floor($size[0]/2) + $CFG['x'];
$wi[M] = 1;
$wi[R] = $size[0] - $wi[L] - $wi[M];

// start coordinates of image for left/middel/right
$xi[L] = 0;
$xi[M] = $wi[L];
$xi[R] = $xi[M] + 1;

// text width
if (is_numeric($CFG['f'])) {
  $tw = strlen($CFG['t']) * ImageFontWidth($CFG['f']);
  $CFG['y'] = floor( ($i[H] - ImageFontHeight($CFG['f']))/2 ) + $CFG['y'];
} else {
  if (strstr($CFG['f'],',')) {
    list($CFG['f'],$CFG['h']) = explode(',',$CFG['f']);
  } else {
    $CFG['h'] = 0;
  }

  if (substr($CFG['f'],-4) != '.ttf') $CFG['f'] .= '.ttf';
  $ffile = dirname(__FILE__).'/'.TTF_FONT_DIR.'/'.$CFG['f'];
  if (!file_exists($ffile)) _die('Missing font file: '.$ffile);

  if (!$CFG['h']) $CFG['h'] = floor($i[H]/2);
  if ($CFG['h'] > $i[H]) $CFG['h'] = floor($i[H]/1.1);

  $h = ImageTTFBbox($CFG['h'], 0, $ffile, $CFG['t']);
  $tw = abs($h[4]-$h[0]);
  $dx = -floor($h[0]/2);
  $CFG['y'] = floor( ($i[H]+abs($h[5]-$h[1]))/2 - 1 ) + $CFG['y'] - $h[1];
  unset($h);
}

// add offset simply to text size
$tw += 2*$CFG['o'];

if ($CFG['s']) {
  // adjustments for text shadow
  if (strstr($CFG['s'], ',')) {
    list($CFG['s'], $CFG['so']) = explode(',', $CFG['s']);
  } else {
    $CFG['so'] = 1;
  }
} else {
  $CFG['so'] = 0;
}

// hash over all configuration data for unique thumb file name
$ETag = md5(serialize($CFG).filemtime($CFG['i']));

if (!$CFG['n'] AND isset($_SERVER['HTTP_IF_NONE_MATCH']) AND strpos($_SERVER['HTTP_IF_NONE_MATCH'], $ETag)) {
  // Client's cache IS current, so we just respond '304 Not Modified'.
  Header('HTTP/1.1 304 Not Modified');
  exit;
}

// common headers
Header('ETag: "'.$ETag.'"');
Header('Cache-Control: private');
Header('Content-Type: image/'.$CFG['m']);

if (!$dbg AND $CACHE_DIR) {
  $cdir = $CACHE_DIR;
  if (substr($CACHE_DIR, 0, 1) != '/') $cdir = dirname(__FILE__).'/'.$cdir;
  // try to get cached version
  $cfile = $cdir . '/' . substr($ETag, 0, NAME_LENGTH) . '.' . $CFG['m'];
  if (file_exists($cfile)) {
    Header('Content-Length: '.filesize($cfile));
    Header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($cfile)).' GMT');
    Header('HTTP/1.1 200 OK');
    readfile($cfile);
    exit;
  }
} else {
  $cdir = $cfile = FALSE;
}

// width left/right ends
$wb[L] = $wi[L];
$wb[M] = $tw;
$wb[R] = $wi[R];

if (!$wb[M]) $wb[M] = 1;

// alignment
switch ($CFG['a']) {
  case 'c':
    $_o = floor(($CFG['w']-array_sum($wb))/2 - $CFG['l'] - $CFG['r']);
    if ($_o > 0) {
      $CFG['o'] += $_o;
    }
    break;
  case 'r':
    $CFG['l'] = $CFG['w'] - array_sum($wb) - $CFG['r'] - $CFG['so'];
    break;
}

if (!$CFG['w']) {
  $wb[M] = $tw + $CFG['l'] + $CFG['r'];
} else {
  $_m = $CFG['w'] - $wi[L] - $wi[R];
  if ($_m > $tw) {
    $wb[M] = $_m;
  }
}

// start coordinates of button for left/middel/right
$xb[L] = 0;
$xb[M] = $wi[L];
$xb[R] = $xb[M] + $wb[M];

// adjustments for text shadow
$wb[M] += $CFG['so'];
$xb[R] += $CFG['so'];
$CFG['y'] -= floor(($CFG['so'])/2) + 1;

// image width
$i[W] = array_sum($wb);

// cache image?
if ($cdir AND !is_writable($cdir)) _die('Can not write to '.$cdir);

// prepare template image and button
$image = call_user_func('ImageCreateFrom'.$mimes[$size[2]], $CFG['i']);
$button = ImageCreate($i[W], $i[H]);

if ($size[2] != 2) {
  // gif or png
  $ccolor = ImageColorTransparent($image);
  ImagePaletteCopy($button, $image);
  ImageFill($button, 0, 0, $ccolor);
  ImageColorTransparent($button, $ccolor);
}

ImageCopy(       $button, $image, $xb[L], 0, $xi[L], 0, $wi[L], $i[H]);
ImageCopyResized($button, $image, $xb[M], 0, $xi[M], 0, $wb[M], $i[H], $wi[M], $i[H]);
ImageCopy(       $button, $image, $xb[R], 0, $xi[R], 0, $wi[R], $i[H]);

imagedestroy($image);

// adjust text output x-coord.
$xb[M] += $CFG['o'] + $CFG['l'];

if ($CFG['s']) {
  $scolor = ImageColorAllocateFromHex($button, $CFG['s']);
  $xb[M] += $CFG['so'];
  $CFG['y'] += $CFG['so'];
  if ($CFG['so'] > 0) {
    for ($i=$CFG['so']; $i>0; $i--) {
      if (is_numeric($CFG['f'])) {
        ImageString($button, $CFG['f'], $xb[M]--, $CFG['y']--, $CFG['t'], $scolor);
      } else {
        ImageTTFtext($button, $CFG['h'], 0, $dx+$xb[M]--, $CFG['y']--, $scolor, $ffile, $CFG['t']);
      }
    }
  } else {
    for ($i=$CFG['so']; $i<0; $i++) {
      if (is_numeric($CFG['f'])) {
        ImageString($button, $CFG['f'], $xb[M]++, $CFG['y']++, $CFG['t'], $scolor);
      } else {
        ImageTTFtext($button, $CFG['h'], 0, $dx+$xb[M]++, $CFG['y']++, $scolor, $ffile, $CFG['t']);
      }
    }
  }
}

$tcolor = ImageColorAllocateFromHex($button, $CFG['c']);

if (is_numeric($CFG['f'])) {
  ImageString($button, $CFG['f'], $xb[M], $CFG['y'], $CFG['t'], $tcolor);
} else {
  $h = ImageTTFtext($button, $CFG['h'], 0, $dx+$xb[M], $CFG['y'], $tcolor, $ffile, $CFG['t']);
}

if (!$dbg) {
  if ($cfile) {
    call_user_func('Image'.$CFG['m'], $button, $cfile);
    Header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($cfile)).' GMT');
  }
  Header('Content-Length: '.filesize($cfile));
  Header('HTTP/1.1 200 OK');
  call_user_func('Image'.$CFG['m'], $button);
  imagedestroy($button);
} else {
  echo '<pre>', print_r($CFG, TRUE), '</pre>';
}


// ****************************************************************************
// functions
// ----------------------------------------------------------------------------

/**
 * Allocate a color using a hex string
 *
 * Works with 6 and 3 char long hex codes
 *
 * @param resource &$img Image
 * @param string $hexstr Hex color code, 6 or 3 chars
 * @return integer Color index in image
 */
function ImageColorAllocateFromHex( &$img, $hexstr ) {
  if (strlen($hexstr) == 3) {
    $hexstr = $hexstr{0}.$hexstr{0}.$hexstr{1}.$hexstr{1}.$hexstr{2}.$hexstr{2};
  }
  $int = hexdec($hexstr);
  $red = 0xFF & ($int>>0x10);
  $green = 0xFF & ($int>>0x8);
  $blue = 0xFF & $int;
  // try to get existing color first
  $idx = imagecolorexact($img, $red, $green, $blue);
  // allocate new color if not exists
  if ($idx == -1) $idx = ImageColorAllocate($img, $red, $green, $blue);
  return $idx;
}

/**
 * Die with error message on an image
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
    Header('Content-type: image/gif');
    ImageGif($i);
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
