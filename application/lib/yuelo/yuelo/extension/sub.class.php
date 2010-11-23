<?php
/**
 * Sub all given parameters from the 1st parameter
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('V1', 1);
 *
 * Template:
 *   {sub:"3","1",V1}
 *
 * Output:
 *   1
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Sub extends Yuelo_Extension {

  /**
   * Sub all given parameters from the 1st parameter
   *
   * @param numeric $param Parameter to sub from
   * @param numeric $params As many parameters as you like
   * @return numeric
   */
  public static function Process() {
    $params = func_get_args();
    $result = array_shift($params);
    foreach ($params as $value) $result -= $value;
    return $result;
  }

}