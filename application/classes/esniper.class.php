<?php
/**
 * Registry class to pass global variables between classes.
 */
class Esniper {

  /**
   * Object registry provides storage for shared objects
   *
   * @var array
   * @access private
   */
  private static $Data = array();

  /**
   * Adds a new variable to the Registry.
   *
   * @param string $key Name of the variable
   * @param mixed $value Value of the variable
   * @return bool
   * @access public
   */
  public static function set( $key, $value=FALSE ) {
    self::$Data[$key] = $value;
    return TRUE;
  }

  /**
   * Adds additional data to a registry variable
   *
   * @param string $key Name of the variable
   * @param mixed $value Value of the variable
   * @return bool
   * @access public
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
   * @param string $key Name of the variable
   * @return mixed Value of the specified $key
   * @access public
   */
  public static function get( $key ) {
    return isset(self::$Data[$key]) ? self::$Data[$key] : NULL;
  }

  /**
   * Check, if a given key is allready set
   *
   * @param string $key Name of the variable
   * @return bool Is key set
   * @access public
   */
  public static function is_set( $key ) {
    return isset(self::$Data[$key]);
  }

  /**
   * Returns the whole Registry as an array.
   *
   * @return array Whole Registry
   * @access public
   */
  public static function getAll() {
    return self::$Data;
  }

  /**
   * Removes a variable from the Registry.
   *
   * @param string $key Name of the variable
   * @return bool
   * @access public
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
   * @access public
   */
  public static function clear() {
    self::$Data = array();
  }

}