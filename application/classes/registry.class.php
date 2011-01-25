<?php
/**
 * Registry class to pass global variables between classes.
 *
 * It is possible to save/restore the values onto a internal stack.
 *
 * @ingroup    Registry
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2008-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class Registry {

  /**
   * Separator to split key into array
   *
   * @var string $NameSpaceSeparator
   */
  public static $NameSpaceSeparator = '::';

  /**
   * Default return value, if requested variable is not set
   *
   * @var string $NVL
   */
  public static $NVL = NULL;

  /**
   * Adds a new variable to the Registry.
   *
   * @usage
   * @code
   *   Key.SubKey.SubSubKey
   * @endcode
   * will result in
   * @code
   *   &self::$Data[0][Key][SubKey][SubSubKey]
   * @endcode
   *
   * @param string|array $keys Name of the variable | Array of key => value pairs to merge
   * @param mixed $value Value of the variable
   * @return void
   */
  public static function set( $keys, $value=NULL ) {
    if (is_array($keys) AND is_null($value)) {
      // merge an array into global space
      foreach ($keys as $key => $value) self::set($key, $value);
    } else {
      $Data =& self::$Data[0];
      if (!empty($keys)) {
        foreach (self::Key2Array($keys) as $key) {
          $Data =& $Data[$key];
        }
      }
      $Data = $value;
    }
  }

  /**
   * Adds additional data to a registry variable
   *
   * @see set()
   * @param string $keys Name of the variable
   * @param mixed $value Value of the variable
   * @return void
   */
  public static function add( $keys, $value=NULL ) {
    $reg = self::get($keys);

    if (!is_array($value)) $value = array($value);

    if (is_null($reg)) {
      $reg = $value;
    } else {
      if (!is_array($reg)) {
        // transform to array
        $reg = array($reg);
      }
      $reg = array_merge($reg, $value);
    }
    return self::set($keys, $reg);
  }

  /**
   * Returns the value of the specified $key in the Registry.
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
    if (empty($keys) OR is_array($keys)) {
      $Data = self::getAll();
    } else {
      $Data = self::$Data[0];
      foreach (self::Key2Array($keys) as $key) {
        if (/*is_array($Data) AND*/ isset($Data[$key])) {
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
   * Returns the whole Registry as an array.
   *
   * @return array Whole Registry
   */
  public static function getAll() {
    return self::$Data[0];
  }

  /**
   * Removes a variable from the Registry.
   *
   * @param string $keys Name of the variable
   * @return void
   */
  public static function delete( $keys ) {
    $Data =& self::$Data[0];
    foreach (self::Key2Array($keys) as $key) {
      $Last =& $Data;
      $Data =& $Data[$key];
    }
    unset($Last[$key]);
  }

  /**
   * Removes all variables from the Registry.
   *
   * @return void
   */
  public static function clear() {
    self::$Data = array( array() );
  }

  /**
   * Save actual data
   *
   * @return int Id of data set, can be used for restoring specific id
   */
  public static function save() {
    array_unshift(self::$Data, array());
    return count(self::$Data)-1;
  }

  /**
   * Restore data saved before
   *
   * If no data {@link save()}d before or requested data set not exists,
   * an {@link RegistryException} comes up.
   *
   * It is possible to restore a specific data set
   * (id return by {@link save()})
   *
   * But use it careful, this will remove a data set inside a stack,
   * not only the last one. Normal sequences of save() & restore() work
   * like push & pop to a kind of data stack!
   *
   * @param int $id Restore id saved before
   * @return void
   * @throws RegistryException
   */
  public static function restore( $id=NULL ) {
    if (count(self::$Data) > 1) {
      if (isset($id)) {
        if (isset(self::$Data[$id])) {
          // restore specific data set
          self::$Data[0] = self::$Data[$id];
          unset(self::$Data[$id]);
        } else {
          throw new RegistryException('Can\'t restore Registry #'.$id.', not saved before!');
        }
      } else {
        // pop the next data set from registry stack
        array_shift(self::$Data);
      }
    } else {
      throw new RegistryException('Can\'t restore Registry, not saved before!');
    }
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Internal data container
   *
   * @var array $Data
   */
  private static $Data = array( array() );

  /**
   * Clear key and split into array
   *
   * @param string $key
   * @return void
   */
  private static function Key2Array( $key ) {
    $key = strtolower(trim($key, self::$NameSpaceSeparator));
    return explode(self::$NameSpaceSeparator, $key);
  }

}

/**
 * Exception class used by Registry class
 *
 * Handle this Exception like this:
 * @code
 * try {
 *   Registry::restore();
 *   // more code...
 * }
 * // Catch exception
 * catch (RegistryException $e) {
 *   echo 'Message: ' .$e->getMessage();
 * }
 * @endcode
 *
 * @ingroup    Registry
 */
class RegistryException extends Exception {}
