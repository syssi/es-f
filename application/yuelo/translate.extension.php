<?php
/**
 * Yuelo Extension translate
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

/**
 * Translate a text Id using parameters
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 */
class Yuelo_Extension_Translate extends Yuelo_Extension {

  /**
   * Form checkbox
   *
   * @static
   * @param string $textid Id to translate
   * @param string $params Parameters optional
   * @return string
   */
  public static function Process() {
    return Translation::get(func_get_args());
  }

}