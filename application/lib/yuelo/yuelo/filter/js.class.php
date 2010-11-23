<?php
/**
 * Formats text for output as part of javascript code
 *
 * If you have more than one parameter, there is also a extension Yuelo_Extension_JS for that
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NEXT', "1'\n2");
 *
 * Template:
 *   {NEXT|ja}
 *
 * Result:
 *   1\' 2
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_JS extends Yuelo_Filter {

  /**
   * Formats text for output as part of javascript code
   *
   * @param mixed $param
   * @return string
   */
  public static function Process( $param ) {
    $param = str_replace("\r", '', $param);
    // contains HTML?
    $html = ($param != strip_tags($param));
    $param = str_replace("\n", ($html ? ' ' : '<br>'), $param);
    return str_replace(array('"',      '\'',   '<',   '/'  ),
                       array('&quot;', '\\\'', '\\<', '\\/'),
                       $param);
  }

}