<?php
/**
 * Converts Special Characters to HTML Entities
 *
 * @usage
 * @code
 * Content:
 *   // Asume from translation...
 *   $template->assign('NEXT', 'Next Page &gt;&gt;');
 *
 * Template:
 *   &lt;a href="next.php"&gt;{html:NEXT}&lt;/a&gt;
 *
 * Output:
 *   &lt;a href="next.php"&gt;Next Page &amp;gt;&amp;gt;&lt;/a&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_HTML extends Yuelo_Extension {

  /**
   * Converts Special Characters to HTML Entities
   *
   * @param string $param Text to convert
   * @param bool $NL Convert new lines to &lt;br&gt;
   * @return string
   */
  public static function Process() {
    @list($param, $NL) = func_get_args();
    $param = htmlspecialchars(stripslashes($param), ENT_COMPAT, 'UTF-8');
    if ($NL) $param = nl2br($param);
    return trim($param);
  }

}