<?php
/**
 * Strip any HTML tags
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('CONTENT', "Some Value with HTML code...");
 *
 * Template:
 *   {CONTENT|striptags}
 *
 * Output:
 *   Some Value WITHOUT HTML code...
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_StripTags extends Yuelo_Filter {

  /**
   * Strip any HTML tags
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return strip_tags($param);
  }

}