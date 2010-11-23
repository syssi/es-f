<?php
/**
 *
 */

/**
 *
 */
class Request {

  public static $Defaults = array ( 'action' => 'index' );

  /**
   * Returns the value of the specified $key in the Registry.
   *
   * @param string $key Name of the variable
   * @return mixed Value of the specified $key
   * @access public
   */
  public static function get( $key ) {
    self::Init();
    return isset(self::$_REQUEST[$key]) ? self::$_REQUEST[$key] : NULL;
  }

  /**
   * Check, if a given key is allready set
   *
   * @param string $key Name of the variable
   * @return bool Is key set
   * @access public
   */
  public static function is_set( $key ) {
    self::Init();
    return isset(self::$_REQUEST[$key]);
  }

  /**
   * Removes a variable from the Registry.
   *
   * @param string $key Name of the variable
   * @return bool
   * @access public
   */
  public static function delete( $key ) {
    self::Init();
    if (isset(self::$_REQUEST[$key])) {
      unset(self::$_REQUEST[$key]);
      return TRUE;
    }
    return FALSE;
  }

  /**
   *
   */
  public static function check( $module, $action=NULL ) {
    return ( ( self::get('module') === $module ) AND
             ( !isset($action) OR ( self::get('action') === $action ) )
           );
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * $_REQUEST
   *
   * @var array
   * @access private
   */
  private static $_REQUEST = NULL;

  /**
   *
   */
  private static function Init() {
    if (is_null(self::$_REQUEST))
      self::$_REQUEST = array_merge(self::$Defaults, $_REQUEST);
  }

}