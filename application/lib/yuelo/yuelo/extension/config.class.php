<?php
/**
 * Get a configuration parameter
 *
 * @usage
 * @code
 * Content:
 *   Yuelo::set('Webmaster', 'email@example.com');
 *
 * Template:
 *   Please Contact Webmaster: {config:"Webmaster"}
 *   // with NVL value
 *   Please Contact Webmaster: {config:"Admin", "adminemail@example.com"}
 *
 * Output:
 *   Please Contact Webmaster: email@example.com
 *   Please Contact Webmaster: adminemail@example.com
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Config extends Yuelo_Extension {

  /**
   * Get a configuration parameter
   *
   * @param string $key Configuration key
   * @param string $default Default value if key not found, default NULL
   * @return string
   */
  public static function Process() {
    @list($key, $default) = func_get_args();
    $return = Yuelo::get($key);
    return $return ? $return : $default;
  }

}