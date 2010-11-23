<?php
/**
 * Calculate the length of a given string
 *
 * The string will be trimed before!
 *
 * Usage Examples:
 * @code
 * Content:
 *   $template->assign('STRING', ' string ');
 *
 * Template:
 *   {STRING|length}
 *
 * Output:
 *   6
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Length extends Yuelo_Filter {

  /**
   * Calculate the length of a given string
   *
   * @static
   * @param mixed $param
   * @return int
   */
  public static function Process( $param ) {
    return strlen(trim($param));
  }

}