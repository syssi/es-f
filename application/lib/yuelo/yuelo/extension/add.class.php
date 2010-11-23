<?php
/**
 * Add some numeric values
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('V1', 1);
 *   $template->assign('V2', 2);
 *
 * Template:
 *   {add:V1,V2,"3"}
 *
 * Output:
 *   6
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Add extends Yuelo_Extension {

  /**
   * Add some numeric values
   *
   * @param numeric $param As many as you like...
   * @return string
   */
  public static function Process() {
    $result = 0;
    foreach (func_get_args() as $value) $result += $value;
    return $result;
  }

}