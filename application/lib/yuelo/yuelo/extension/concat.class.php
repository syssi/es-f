<?php
/**
 * Concatenate all given string parameters
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('VAR', 2);
 *
 * Template:
 *   {concat:"1",VAR,"3"}
 *   {:"1",VAR,"3"}  // This short form works also
 *
 * Output:
 *   123
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Concat extends Yuelo_Extension {

  /**
   * Concatenate all given parameters
   *
   * @param string $param As many as you like
   * @return string
   */
  public static function Process() {
    return implode(func_get_args());
  }

}