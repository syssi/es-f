<?php
/**
 * $_REQUEST wrapper
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class Request {

  /**
   * Default values for not defined request parameters
   *
   * @var array $Defaults
   */
  public static $Defaults = array ( 'action' => 'index' );

  /**
   * Returns the value of the specified $key in the Registry.
   *
   * @param string $key Name of the variable
   * @return mixed Value of the specified $key
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
   * Check if a module (and action) are requested
   *
   * @param string $module Module name to check
   * @param string $action Action name to check
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
   * @var array $_REQUEST
   */
  private static $_REQUEST = NULL;

  /**
   * Initialize slef::$_REQUEST buffer
   */
  private static function Init() {
    if (is_null(self::$_REQUEST))
      self::$_REQUEST = array_merge(self::$Defaults, $_REQUEST);
  }

}