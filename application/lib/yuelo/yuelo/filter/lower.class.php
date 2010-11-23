<?php
/**
 * Convert string to lower case
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NAME', 'John Doe');
 *
 * Template:
 *   Username: {NAME|lower}
 *
 * Output:
 *   Username: john doe
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Lower extends Yuelo_Filter {

  /**
   * Convert string to lower case
   *
   * @static
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return strtolower($param);
  }

}