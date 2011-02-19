<?php
/**
 * Template data system, mostly a TplData
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2008-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class TplData {

  /**
   * Separator to split key into array
   *
   * @var string $NameSpaceSeparator
   */
  public static $NameSpaceSeparator = '::';

  /**
   * Force array keys uppercase
   *
   * @var bool $KeysUppercase
   */
  public static $KeysUppercase = FALSE;

  /**
   * Default return value, if requested variable is not set
   *
   * @var mixed $NVL
   */
  public static $NVL = NULL;

  /**
   * Set a new variable in TplData.
   *
   * Example:
   * @code
   *   Key.SubKey.SubSubKey
   * @endcode
   * will result in
   * @code
   *   &self::$Data[Key][SubKey][SubSubKey]
   * @endcode
   *
   * @param string|array $keys Name of the variable
   * @param mixed $value Value of the variable
   * @return void
   */
  public static function set( $keys, $value=NULL ) {
    if (is_array($keys)) {
      self::$Data = array_merge(self::$Data, $keys);
    } else {
      $Data =& self::$Data;
      foreach (self::Key2Array($keys) as $key) {
        if (!isset($Data[$key]) OR is_array($Data)) {
          $Data =& $Data[$key];
        } else {
          $Data = array($key=>array());
          $Data =& $Data[$key];
        }
      }
      $Data = $value;
    }
  }

  /**
   * Adds additional data to a TplData variable
   *
   * @see set()
   * @param string $keys Name of the variable
   * @param mixed $value Value of the variable
   * @return void
   */
  public static function add( $keys, $value='' ) {
    $reg = self::get($keys);
    if (is_array($value)) {
      // force array
      if (empty($reg)) {
        $reg = array($value);
      } elseif (!is_array($reg)) {
        $reg = array($reg, $value);
      } else {
        $reg[] = $value;
      }
    } else {
      if (is_array($reg)) {
        $reg[] = $value;
      } else {
        // concatenate strings
        $reg .= $value;
      }
    }
    return self::set($keys, $reg);
  }

  /**
   * Merge additional data to a TplData variable array
   *
   * @see set()
   * @param string $keys Name of the variable
   * @param array $value Value of the variable
   * @return void
   */
  public static function merge( $keys, $value ) {
    if (isset($keys)) {
      $reg = self::get($keys);
      if (!is_array($reg)) {
        // force array
        $reg = array($reg);
      }
      if (!is_array($value)) {
        // add to array
        $reg[] = $value;
      } else {
        // merge arrays
        $reg = array_merge($reg, $value);
      }
      self::set($keys, $reg);
    } else {
      self::$Data = array_merge(self::$Data, (array)$value);
    }
  }

  /**
   * Returns the value of the specified $key in the TplData.
   *
   * If $keys is not set, return $default value
   *
   * If $keys is empty, return all data, but better use {@link getAll()}
   *
   * @param string $keys Name of the variable
   * @param mixed $default Value if $keys is not set
   * @return mixed Value of the specified $key
   */
  public static function get( $keys, $default=NULL ) {
    if (empty($keys)) {
      $Data = self::getAll();
    } else {
      $Data = self::$Data;
      foreach (self::Key2Array($keys) as $key) {
        if (isset($Data[$key])) {
          // move through path
          $Data = $Data[$key];
        } else {
          // not found, end here with default
          $Data = isset($default) ? $default : self::$NVL;
          break;
        }
      }
    }
    return $Data;
  }

  /**
   * Test if a key is empty
   *
   * @param string $keys Name of the variable
   * @return bool
   */
  public static function isEmpty( $keys ) {
    $value = self::get($keys);
    return empty($value);
  }

  /**
   * Returns the whole TplData as an array.
   *
   * @return array Whole TplData
   */
  public static function getAll() {
    return self::$Data;
  }

  /**
   * Removes a variable from the TplData.
   *
   * @param string $keys Name of the variable
   * @return void
   */
  public static function delete( $keys ) {
    $Data =& self::$Data;
    foreach (self::Key2Array($keys) as $key) {
      $Last =& $Data;
      $Data =& $Data[$key];
    }
    unset($Last[$key]);
  }

  /**
   * Removes all variables from the TplData.
   *
   * @return void
   */
  public static function clear() {
    self::$Data = array();
  }

  /**
   * Set a new variable in TplData.
   *
   * Example:
   * @code
   *   Key.SubKey.SubSubKey
   * @endcode
   * will result in
   * @code
   *   &self::$Constans[Key][SubKey][SubSubKey]
   * @endcode
   *
   * @param string $keys Name of the constant
   * @param mixed $value Value of the constant
   * @return void
   */
  public static function setConstant( $keys, $value ) {
    $Constants =& self::$Constants;
    foreach (self::Key2Array($keys) as $key) $Constants =& $Constants[$key];
    $Constants = $value;
  }

  /**
   * Returns the value of the constant $key in the TplData.
   *
   * If $keys is not set, return $default value
   *
   * If $keys is empty, return all data, but better use {@link getAllConstants()}
   *
   * @param string $keys Name of the constant
   * @param mixed $default Value if $keys is not set
   * @return mixed Value of the constant $key
   */
  public static function getConstant( $keys, $default=NULL ) {
    $Constants = self::$Constants;
    foreach (self::Key2Array($keys) as $key) {
      if (isset($Constants[$key])) {
        // move through path
        $Constants = $Constants[$key];
      } else {
        // not found, end here with default
        $Constants = isset($default) ? $default : self::$NVL;
        break;
      }
    }
    return $Constants;
  }

  /**
   * Returns the whole constants as an array.
   *
   * @return array All constants
   */
  public static function getAllConstants() {
    return self::$Constants;
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Internal data container
   * @var array $Data
   */
  private static $Data = array();

  /**
   * Internal constants container
   * @var array $Constants
   */
  private static $Constants = array();

  /**
   * Clear key and split into array
   *
   * @param string $key
   * @return array
   */
  private static function Key2Array( $key ) {
    if (self::$KeysUppercase) $key = strtoupper($key);
    // remove (mostly) trailing separators
    $key = trim($key, self::$NameSpaceSeparator);
    return explode(self::$NameSpaceSeparator, $key);
  }

}
