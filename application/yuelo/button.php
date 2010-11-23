<?php
/**
 * Yuelo Extension button
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 */

/**
 * Input button
 *
 * Usage examples:
 * <pre>
 * Content:
 *   Set transation for "Save"...
 *
 * Template:
 *   {button:"t",[[Save]]}
 *
 * Result:
 * </pre>
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 */
class Yuelo_Extension_Button extends Yuelo_Extension {

  /**
   * Input button
   *
   * Provide pairs of "key1","value1"[,"key2","value2"]
   *
   * @static
   * @param string $textid Id to translate
   * @param string $params Parameters optional
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