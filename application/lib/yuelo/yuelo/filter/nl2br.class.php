<?php
/**
 * Inserts HTML line breaks before all newlines in a string
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('VAR', "line 1\nline 2");
 *
 * Template:
 *   {VAR|nl2br}
 *
 * Output:
 *   line 1&lt;br&gt;line 2
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_NL2BR extends Yuelo_Filter {

  /**
   * Inserts HTML line breaks before all newlines in a string
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return nl2br($param);
  }

}