<?php
/** @defgroup ImageViewer Image viewer

Display images from the file system with scaling and rotating

@usage
@code
image.php?i=...
@endcode

Minimum parameter is the file name "i=" as
- absolute name in file system
- relative name from DOCUMENT_ROOT
- one of above BASE64 encoded: base64_encode($ImageFileName)

@author     Knut Kohl <knutkohl@users.sourceforge.net>
@copyright  2007-2011 Knut Kohl
@license    GNU General Public Licensehttp://www.gnu.org/licenses/gpl.txt
@version    1.2.0
@version    $Id$

@changelog
- Version 1.2.0
  - NEW: water marking
- Version 1.1.0
  - NEW: Shorten the hash

*/

/**
 * Use better (true color) GD functions if exists
 *
 * Needs a bit more system resources
 */
define('TRUECOLOR', TRUE);

/**
 * Directory for thumb files, absolue or relative to image location
 *
 * WITH TRAILING SLASH!
 */
define('THUMBDIR', './');
#define('THUMBDIR', '/tmp/');

/**
 * Create also thumbs for images, which are smaller then requested size
 */
define('RESIZE', FALSE);

/**
 * Hash length, 1..32, used for thumb file name
 */
define('HASHLEN', 8);

###############################################################################
# DON'T CHANGE FROM HERE
###############################################################################
if (!function_exists('gd_info')) die('ERROR: No GD library installed!');

#error_reporting(0);
error_reporting(-1);

$cfg = array(
  't' => TRUECOLOR,  // use better GD functions
  'n' => FALSE,      // no no-cache
  'd' => TRUE,       // die with error message
  'r' => 0,          // no rotation
  'wmc' => '000000', // water marking text color: black
  'wmf' => 5,        // water marking text font: 3
  'wmp' => 'br',     // water marking position: bottom right
);

#$cfg = array( 'n' => TRUE, 'd' => TRUE );
#_die(print_r(apache_request_headers(),true));
#_die(print_r($_SERVER,true));

$iname = NULL;

foreach ($_GET as $key => $val) {
  // filter possible parameters
  switch (strtolower($key)) {
    case 'n':
      // no cache
      $cfg['n'] = TRUE;
      break;
    case 'd':
      // die with transparent gif pixel
      $cfg['d'] = FALSE;
      break;
    case 'i':
      // image name
      $iname = $val;
      break;
    case 'm':
      // max. width/height
      if ((int)$val > 0) $cfg['m'] = (int)$val;
      break;
    case 'w':
    case 'x':
      // fixed width
      if ((int)$val > 0) $cfg['w'] = (int)$val;
      break;
    case 'h':
    case 'y':
      // fixed height
      if ((int)$val > 0) $cfg['h'] = (int)$val;
      break;
    case 'r':
      // rotate degrees clockwise
      $cfg['r'] = (int)$val;
      break;
    case 't':
      // use truecolor GD functions
      $cfg['t'] = TRUE;
      break;
    case 'wm':
      // water marking text
      $cfg['wm'] = stripslashes($val);
      break;
    case 'wmf':
      // water marking text font
      $cfg['wmf'] = (int)$val;
      break;
    case 'wmc':
      // water marking text color, default black
      $cfg['wmc'] = $val;
      break;
    case 'wmp':
      // water marking text position, 2 char combination, default bottom right
      // - vertical position - Top, Middle, Bottom
      // - horiz. position - Left, Center, Right
      $cfg['wmp'] = strtolower($val);
      break;
  }
}

if (empty($iname)) _die('Es wurde'."\n".'kein Bild'."\n".'angegeben!');

// test for real image name
// if relative path, MUST be from DOCUMENT_ROOT
$ImageName = (substr($iname,0,1) != '/') ? $_SERVER['DOCUMENT_ROOT'].'/'.$iname : $iname;
$Error = file_exists($ImageName) ? FALSE
       : 'Das Bild'."\n".$ImageName."\n".'konnte nicht gefunden werden!';

if ($Error) {
  // test for base64 encoded image name
  $iname = base64_decode($iname);
  // if relative path, MUST be from DOCUMENT_ROOT
  $ImageName = (substr($iname,0,1) != '/') ? $_SERVER['DOCUMENT_ROOT'].'/'.$iname : $iname;
  $Error = file_exists($ImageName) ? FALSE
         : 'Das Bild'."\n".$ImageName."\n".'konnte nicht gefunden werden!';
}

_die($Error);

// available image types
$ImgTypes = array( 1=>'GIF', 'JPEG', 'PNG', 'SWF', 'PSD', 'WBMP' );

$CacheDir = substr($ImageName, 0, strrpos($ImageName,'/')+1) . THUMBDIR;

$umask = umask(0);
if (!is_dir($CacheDir))      @mkdir($CacheDir, 0757);
if (!is_writable($CacheDir)) @chmod($CacheDir, 0757);
umask($umask);

// disable caching?
if (!is_writable($CacheDir)) $cfg['n'] = TRUE;

$ImageData = @GetImageSize($ImageName);

$ETag = md5(serialize($cfg).filemtime($ImageName).serialize($ImageData));

if (!$cfg['n'] AND isset($_SERVER['HTTP_IF_NONE_MATCH']) AND strpos($_SERVER['HTTP_IF_NONE_MATCH'], $ETag))
  // Client's cache IS current, so we just respond '304 Not Modified'.
  die(Header('HTTP/1.1 304 Not Modified'));


if (empty($ImageData[2]) || $ImageData[2] == 4 || $ImageData[2] == 5)
  _die('Bei der angegebenen Datei'."\n".'handelt es sich'."\n".'nicht um ein Bild!');

$ImgType = $ImgTypes[$ImageData[2]];

// idea from http://php.net/manual/function.constant.php
// UCN by Andre, 27-Apr-2003 10:10
if (!(ImageTypes() & constant('IMG_'.$ImgType)))
  _die('Das '.$ImgType.'-Format'."\n".'wird nicht unterstützt!');

if (isset($cfg['m'])) {
  if ($ImageData[0] > $ImageData[1])
    $cfg['w'] = $cfg['m'];
  else
    $cfg['h'] = $cfg['m'];
}

$x = (isset($cfg['w'])) ? (int)$cfg['w'] : $ImageData[0];
$y = (isset($cfg['h'])) ? (int)$cfg['h'] : $ImageData[1];
if (!is_int($x) OR !is_int($y))
  _die('Ungueltige(r)'."\n".'Groessen-Parameter!');

if (isset($cfg['w'])) $y = floor($x * $ImageData[1] / $ImageData[0]);
if (isset($cfg['h'])) $x = floor($y * $ImageData[0] / $ImageData[1]);

$Ext = substr(strrchr($ImageName, '.'), 1);

$ThumbFile = str_replace('.'.$Ext, '', basename($ImageName))
           . sprintf('.%s.%s', substr($ETag, 1, HASHLEN), $Ext);

$isCached = file_exists($CacheDir.$ThumbFile);

// if no cache thumb or "passthru", no thumb will created
$makeThumb = ($cfg['n'] OR !$isCached OR
              $ImageData[0] > $x OR $ImageData[1] > $y OR
              (($ImageData[0] < $x OR $ImageData[1] < $y) AND RESIZE));

$makeThumb = ($cfg['n'] OR !$isCached);

$Thumb = FALSE;

// create thumb
if ($makeThumb) {
  $Image = call_user_func('ImageCreateFrom'.$ImgType, $ImageName);

  if ($ImageData[2] == 1 OR $ImageData[2] == 3) {
    // gif or png
    $Thumb = ImageCreate($x, $y);
    $colorTransparent = ImageColorTransparent($Image);
    ImagePaletteCopy($Thumb,$Image);
    ImageFill($Thumb,0,0,$colorTransparent);
    ImageColorTransparent($Thumb, $colorTransparent);
  } else {
    // use better GD function if available
    $Thumb = ($cfg['t'] AND function_exists('ImageCreateTrueColor'))
           ? ImageCreateTrueColor($x, $y)
           : ImageCreate($x, $y);
  }
  
  // use better GD function if available
  if ($cfg['t'] AND function_exists('ImageCopyResampled')) {
    ImageCopyResampled($Thumb, $Image, 0, 0, 0, 0, $x, $y, $ImageData[0], $ImageData[1]);
  } else {
    ImageCopyResized($Thumb, $Image, 0, 0, 0, 0, $x, $y, $ImageData[0], $ImageData[1]);
  }
  ImageDestroy ($Image);
  
  // rotation
  if ($cfg['r'] != 0) $Thumb = ImageRotate($Thumb,360-$cfg['r'],0);

  // watermark
  if (!empty($cfg['wm'])) {
    if ($cfg['wmf'] < 0) {
      $cfg['wmf'] = 0;
    } elseif ($cfg['wmf'] > 5) {
      $cfg['wmf'] = 5;
    }
    $fw = ImageFontWidth($cfg['wmf']);
    $fh = ImageFontHeight($cfg['wmf']);
    $wml = strlen($cfg['wm']) * $fw;
    // vertical alignment
    if (strstr($cfg['wmp'], 't')) {       // top
      $wmy = 2;
    } elseif (strstr($cfg['wmp'], 'm')) { // middle
      $wmy = ($y-$fh)/2;
    } else {                              // bottom, default
      $wmy = $y-$fh-2;
    }
    // horizontal alignment
    if (strstr($cfg['wmp'], 'l')) {       // left
      $wmx = 2;
    } elseif (strstr($cfg['wmp'], 'c')) { // center
      $wmx = ($x-$wml)/2;
    } else {                              // right, default
      $wmx = $x-$wml-2;
    }
    $wmcolor = ImageColorAllocateFromHex($Thumb, $cfg['wmc']);
    imagestring($Thumb, $cfg['wmf'], $wmx, $wmy, $cfg['wm'], $wmcolor);
  }

  // caching
  if (!$cfg['n']) {
    // save to cache dir
    $ImageName = $CacheDir.$ThumbFile;
    call_user_func('Image'.$ImgType, $Thumb, $ImageName);
  }
} else {
  if ($isCached) {
    $ImageName = $CacheDir.$ThumbFile;
    Header('X-Cached: TRUE');
  }
}

Header('ETag: "'.$ETag.'"');
Header('Cache-Control: private');
Header('Content-Type: image/'.$ImgType);
Header('Content-Length: '.filesize($ImageName));
Header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($ImageName)).' GMT');
Header('HTTP/1.1 200 OK');
if ($Thumb) {
  // in case of thumb made or no cache thumb
  call_user_func('Image'.$ImgType, $Thumb);
  ImageDestroy ($Thumb);
} else {
  readfile($ImageName);
}

/******************************************************************************
 * Functions
 *****************************************************************************/

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
  return ImageColorAllocate($img, 0xFF & ($int>>0x10), 0xFF & ($int>>0x8), 0xFF & $int);
}

/**
 * Show error image ($dir=TRUE) or transparent pixel ($die=FALSE)
 *
 * @param mixed $msg Message string|Array of messages
 * @param boolean $die Show message or not
 */
function _die( $msg, $die=FALSE ) {
  if (!$msg) return;

  // Error is always a gif
  Header('Content-Type: image/gif');

  if ($die OR $GLOBALS['cfg']['d']) {
    $font = 2;
    $w = 0;

    if (!is_array($msg)) $msg = explode("\n",$msg);
    foreach ($msg as $m) if (strlen($m) > $w) $w = strlen($m);

    $img = ImageCreate(ImageFontWidth($font) * $w + 4,
                       (ImageFontHeight($font)+2) * count($msg) + 4);

    ImageColorAllocate($img, 255, 255, 255);
    $black = ImageColorAllocate($img, 0, 0, 0);

    $y = 2;
    foreach ($msg as $m) {
      ImageString($img, $font, 2, $y, $m, $black);
      $y += ImageFontHeight($font)+2;
    }

    ImageGif($img);
    ImageDestroy($img);
  } else {
    // transparent pixel
    echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAQAICTAEAOw');
  }
  exit;
}

/*
Source: http://php.net/manual/function.header.php
UCN by mandor at mandor dot net, 15-Feb-2006 02:14

When using PHP to output an image, it won't be cached by the client so if you
don't want them to download the image each time they reload the page,
you will need to emulate part of the HTTP protocol.

Here's how:

    // Test image.
    $fn = '/test/foo.png';

    // Getting headers sent by the client.
    $headers = apache_request_headers();

    // Checking if the client is validating his cache and if it is current.
    if (isset($headers['If-Modified-Since']) && 
        (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
        // Client's cache IS current, so we just respond '304 Not Modified'.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
    } else {
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
        header('Content-Length: '.filesize($fn));
        header('Content-Type: image/png');
        print file_get_contents($fn);
    }

That way foo.png will be properly cached by the client and you'll save bandwith. :)

-------------------------------------------------------------------------------
Source: http://php.net/manual/function.header.php
UCN by ondrew at quick dot cz, 17-Sep-2004 02:19

How to force browser to use already downloaded and cached file.

If you have images in DB, they will reload each time user views them. To prevent this,
web server must identify each file with ID.

When sending a file, web server attaches ID of the file in header called ETag.
header("ETag: \"uniqueID\");

When requesting file, browser checks if the file was already downloaded.
If cached file is found, server sends the ID with the file request to server.

Server checks if the IDs match and if they do, sends back
header("HTTP/1.1 304 Not Modified");
else
Server sends the file normally.

  $file = getFileFromDB();

  // generate unique ID
  $hash = md5($file['contents']);

  $headers = getallheaders();

  // if Browser sent ID, we check if they match
  if (ereg($hash, $headers['If-None-Match']))
  {
    header('HTTP/1.1 304 Not Modified');
  }
  else
  {
    header("ETag: \"{$hash}\"");
    header("Accept-Ranges: bytes");
    header("Content-Length: ".strlen($file['content']));
    header("Content-Type: {$mime}");
    header("Content-Disposition: inline; filename=\"{$file['filename']}\";");

    echo $file['content'];
  }
  exit();

*/
