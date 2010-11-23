<?php
/**
 * Get a cookie value
 *
 * @usage
 * @code
 * Content:
 *   // set cookie 'UserName' => 'Knut Kohl'
 *
 * Template:
 *   Current User: {cookie:"UserName"}
 *
 * Result:
 *   Current User: Knut Kohl
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Cookie extends Yuelo_Extension {

  /**
   * Get a cookie value
   *
   * @param string $key Cookie key
   * @return string
   */
  public static function Process() {
    @list($key) = func_get_args();
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : NULL;
  }

}