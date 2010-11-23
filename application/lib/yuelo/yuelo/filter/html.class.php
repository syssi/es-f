<?php
/**
 * Converts Special Characters to HTML Entities
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NEXT', 'Next Page >>');
 *
 * Template:
 *   &lt;a href="next.php"&gt;{html:NEXT}&lt;/a&gt;
 *
 * Output:
 *   &lt;a href="next.php"&gt;Next Page &amp;gt;&amp;gt;&lt;/a&gt;
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_HTML extends Yuelo_Filter {

  /**
   * Converts Special Characters to HTML Entities
   *
   * @static
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return htmlspecialchars($param, ENT_COMPAT, 'UTF-8');
  }

}