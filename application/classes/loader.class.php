<?php
/**
 * Class / file Loader
 *
 * @ingroup    Loader
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class Loader {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Paths to scan for class definition files
   *
   * @var array $AutoLoadPath
   */
  public static $AutoLoadPath = array();

  /**
   * Function setPreload...
   *
   * @param string $function Callback function pre load with one parameter: $file
   * @return boolean
   */
  public static function setPreload( $function=NULL ) {
    self::setCallback('Pre', $function);
  } // function setPreload()

  /**
   * Function setPostload...
   *
   * @param string $function Callback function after load with one parameter: $file
   * @return boolean
   */
  public static function setPostload( $function=NULL ) {
    self::setCallback('Post', $function);
  } // function setPostload()

  /**
   * Function Load...
   *
   * @throws LoaderException
   * @param string $file
   * @param string $once Load only once
   * @param string $throw Throw exception on missing files
   * @return boolean
   */
  public static function Load( $file, $once=TRUE, $throw=TRUE ) {
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($file)) {
      self::callback('Pre', $file);
      if ($once)
        include_once $file;
      else
        include $file;
      self::callback('Post', $file);
      return TRUE;
    } elseif ($throw) {
      throw new LoaderException(__METHOD__.' : Missing file: '.$file);
    }
    return FALSE;
  } // function Load()

  /**
   * Function Autoload...
   *
   * @param string $class
   * @return void
   */
  public static function __autoload( $class ) {
    $cpath = str_replace('_', DIRECTORY_SEPARATOR, strtolower($class));

    foreach (array(strtolower($cpath), $cpath) as $path) {
      foreach (self::$AutoLoadPath as $dir) {
        foreach (array('%s.class.php', '%s.if.php', '%s.php') as $file) {
          $file = sprintf($dir.DIRECTORY_SEPARATOR.$file, $path);
          // Don't throw an exception!
          if (self::Load($file, TRUE, FALSE)) {
            /// Yryie::Debug($class.' ('.$file.')');
            return;
          }
        }
      }
    }
  } // function Autoload()

  /**
   * Function Register...
   *
   * @return boolean
   */
  public static function Register( $throw=TRUE ) {
    if (function_exists('spl_autoload_register'))
      return spl_autoload_register(array(__CLASS__, '__autoload'), $throw);
    else
      return FALSE;
  } // function Register()

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Callback function definitions
   *
   * @var array $Callbacks
   */
  private static $Callbacks = array();

  /**
   *
   * @throws LoaderException
   * @param string $step Pre|Post
   * @param string $function
   */
  private static function setCallback( $step, $function ) {
    if ($function AND !function_exists($function))
      throw new LoaderException('Missing callback function: '.$function);
    self::$Callbacks[$step] = $function;
  }

  /**
   *
   * @param string $step Pre|Post
   * @param string &$file
   */
  private static function callback( $step, &$file ) {
    if (empty(self::$Callbacks[$step])) return;
    $callback = self::$Callbacks[$step];
    $callback($file);
  }

}

/**
 * Loader exception class
 *
 * @ingroup Loader
 */
class LoaderException extends Exception {}
