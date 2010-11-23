<?php
/**
 * Print Content of Server variables
 *
 * @usage
 * @code
 * Template:
 *   {server:"REMOTE_ADDR"}
 *
 * Result:
 *   123.45.67.89
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Server extends Yuelo_Extension {

  /**
   * Print Content of Server variables
   *
   * @param string $key
   * @return string
   */
  public static function Process() {
    @list($key) = func_get_args();
    if ($key == 'REMOTE_HOST' AND empty($_SERVER['REMOTE_HOST']))
      $_SERVER['REMOTE_HOST'] = getHostByAddr($_SERVER['REMOTE_ADDR']);
    return isset($_SERVER[$key]) ? $_SERVER[$key] : '';
  }

}