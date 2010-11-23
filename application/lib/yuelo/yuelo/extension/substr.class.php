<?php
/**
 * Print specific part of a string
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TITLE', 'Title');
 *
 * Template:
 *   &lt;span style="font-size:150%"&gt;{substr:TITLE,0,1}&lt;/span&gt;{substr:TITLE,1}
 *
 * Output:
 *   &lt;span style="font-size:150%"&gt;T&lt;/span&gt;itle
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_SubStr extends Yuelo_Extension {

  /**
   * Print specific part of a string
   *
   * @param string $str Text to work with
   * @param int $start Start of string part
   * @param int $length Length of string part
   * @return string
   */
  public static function Process() {
    @list($str, $start, $length) = func_get_args();
    if (!$start) $start = 0;
    if (!$length) $length = 0;
    return $length ? substr($str, $start, $length) : substr($str, $start);
  }

}