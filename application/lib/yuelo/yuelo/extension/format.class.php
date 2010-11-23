<?php
/**
 * Formats a string using sprintf
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('SUM', 25);
 *
 * Template:
 *   Current balance: {format:SUM,'$ %01.2f'}
 *
 * Result:
 *   Current balance: $ 25.00
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Format extends Yuelo_Extension {

  /**
   * Formats a string using sprintf
   *
   * @param string $param Value to format
   * @param string $format Format definition
   * @return string
   */
  public static function Process() {
    @list($param, $format) = func_get_args();
    return sprintf($format, $param);
  }

}