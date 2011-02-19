<?php
/**
 * Translate a text Id using parameters
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class Yuelo_Extension_Translate extends Yuelo_Extension {

  /**
   * Uses Translation::get()
   *
   * @return string
   */
  public static function Process() {
    return Translation::get(func_get_args());
  }

}