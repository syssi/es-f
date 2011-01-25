<?php
/**
 * Registry class to pass global variables between classes.
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-19-gc734aa2 - Sat Dec 25 22:49:29 2010 +0100 $
 */
abstract class Esniper {

  /**
   * Adds a new variable to the Registry.
   *
   * @param $key string Name of the variable
   * @param $value mixed Value of the variable
   * @return bool
   */
  public static function set( $key, $value=FALSE ) {
    self::$Data[$key] = $value;
    return TRUE;
  }

  /**
   * Adds additional data to a registry variable
   *
   * @param $key string Name of the variable
   * @param $value mixed Value of the variable
   * @return bool
   */
  public static function add( $key, $value=FALSE ) {
    $reg = self::get($key);
    if (!is_array($reg)) {
      $reg = array($reg);
    }
    $reg[] = $value;
    return self::set($key, $reg);
  }

  /**
   * Returns the value of the specified $key in the Registry.
   *
   * @param $key string Name of the variable
   * @return mixed Value of the specified $key
   */
  public static function get( $key ) {
    return isset(self::$Data[$key]) ? self::$Data[$key] : NULL;
  }

  /**
   * Check, if a given key is allready set
   *
   * @param $key string Name of the variable
   * @return bool Is key set
   */
  public static function is_set( $key ) {
    return isset(self::$Data[$key]);
  }

  /**
   * Returns the whole Registry as an array.
   *
   * @return array Whole Registry
   */
  public static function getAll() {
    return self::$Data;
  }

  /**
   * Removes a variable from the Registry.
   *
   * @param $key string Name of the variable
   * @return bool
   */
  public static function delete( $key ) {
    if (isset(self::$Data[$key])) {
      unset(self::$Data[$key]);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Removes all variables from the Registry.
   *
   * @return void
   */
  public static function clear() {
    self::$Data = array();
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Object registry provides storage for shared objects
   *
   * @var array $Data
   */
  private static $Data = array();

}