<?php
/**
 * Negate the given parameter
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('VAR', FALSE);
 *
 * Template:
 *   "{VAR}"
 *   "{VAR|not}"
 *
 * Output:
 *   ""
 *   "1"
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Not extends Yuelo_Filter {

  /**
   * Negate the given parameter
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return !$param;
  }

}