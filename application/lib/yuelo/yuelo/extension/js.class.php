<?php
/**
 * Extension js
 *
 * Formats text for output as part of javascript code
 *
 * If you have only one parameter, there is also a filter Yuelo_Filter_JS for that
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NEXT', "1", "'", "\n", "2");
 *
 * Template:
 *   {js:NEXT}
 *
 * Result:
 *   1\' 2
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_JS extends Yuelo_Extension {

  /**
   * Formats text for output as part of javascript code
   *
   * @param mixed $params As many parameters as you like
   * @return string
   */
  public static function Process() {
    $params = func_get_args();
    $params = str_replace("\r", '', implode($params));
    // contains HTML?
    $html = ($params != strip_tags($params));
    $params = str_replace("\n", ($html ? ' ' : '<br>'), $params);
    return str_replace(array('"',      '\'',   '<',   '/'  ),
                       array('&quot;', '\\\'', '\\<', '\\/'),
                       $params);
  }

}