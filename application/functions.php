<?php
/**
 * Core functions
 *
 * @package es-f
 * @subpackage Core
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Define a constant if not defined yet
 *
 * @param string $define Name
 * @param string $value Value
 */
function _Define( $define, $value ) {
  /**
   * @ignore
   */
  defined($define) || define($define, $value);
}

/**
 *
 */
function encodeReturnTo( $params ) {
  return trim(base64_encode(serialize($params)), '=');
}

/**
 *
 */
function decodeReturnTo( $params='' ) {
  return unserialize(base64_decode($params));
}

/**
 * Normalize Path
 *
 * Replace all / with DIRECTORY_SEPARATOR
 *
 * @param $path (string)
 * @return string
 */
function np() {
  $args = func_get_args();
  $path = array_shift($args);
  if (count($args)) $path = vsprintf($path, $args);
  return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

/**
 * Find all layouts
 *
 * @return array Array of installed layouts
 */
function getLayouts() {
  $layouts = array();
  foreach (glob(BASEDIR.'/layout/*', GLOB_ONLYDIR) as $layout) {
    $layout = preg_replace('~.*/(.+)~', '$1', realpath($layout));
    if ($layout != 'images') $layouts[] = $layout;
  }
  return $layouts;
}

/**
 * Check which parameter is set
 *
 * @param array $request
 * @param string $param Category/Group
 * @return string
 */
function getNewRequest( $request, $param ) {
  return !empty($request[$param.'new'])
       ? $request[$param.'new']
       : ( !empty($request[$param])
           ? $request[$param]
           : '');
}

/**
 * Find actual layout according to actual layout
 *
 * Matrix for resulting module layout:
 *
 * <pre>
 *  global layout  | module layout | resulting module layout
 * ----------------+---------------+-------------------------
 *  default        | <any>         | <module layout>
 *  <some special> | default       | <some special>
 *  <some special> | <some other>  | <some other>
 * </pre>
 *
 * @param string $Layout Layout to check against
 */
function FindActualLayout( $Layout ) {
  if (Registry::get('Layout') != 'default' AND $Layout == 'default') {
    $Layout = Registry::get('Layout');
  }
  return $Layout;
}

/**
 * Find module/plugin specific scripts and styles
 *
 * Search in given directory and given layout alternatives for
 * style.css, print.css and script.js
 *
 * @param string $dir Directory to search
 * @param array $layouts Possible layouts
 * @return string
 */
function StylesAndScripts( $dir, $layouts ) {
  static $fmthead = array (
    '  <link type="text/css" rel="stylesheet" href="%s">',
    '  <link type="text/css" rel="stylesheet" href="%s" media="print">',
    '  <script type="text/javascript" src="%s"></script>',
  );

  if (!is_array($layouts)) $layouts = array($layouts);

  $htmlhead = array();

  foreach (array('style.css', 'print.css', 'script.js') as $id => $f) {
    foreach ($layouts as $layout) {
      // common for all layouts
      $file = $dir.'/layout/'.$f;
      if (file_exists(np($file))) $htmlhead[] = sprintf($fmthead[$id], $file);
      // for specific layout ...
      $file = $dir.'/layout/'.$layout.'/'.$f;
      if (file_exists(np($file))) $htmlhead[] = sprintf($fmthead[$id], $file);
    }
  }
  return implode("\n", $htmlhead);
}

/**
 * Make a string secure for use in file system
 *
 * @param string possibly insecure string
 * @return string file system secure string
 */
function Secure4fs( $str ) {
  // from http://www.php.net/manual/en/function.escapeshellcmd.php
  $w = "#&;`|*?~<>^()[]{}$\\\x0A\xFF";
  $l = strlen($w);
  for ($i=0; $i<$l; $i++)
    $str = str_replace($w[$i], sprintf('#%02x', ord($w[$i])), $str);
  return $str;
}

/**
 * Check if directory exists or create it and set permission
 *
 * @param string $dir Directory name
 * @param integer $chmod Permissions
 */
function checkDir( $dir, $chmod=755 ) {
  $Exec = Exec::getInstance();
  if (!is_dir($dir)) {
    if ($Exec->MkDir($dir, $res)) {
      Messages::addError($res);
    }
    is_dir($dir) OR die('Can\'t create directory ['.$dir.']!');
  }
  if ($Exec->ChMod($dir, $chmod, FALSE, $res)) {
    Messages::addError($res);
  }
  return realpath($dir);
}

/**
 * Try to make path relative to server document root
 *
 * @param string $file File name
 * @return string
 */
function RelativePath( $file ) {
  $_file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
  return ($_file != $file)
       // we could eliminate DOCUMENT_ROOT
       ? ((substr($_file,0,1) == '/') ? substr($_file,1) : $_file)
       // outside DOCUMENT_ROOT
       : $file;
}

/**
 * Check user specific confiuration file
 *
 * @param string $file User configuration file
 */
function checkUserConfig( $file ) {
  if (Loader::Load($file)) {
    if (isset($esniper['seconds'])) Esniper::set('seconds', $esniper['seconds']);
    if (isset($cfg['LANGUAGE']))    Registry::set('LANGUAGE', $cfg['LANGUAGE']);
    if (isset($cfg['STARTMODULE'])) Registry::set('STARTMODULE', $cfg['MODULE']);
    if (isset($cfg['LAYOUT']))      Registry::set('LAYOUT', $cfg['LAYOUT']);
    if (isset($cfg['MENUSTYLE']))   Registry::set('MENUSTYLE', @explode(',', $cfg['MENUSTYLE']));
  }
}

/**
 * Check a variable, if its value is member of a given array
 * 
 * Set default, if variable is not set yet
 *
 * @param mixed &$var Pointer to variable
 * @param array $values Allowed values
 * @param mixed $default Default value
 * @return mixed
 */
function checkAgainstArray( &$var, $values, $default=NULL ) {
  if (!count($values)) { return; }
  if (!is_array($values)) {
    $values = explode(',', $values);
  }
  if (!in_array($var, $values)) {
    if (is_null($default)) {
      // If default is NULL, first array value will be the default.
      $default = $values[0];
    }
    $var = $default;
  }
  return $var;
}

/**
 * checkRequest, check $_REQUEST for $param and set to $default if not found
 * 
 * @param string $param Request parameter
 * @param mixed $default Default value
 * @see checkArray
 */
function checkR( $param, $default=NULL ) {
  return checkArray($_REQUEST, $param, $default);
}

/**
 * Check an array for $param and set to $default if not found
 * 
 * @param array &$array to check
 * @param string $param Parameter
 * @param mixed $default Default value
 * @return mixed Array value
 */
function checkArray( &$array, $param, $default=NULL ) {
  if (!isset($array[$param])) {
    $array[$param] = $default;
  }
  return $array[$param];
}

/**
 * Check a value and return $default if empty
 *
 * @param mixed $value to check
 * @param mixed $default Default value
 * @return mixed
 */
function nvl( $value, $default=NULL ) {
  return ($value !== '') ? $value : $default;
}

/**
 * Get server protocol
 * 
 * @return string http|https
 */
function ServerProtocol() {
  return (isset($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) == 'on')
       ? 'https' : 'http';
}

/**
 * Output like echo and force the flush of the output buffer
 *
 * @param mixed $script
 */
function echo_script( $script ) {
echo <<<EOT
<script type="text/javascript">
// <![CDATA[
$script
// ]]>
</script>

EOT;
}

/**
 * Output like echo and force the flush of the output buffer
 *
 * @param mixed $params
 */
function echo_flush() {
  echo implode(func_get_args()), str_pad('',4096);
  flush();
}

/**
 * Output like printf and force the flush of the output buffer
 *
 * @param mixed $params
 */
function printf_flush() {
  if (func_num_args()) {
    $args = func_get_args();
    $str = array_shift($args);
    echo_flush(vsprintf($str, $args));
  } else {
    echo_flush();
  }
}

// ----------------------------------------------------------------------------
// Extension handling
// ----------------------------------------------------------------------------
/**
 * Set an value to the global Event variables
 * 
 * @internal 
 * @param string $scope module|plugin
 * @param string $extension Event name
 * @param string $var Variable name
 * @param mixed $value Variable value
 */
function setExtensionVar( $scope, $extension, $var, $value=NULL ) {
  Registry::set($scope.'.'.$extension.'.'.$var, $value);

  static $mark = array();

  $id = $scope.$extension;
  if (!isset($mark[$id])) {
    Messages::addError('<i>ATTENTION</i>: Due to design changes it is required to '
                      .Core::Link(Core::URL(array('module'=>'configuration',
                                                  'action'=>'edit',
                                                  'params'=>array('ext'=>$scope.'-'.$extension))),
                                            're-configure '.$scope.' '.$extension)
                      .'! Just re-save your settings, thats all...', TRUE);
    $mark[$id] = TRUE;
  }
}

/**
 * Format a variable value accoriding to its type for display
 */
function fmtVar ( $var ) {
  switch (TRUE) {
    case ($var === TRUE):   return 'TRUE';
    case ($var === FALSE):  return 'FALSE';
    case ($var === NULL):   return 'NULL';
    case is_int($var):
    case is_float($var):    return htmlspecialchars($var);
    case is_object($var):
    case is_array($var):    return '<pre>'.htmlspecialchars(print_r($var, TRUE)).'</pre>';
    default:                return '"'.htmlspecialchars($var).'"';
  }
}

/**
 * Translate accented utf8 characters over to non-accented using translation table
 *
 * @param string $str String to translate
 * @return string Translated string
 */
function utf8_unaccent( $str ) {
  static $map = array(
  "\xC3\x80" => 'A',  "\xC3\x81" => 'A',  "\xC3\x82" => 'A',  "\xC3\x83" => 'A',
  "\xC3\x84" => 'Ae', "\xC3\x85" => 'A',  "\xC3\x86" => 'AE', "\xC3\x87" => 'C',
  "\xC3\x88" => 'E',  "\xC3\x89" => 'E',  "\xC3\x8A" => 'E',  "\xC3\x8B" => 'E',
  "\xC3\x8C" => 'I',  "\xC3\x8D" => 'I',  "\xC3\x8E" => 'I',  "\xC3\x8F" => 'I',
  "\xC3\x90" => 'D',  "\xC3\x91" => 'N',  "\xC3\x92" => 'O',  "\xC3\x93" => 'O',
  "\xC3\x94" => 'O',  "\xC3\x95" => 'O',  "\xC3\x96" => 'Oe', "\xC3\x98" => 'O',
  "\xC3\x99" => 'U',  "\xC3\x9A" => 'U',  "\xC3\x9B" => 'U',  "\xC3\x9C" => 'Ue',
  "\xC3\x9D" => 'Y',  "\xC3\x9E" => 'P',  "\xC3\x9F" => 'ss',
  "\xC3\xA0" => 'a',  "\xC3\xA1" => 'a',  "\xC3\xA2" => 'a',  "\xC3\xA3" => 'a',
  "\xC3\xA4" => 'ae', "\xC3\xA5" => 'a',  "\xC3\xA6" => 'ae', "\xC3\xA7" => 'c',
  "\xC3\xA8" => 'e',  "\xC3\xA9" => 'e',  "\xC3\xAA" => 'e',  "\xC3\xAB" => 'e',
  "\xC3\xAC" => 'i',  "\xC3\xAD" => 'i',  "\xC3\xAE" => 'i',  "\xC3\xAF" => 'i',
  "\xC3\xB0" => 'o',  "\xC3\xB1" => 'n',  "\xC3\xB2" => 'o',  "\xC3\xB3" => 'o',
  "\xC3\xB4" => 'o',  "\xC3\xB5" => 'o',  "\xC3\xB6" => 'oe', "\xC3\xB8" => 'o',
  "\xC3\xB9" => 'u',  "\xC3\xBA" => 'u',  "\xC3\xBB" => 'u',  "\xC3\xBC" => 'ue',
  "\xC3\xBD" => 'y',  "\xC3\xBE" => 'p',  "\xC3\xBF" => 'y',
  "\xC4\x80" => 'A',  "\xC4\x81" => 'a',  "\xC4\x82" => 'A',  "\xC4\x83" => 'a',
  "\xC4\x84" => 'A',  "\xC4\x85" => 'a',  "\xC4\x86" => 'C',  "\xC4\x87" => 'c',
  "\xC4\x88" => 'C',  "\xC4\x89" => 'c',  "\xC4\x8A" => 'C',  "\xC4\x8B" => 'c',
  "\xC4\x8C" => 'C',  "\xC4\x8D" => 'c',  "\xC4\x8E" => 'D',  "\xC4\x8F" => 'd',
  "\xC4\x90" => 'D',  "\xC4\x91" => 'd',  "\xC4\x92" => 'E',  "\xC4\x93" => 'e',
  "\xC4\x94" => 'E',  "\xC4\x95" => 'e',  "\xC4\x96" => 'E',  "\xC4\x97" => 'e',
  "\xC4\x98" => 'E',  "\xC4\x99" => 'e',  "\xC4\x9A" => 'E',  "\xC4\x9B" => 'e',
  "\xC4\x9C" => 'G',  "\xC4\x9D" => 'g',  "\xC4\x9E" => 'G',  "\xC4\x9F" => 'g',
  "\xC4\xA0" => 'G',  "\xC4\xA1" => 'g',  "\xC4\xA2" => 'G',  "\xC4\xA3" => 'g',
  "\xC4\xA4" => 'H',  "\xC4\xA5" => 'h',  "\xC4\xA6" => 'H',  "\xC4\xA7" => 'h',
  "\xC4\xA8" => 'I',  "\xC4\xA9" => 'i',  "\xC4\xAA" => 'I',  "\xC4\xAB" => 'i',
  "\xC4\xAC" => 'I',  "\xC4\xAD" => 'i',  "\xC4\xAE" => 'I',  "\xC4\xAF" => 'i',
  "\xC4\xB0" => 'I',  "\xC4\xB1" => 'i',  "\xC4\xB2" => 'IJ', "\xC4\xB3" => 'ij',
  "\xC4\xB4" => 'J',  "\xC4\xB5" => 'j',  "\xC4\xB6" => 'K',  "\xC4\xB7" => 'k',
  "\xC4\xB8" => 'k',  "\xC4\xB9" => 'L',  "\xC4\xBA" => 'l',  "\xC4\xBB" => 'L',
  "\xC4\xBC" => 'l',  "\xC4\xBD" => 'L',  "\xC4\xBE" => 'l',  "\xC4\xBF" => 'L',
  "\xC5\x80" => 'l',  "\xC5\x81" => 'L',  "\xC5\x82" => 'l',  "\xC5\x83" => 'N',
  "\xC5\x84" => 'n',  "\xC5\x85" => 'N',  "\xC5\x86" => 'n',  "\xC5\x87" => 'N',
  "\xC5\x88" => 'n',  "\xC5\x89" => 'n',  "\xC5\x8A" => 'N',  "\xC5\x8B" => 'n',
  "\xC5\x8C" => 'O',  "\xC5\x8D" => 'o',  "\xC5\x8E" => 'O',  "\xC5\x8F" => 'o',
  "\xC5\x90" => 'O',  "\xC5\x91" => 'o',  "\xC5\x92" => 'CE', "\xC5\x93" => 'ce',
  "\xC5\x94" => 'R',  "\xC5\x95" => 'r',  "\xC5\x96" => 'R',  "\xC5\x97" => 'r',
  "\xC5\x98" => 'R',  "\xC5\x99" => 'r',  "\xC5\x9A" => 'S',  "\xC5\x9B" => 's',
  "\xC5\x9C" => 'S',  "\xC5\x9D" => 's',  "\xC5\x9E" => 'S',  "\xC5\x9F" => 's',
  "\xC5\xA0" => 'S',  "\xC5\xA1" => 's',  "\xC5\xA2" => 'T',  "\xC5\xA3" => 't',
  "\xC5\xA4" => 'T',  "\xC5\xA5" => 't',  "\xC5\xA6" => 'T',  "\xC5\xA7" => 't',
  "\xC5\xA8" => 'U',  "\xC5\xA9" => 'u',  "\xC5\xAA" => 'U',  "\xC5\xAB" => 'u',
  "\xC5\xAC" => 'U',  "\xC5\xAD" => 'u',  "\xC5\xAE" => 'U',  "\xC5\xAF" => 'u',
  "\xC5\xB0" => 'U',  "\xC5\xB1" => 'u',  "\xC5\xB2" => 'U',  "\xC5\xB3" => 'u',
  "\xC5\xB4" => 'W',  "\xC5\xB5" => 'w',  "\xC5\xB6" => 'Y',  "\xC5\xB7" => 'y',
  "\xC5\xB8" => 'Y',  "\xC5\xB9" => 'Z',  "\xC5\xBA" => 'z',  "\xC5\xBB" => 'Z',
  "\xC5\xBC" => 'z',  "\xC5\xBD" => 'Z',  "\xC5\xBE" => 'z',  "\xC6\x8F" => 'E',
  "\xC6\xA0" => 'O',  "\xC6\xA1" => 'o',  "\xC6\xAF" => 'U',  "\xC6\xB0" => 'u',
  "\xC7\x8D" => 'A',  "\xC7\x8E" => 'a',  "\xC7\x8F" => 'I',
  "\xC7\x90" => 'i',  "\xC7\x91" => 'O',  "\xC7\x92" => 'o',  "\xC7\x93" => 'U',
  "\xC7\x94" => 'u',  "\xC7\x95" => 'U',  "\xC7\x96" => 'u',  "\xC7\x97" => 'U',
  "\xC7\x98" => 'u',  "\xC7\x99" => 'U',  "\xC7\x9A" => 'u',  "\xC7\x9B" => 'U',
  "\xC7\x9C" => 'u',
  "\xC7\xBA" => 'A',  "\xC7\xBB" => 'a',  "\xC7\xBC" => 'AE', "\xC7\xBD" => 'ae',
  "\xC7\xBE" => 'O',  "\xC7\xBF" => 'o',
  "\xC9\x99" => 'e',

  "\xC2\x82" => ',',        // High code comma
  "\xC2\x84" => ',,',       // High code double comma
  "\xC2\x85" => '...',      // Tripple dot
  "\xC2\x88" => '^',        // High carat
  "\xC2\x91" => "\x27",     // Forward single quote
  "\xC2\x92" => "\x27",     // Reverse single quote
  "\xC2\x93" => "\x22",     // Forward double quote
  "\xC2\x94" => "\x22",     // Reverse double quote
  "\xC2\x96" => '-',        // High hyphen
  "\xC2\x97" => '--',       // Double hyphen
  "\xC2\xA6" => '|',        // Split vertical bar
  "\xC2\xAB" => '<<',       // Double less than
  "\xC2\xBB" => '>>',       // Double greater than
  "\xC2\xBC" => '1/4',      // one quarter
  "\xC2\xBD" => '1/2',      // one half
  "\xC2\xBE" => '3/4',      // three quarters

  "\xCA\xBF" => "\x27",     // c-single quote
  "\xCC\xA8" => '',         // modifier - under curve
  "\xCC\xB1" => '');        // modifier - under line
  return strtr($str, $map);
}

/**
 * Extends PHPs array_map to traverse arrays recursiv
 *
 * @param string|array $func Callback function or object method
 * @param array $array Array to traverse
 * @return array Mapped array
 */
function array_map_recursive( $func, $array ) {
  $newArray = array();
  foreach ( $array as $key => $value ) {
    $newArray[$key] = is_array($value)
                    ? array_map_recursive($func, $value) : $func($value);
  }
  return $newArray;
}

  /**
   * Returns an array with all keys from input lowercased or uppercased recursive.
   *
   * If $input ist no array, returns $input unchanged
   *
   * @param array $input
   * @param int $case CASE_UPPER or CASE_LOWER
   * @return mixed
   */
  function array_change_key_case_rec( $input, $case=CASE_LOWER ) {
    if (!is_array($input))
      return $input;

    $newArray = array_change_key_case($input, $case);

    foreach ($newArray as $key => $array){
      if (is_array($array)){
        $newArray[$key] = self::array_change_key_case_rec($array, $case);
      }
    }
    return $newArray;
  }

/**
 * @ignore
 */
function str2hex( $string, $sep='' ) {
  $hex = '';
  $len = strlen($string);
  for ($i=0; $i<$len; $i++) {
    if ($hex) $hex .= $sep;
    $hex .= str_pad(dechex(ord($string[$i])), 2, 0, STR_PAD_LEFT);
  }
  return strtoupper($hex);
}

/**
 * Emulate register_globals off
 * 
 * see http://php.net/manual/faq.misc.php#faq.misc.registerglobals
 */
function unregister_GLOBALS() {
  if (!ini_get('register_globals')) return;

  // Might want to change this perhaps to a nicer error
  if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    die('GLOBALS overwrite attempt detected!');
  }

  // Variables that shouldn't be unset
  $noUnset = array( 'GLOBALS', '_GET', '_POST', '_COOKIE',
                    '_REQUEST', '_SERVER', '_ENV', '_FILES' );

  $input = array_merge( $_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES,
                        (isset($_SESSION) && is_array($_SESSION)) ? $_SESSION : array());

  foreach ($input as $key => $dummy) {
    if (!in_array($key, $noUnset) AND isset($GLOBALS[$key])) unset($GLOBALS[$key]);
  }
}

/**
 * Wrapper function for $var = $expr ? $true : $false;
 *
 * $var = iif($expr, $true, $false);
 *
 * @param bool $expr
 * @param mixed $true Result for $expr == TRUE
 * @param mixed $false Result for $expr != TRUE
 * @return mixed
 */
function iif( $expr, $true, $false='' ) {
  return ($expr ? $true : $false);
}

/**
 * Extract numeric data from value
 *
 * Idea from http://php.net/manual/function.money-format.php
 * UCN by scot from ezyauctionz.co.nz at 06-Oct-2007 12:10
 *
 * @param string $value
 * @return float
 */
function toNum( $value, $decimalPlaces=2 ) {
  // remove all non numeric chars and html entities
  $value = preg_replace('~(&.+;|[^\d,.]+)~', '', $value);
  // split input value up to allow checking
  $bits = explode(',', $value);

  $value = (isset($bits[1]) AND strlen($bits[1]) < $decimalPlaces+1)
         ? // assume dot is a thousands seperator, so translate it
           // transform format  1.234.567,89  to  1234567.89
           str_replace(array('.',','), array('','.'), $value)
         : // assume comma is a thousands seperator, so remove it
           // transform format  1,234,567.89  to  1234567.89
           str_replace(',', '', $value);

  // return as float
  return (float)$value;
}

/**
 * Base 64 encode string and remove trailing '='
 *
 * @param string $str
 * @return string
 */
function _base64_encode( $str ) {
  return trim(base64_encode($str), '=');
}

/**
 * Build a gradient color
 *
 * @param string $start Start color
 * @param string $end End color
 * @param integer $max Range 0 ... $max
 * @param integer $id Id in range
 * @return array Colors (R,G,B)
 */
function getGradientColor( $start, $end, $max, $id ) {
  if ($id < 0    ) $id = 0;
  if ($id > $max ) $id = $max;

  if (!is_array($start)) {
    $start = str_replace('#', '', $start);
    $start = array( hexdec(substr($start,0,2)), hexdec(substr($start,2,2)), hexdec(substr($start,4,2)) );
  }
  if (!is_array($end)) {
    $end = str_replace('#', '', $end);
    $end = array( hexdec(substr($end,0,2)), hexdec(substr($end,2,2)), hexdec(substr($end,4,2)) );
  }
  return array(
    round( max(0, $start[0] - ( (($end[0]-$start[0])/-$max) * $id )) ),
    round( max(0, $start[1] - ( (($end[1]-$start[1])/-$max) * $id )) ),
    round( max(0, $start[2] - ( (($end[2]-$start[2])/-$max) * $id )) )
  );
}

if (!function_exists('image_type_to_Extension')) {
/**
 * before PHP 5.2
 *
 * @ignore 
 */
function image_type_to_Extension ( $imagetype ) {
  switch ($imagetype) {
    case IMAGETYPE_GIF     : return 'gif';
    case IMAGETYPE_JPEG    : return 'jpg';
    case IMAGETYPE_PNG     : return 'png';
    case IMAGETYPE_SWF     : return 'swf';
    case IMAGETYPE_PSD     : return 'psd';
    case IMAGETYPE_BMP     : return 'bmp';
    case IMAGETYPE_TIFF_II : return 'tiff';
    case IMAGETYPE_TIFF_MM : return 'tiff';
    case IMAGETYPE_JPC     : return 'jpc';
    case IMAGETYPE_JP2     : return 'jp2';
    case IMAGETYPE_JPX     : return 'jpf';
    case IMAGETYPE_JB2     : return 'jb2';
    case IMAGETYPE_SWC     : return 'swc';
    case IMAGETYPE_IFF     : return 'aiff';
    case IMAGETYPE_WBMP    : return 'wbmp';
    case IMAGETYPE_XBM     : return 'xbm';
    default                : return FALSE;
  }
}
}
