<?php
/**
 * Singleton collection
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
abstract class Singleton {

  /**
   * Get a named singleton instance, if not jey exists, create one
   *
   * @param string $name Class name to fetch
   * @param array $args Parameters for class constructor
   */
  public static function &get( $name, $args = array() )  {
    $name = strtolower($name);
    if (!isset(self::$instance[$name])) self::$instance[$name] = $name($args);
    return self::$instance[$name];
  }

  /**
   * Singleton buffer
   *
   * @var array $instance
   */
  private static $instance=array();

}