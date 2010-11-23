<?php
/**
 * Uppercase the first character of each word in a string
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TEASER', 'TEXT and more TEXT');
 *
 * Template:
 *   {TEASER|ucwords}
 *
 * Result:
 *   [Text And More Text]
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_UcWords extends Yuelo_Filter {

  /**
   * Uppercase the first character of each word in a string
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return ucwords(strtolower($param));
  }

}