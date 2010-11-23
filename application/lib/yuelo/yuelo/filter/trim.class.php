<?php
/**
 * Trim a String
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TEASER', ' Text ');
 *
 * Template:
 *   [{TEASER|trim}]
 *
 * Result:
 *   [Text]
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_Trim extends Yuelo_Filter {

  /**
   * Trim a String
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return trim($param);
  }

}