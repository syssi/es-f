<?php
/**
 * Yuelo Extension input button image source
 *
 * @usage
 * @code
 * Content:
 *   Set translation for "Save"...
 *
 * Template:
 *   {button:"t",[[Save]]}
 *
 * Result:
 *   button/button.php?t=Save
 * @endcode
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-43-g9eb0fbd - Tue Jan 11 21:51:29 2011 +0100 $
 */
class Yuelo_Extension_Button extends Yuelo_Extension {

  /**
   * Input button
   *
   * Provide pairs of "key1","value1"[,"key2","value2"]
   *
   * @return string
   */
  public static function Process() {
    $args = array();
    $cnt = func_num_args();
    for ($i=0; $i<$cnt; $i++) {
      $arg = func_get_arg($i++) . '=';
      if ($i<$cnt) $arg .= rawurlencode(func_get_arg($i));
      $args[] = str_replace('"', '&quot;', $arg);
    }
    return 'button/button.php?' . implode('&amp;', $args);
  }

}