<?php
/**
 * Replace double new lines with paragraphs
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('VAR', "line 1\n\nline 2\nline 3");
 *
 * Template:
 *   {VAR|nl2p}
 *
 * Output:
 *   &lt;p&gt;line 1&lt;/p&gt;&lt;p&gt;line 2&lt;br&gt;line 3&lt;/p&gt;
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_NL2P extends Yuelo_Filter {

  /**
   * Replace double new lines with paragraphs
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return '<p>'.nl2br(str_replace("\n\n", '</p><p>', trim($param))).'</p>';
  }

}