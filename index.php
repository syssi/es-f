<?php
/**
 * Main program file
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
error_reporting(0);

if (file_exists('prepend.php')) require 'prepend.php';

define('_ESF_OK', TRUE);

try {

  /**
   * Application definitions
   */
  require_once 'application' . DIRECTORY_SEPARATOR . 'define.php';

  // check if required config files exists and required PHP version
  if (!is_file(LOCALDIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.xml') OR
      !is_file(LOCALDIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'state.xml') OR
      version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<')) {
    Header('Location: setup/index.php');
  }

  /**
   * Load DebugStack
   */
  $GLOBALS['DEBUGSTACK_ADD_VERSIONS'] = TRUE;
  require_once APPDIR . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR
             . 'debugstack' . DIRECTORY_SEPARATOR. 'debugstack.class.php';
  /**
   * Class autoloading
   */
  require_once APPDIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'loader.class.php';

  if (!Loader::Register()) {
    /**
     * Emulate autoloading for PHP < 5.1.2
     */
    function __autoload( $class ) { Loader::__autoload($class); }
  }

  Loader::$AutoLoadPath[] = APPDIR . DIRECTORY_SEPARATOR . 'classes';
  if (DEVELOP) Loader::setPreload('__develop');

  Loader::Load(BASEDIR.'/index.inc.php');

  // set garbage collection probability to 5%
  $gc = Yuelo_Cache::gc(5);

} catch (Exception $e) {

  if (DEVELOP) echo '<pre>', $e; else echo $e->getMessage();

}

if (file_exists('append.php')) require 'append.php';
