<?php
/**
 * Make a string upper case
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NAME', 'John Doe');
 *
 * Template:
 *
 *   Username: {NAME|upper}
 *
 * Output:
 *   Username: JOHN DOE
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Upper extends Yuelo_Filter {

  /**
   * Make a string upper case
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return strtoupper($param);
  }

}