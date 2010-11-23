<?php
/**
 * Calculate the size of a given array
 *
 * Usage Examples:
 * @code
 * Content:
 *   $template->assign('ARRAY', array(1,2));
 *   $template->assign('STRING', 'string');
 *
 * Template:
 *   {ARRAY|count}
 *   {STRING|count}
 *
 * Output:
 *   2
 *   1
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Count extends Yuelo_Filter {

  /**
   * Calculate the size of a given array
   *
   * @static
   * @param mixed $param
   * @return int Array count or 1 for non arrays
   */
  public static function Process( $param ) {
    return (is_array($param)) ? count($param) : 1;
  }

}