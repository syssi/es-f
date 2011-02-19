<?php
/**
 * @ingroup    setup
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 *
 */
function LoadConfig() {

  $xml = new XML_Array_Configuration($GLOBALS['oCache']);

  if ($cfg = $xml->ParseXMLFile(BASEDIR.'/local/config/config.xml')) {
    foreach ($cfg['users'] as $user) $users[$user['name']] = $user['auth'];
    Registry::set('Users', $users);
    unset($cfg['users']);
    foreach ($cfg['esniper'] as $key => $val) Esniper::set($key, $val);
    unset($cfg['esniper']);
    Registry::set($cfg);
  }

}

/**
 * Put posted data into session
 *
 * @param string $step Configration step
 */
function savePosted( $step ) {
  $_SESSION['STEP'][$step] = @$_POST['data'];
}

/**
 * Get saved posted data from session
 *
 * @param string $step Configration step
 * @return array
 */
function getSavedPosted( $step ) {
  return isset($_SESSION['STEP'][$step]) ? $_SESSION['STEP'][$step] : FALSE;
}

/**
 * Check the $_POST-array for params and set NULL if missing
 * 
 * @param string $param1[, $param2 ...]
 */
function checkPosted() {
  foreach (func_get_args() as $key) {
    if (empty($_POST['data'][$key])) $_POST['data'][$key] = NULL;
  }
}

/**
 * Check the $_POST-array for required params and return FALSE if not set
 * 
 * @param string $param1[, $param2 ...]
 * @return boolean TRUE|FALSE
 */
function checkRequired() {
  foreach (func_get_args() as $key) {
    if (!isset($_POST['data'][$key])) return FALSE;
  }
  return TRUE;
}

/**
 *
 */
function CheckResult( $name, $ok=TRUE, $title='', $todo='', $msg=FALSE ) {
  global $setup2, $err;
  // hold error, if there was one earlier
  $err = ($err OR !$ok);
  $setup2[$name] = array($title, $todo, $msg);
}

/**
 * Calculate a real path using ../ and/or ./
 * 
 * @param string $path Full path
 * @return string shorten path
 */
function _RealPath( $path ) {
  $oldP = '';
  $newP = $path;
  while($newP!=$oldP){
    $oldP = $newP;
    $newP = preg_replace('~([^/]+/)([^/]+/)(\.\./)~ms','$1',$newP);
  }
  return str_replace('//','',str_replace('./','',$newP));
}

/**
 * Return "Done." or "Error!" span
 * 
 * Example:
 * <code>
 * <span class="ok">Done.</span>
 * <span class="error">Error!</span>
 * </code>
 * @param integer $rc Result code
 * @return string Formated string
 */
function fmtResult( $rc=TRUE ) {
  return sprintf('<span class="%s">%s</span>',
                 ($rc ? 'ok'    : 'error'),
                 ($rc ? 'Done.' : 'Error: '.$rc));
}

/**
 * Find installed GD version
 * 
 * Source: http://php.net/manual/function.gd-info.php,
 * UCN by Hagan Fox, 03-May-2005 01:35
 * 
 * @param integer $user_ver Return this version if set
 * @return integer Installed GD version
 */
function gdVersion($user_ver = 0) {
  if (!Extension_loaded('gd')) return;
  static $gd_ver = 0;
  // Just accept the specified setting if it's 1.
  if ($user_ver == 1) { $gd_ver = 1; return 1; }
  // Use the static variable if function was called previously.
  if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
  // Use the gd_info() function if possible.
  if (function_exists('gd_info')) {
      $ver_info = gd_info();
      preg_match('/\d/', $ver_info['GD Version'], $match);
      $gd_ver = $match[0];
      return $match[0];
  }
  // If phpinfo() is disabled use a specified / fail-safe choice...
  if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
      if ($user_ver == 2) {
          $gd_ver = 2;
          return 2;
      } else {
          $gd_ver = 1;
          return 1;
      }
  }
  // ...otherwise use phpinfo().
  ob_start();
  phpinfo(8);
  $info = ob_get_contents();
  ob_end_clean();
  $info = stristr($info, 'gd version');
  preg_match('/\d/', $info, $match);
  $gd_ver = $match[0];
  return $match[0];
} // End gdVersion()
